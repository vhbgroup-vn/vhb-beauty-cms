<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if (blank($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    protected $fillable = [
        'title', 'slug', 'page_type', 'published', 'hero_image_path', 'content',
        'contact_email', 'contact_phone', 'contact_address', 'map_url', 'template', 'html_content',
        'excerpt', 'blocks', 'seo_title', 'seo_description', 'sort_order',
        'show_in_navigation', 'navigation_label',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'blocks' => 'array',
            'show_in_navigation' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function htmlContentIsFullDocument(): bool
    {
        $html = trim((string) $this->html_content);

        return $html !== '' && preg_match('/<!doctype\s+html\b|<html\b|<head\b|<body\b/i', $html) === 1;
    }

    public function staticBlocks(): MorphToMany
    {
        return $this->morphToMany(StaticBlock::class, 'static_blockable')
            ->withPivot('sort_order')
            ->orderByPivot('id');
    }
}
