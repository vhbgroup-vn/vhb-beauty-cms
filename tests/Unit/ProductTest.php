<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_it_formats_a_decimal_usd_price_range(): void
    {
        $product = Product::make([
            'currency' => 'USD',
            'price_min' => 10.9,
            'price_max' => 11.2,
        ]);

        $this->assertSame('$10.90 - $11.20', $product->priceLabel());
    }

    public function test_it_detects_an_active_sale_for_decimal_prices(): void
    {
        $product = Product::make([
            'currency' => 'USD',
            'price' => 10.9,
            'sale_price' => 8.9,
        ]);

        $this->assertTrue($product->hasActiveSale());
    }
}
