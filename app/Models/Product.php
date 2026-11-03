<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    protected $fillable = [
        'name', 'slug', 'sku', 'brand', 'category', 'product_type', 'status',
        'price', 'currency', 'price_min', 'price_max', 'compare_at_price', 'sale_price', 'sale_starts_at', 'sale_ends_at',
        'featured', 'short_description', 'description', 'image_path', 'gallery',
        'benefits', 'tags', 'attributes', 'variations', 'stock_status', 'manage_stock',
        'stock_quantity', 'backorders', 'sold_individually', 'weight', 'length',
        'width', 'height', 'shipping_class', 'purchase_note', 'reviews_enabled',
        'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'price_min' => 'float',
            'price_max' => 'float',
            'compare_at_price' => 'float',
            'sale_price' => 'float',
            'sale_starts_at' => 'datetime',
            'sale_ends_at' => 'datetime',
            'featured' => 'boolean',
            'manage_stock' => 'boolean',
            'sold_individually' => 'boolean',
            'reviews_enabled' => 'boolean',
            'stock_quantity' => 'integer',
            'benefits' => 'array',
            'tags' => 'array',
            'gallery' => 'array',
            'attributes' => 'array',
            'variations' => 'array',
        ];
    }

    public static function currencyOptions(): array
    {
        return [
            'VND' => 'VND - Vietnamese dong',
            'USD' => 'USD - US dollar',
            'EUR' => 'EUR - Euro',
            'GBP' => 'GBP - Pound sterling',
            'JPY' => 'JPY - Japanese yen',
        ];
    }

    public function getCurrentPriceAttribute(): ?float
    {
        if ($this->price === null) {
            return $this->sale_price !== null ? (float) $this->sale_price : null;
        }

        if ($this->sale_price === null) {
            return (float) $this->price;
        }

        $now = now();
        $starts = $this->sale_starts_at;
        $ends = $this->sale_ends_at;

        if (($starts && $now->isBefore($starts)) || ($ends && $now->isAfter($ends))) {
            return (float) $this->price;
        }

        return (float) $this->sale_price;
    }

    public function hasActiveSale(): bool
    {
        return $this->price !== null
            && $this->sale_price !== null
            && $this->current_price < (float) $this->price;
    }

    public function hasDisplayPrice(): bool
    {
        return $this->price !== null
            || $this->price_min !== null
            || $this->price_max !== null
            || ($this->price === null && $this->sale_price !== null);
    }

    public function formatAmount(int|float|null $amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        $currency = $this->currency ?: 'VND';
        $decimals = in_array($currency, ['VND', 'JPY'], true) ? 0 : 2;
        $decimalSeparator = $decimals > 0 ? '.' : ',';
        $thousandsSeparator = $decimals > 0 ? ',' : '.';
        $formatted = number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);

        return match ($currency) {
            'USD' => '$'.$formatted,
            'EUR' => '€'.$formatted,
            'GBP' => '£'.$formatted,
            'JPY' => '¥'.$formatted,
            'VND' => 'VND '.$formatted,
            default => $currency.' '.$formatted,
        };
    }

    public function priceLabel(): ?string
    {
        if ($this->price_min !== null || $this->price_max !== null) {
            $minimum = $this->formatAmount($this->price_min);
            $maximum = $this->formatAmount($this->price_max);

            if ($minimum && $maximum && $this->price_min !== $this->price_max) {
                return $minimum.' - '.$maximum;
            }

            return $minimum ?: $maximum;
        }

        return $this->formatAmount($this->current_price);
    }

    public function staticBlocks(): MorphToMany
    {
        return $this->morphToMany(StaticBlock::class, 'static_blockable')
            ->withPivot('sort_order')
            ->orderByPivot('id');
    }
}
