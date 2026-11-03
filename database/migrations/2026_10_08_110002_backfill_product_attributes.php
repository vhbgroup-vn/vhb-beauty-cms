<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $definitions = [];

        DB::table('products')
            ->whereNotNull('attributes')
            ->pluck('attributes')
            ->each(function ($rawAttributes) use (&$definitions): void {
                $attributes = is_array($rawAttributes)
                    ? $rawAttributes
                    : json_decode((string) $rawAttributes, true);

                if (! is_array($attributes)) {
                    return;
                }

                foreach ($attributes as $attribute) {
                    $name = trim((string) ($attribute['name'] ?? ''));

                    if ($name === '') {
                        continue;
                    }

                    $slug = Str::slug($name);
                    $values = array_values(array_filter(array_map(
                        static fn ($value): string => trim((string) $value),
                        is_array($attribute['values'] ?? null) ? $attribute['values'] : [],
                    )));

                    $definitions[$slug] ??= [
                        'name' => $name,
                        'values' => [],
                        'visible_by_default' => (bool) ($attribute['visible'] ?? true),
                        'used_for_variations' => (bool) ($attribute['used_for_variations'] ?? false),
                    ];

                    $definitions[$slug]['values'] = array_values(array_unique([
                        ...$definitions[$slug]['values'],
                        ...$values,
                    ]));
                    $definitions[$slug]['visible_by_default'] = $definitions[$slug]['visible_by_default'] || (bool) ($attribute['visible'] ?? true);
                    $definitions[$slug]['used_for_variations'] = $definitions[$slug]['used_for_variations'] || (bool) ($attribute['used_for_variations'] ?? false);
                }
            });

        foreach ($definitions as $slug => $definition) {
            if (DB::table('product_attributes')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('product_attributes')->insert([
                'name' => $definition['name'],
                'slug' => $slug,
                'type' => 'select',
                'values' => json_encode($definition['values'], JSON_UNESCAPED_UNICODE),
                'active' => true,
                'visible_by_default' => $definition['visible_by_default'],
                'used_for_variations' => $definition['used_for_variations'],
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Existing definitions are intentionally preserved on rollback.
    }
};
