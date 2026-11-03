<?php

namespace App\Models;

use Database\Factories\StaticBlockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class StaticBlock extends Model
{
    /** @use HasFactory<StaticBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'published', 'excerpt', 'content', 'html_content',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (StaticBlock $block): void {
            if (blank($block->slug)) {
                $block->slug = Str::slug($block->title);
            }
        });
    }

    public function pages(): MorphToMany
    {
        return $this->morphedByMany(Page::class, 'static_blockable');
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'static_blockable');
    }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'static_blockable');
    }
}
