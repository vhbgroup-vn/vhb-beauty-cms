<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'brand_name', 'logo_path', 'support_email', 'support_phone',
        'announcement', 'social_links',
    ];

    protected function casts(): array
    {
        return ['social_links' => 'array'];
    }
}
