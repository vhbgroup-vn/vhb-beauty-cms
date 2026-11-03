<?php

namespace App\Models;

use Database\Factories\ProductAttributeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductAttribute extends Model
{
    /** @use HasFactory<ProductAttributeFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'type', 'values', 'description', 'active',
        'visible_by_default', 'used_for_variations', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'values' => 'array',
            'active' => 'boolean',
            'visible_by_default' => 'boolean',
            'used_for_variations' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ProductAttribute $attribute): void {
            if (blank($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }

            if ($attribute->sort_order === null) {
                $attribute->sort_order = 0;
            }
        });
    }
}
