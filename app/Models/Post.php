<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Post $post): void {
            if (blank($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    protected $fillable = [
        'title', 'slug', 'status', 'category', 'excerpt', 'content', 'cover_image_path',
        'author_name', 'tags', 'featured', 'published_at', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function staticBlocks(): MorphToMany
    {
        return $this->morphToMany(StaticBlock::class, 'static_blockable')
            ->withPivot('sort_order')
            ->orderByPivot('id');
    }
}
