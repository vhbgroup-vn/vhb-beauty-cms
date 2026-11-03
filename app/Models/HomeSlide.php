<?php

namespace App\Models;

use Database\Factories\HomeSlideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    /** @use HasFactory<HomeSlideFactory> */
    use HasFactory;

    protected $fillable = [
        'headline', 'subheadline', 'image_path', 'cta_label', 'cta_url',
        'active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
