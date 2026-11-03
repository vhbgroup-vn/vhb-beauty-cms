<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        SiteSetting::updateOrCreate(['id' => 1], [
            'brand_name' => 'VHB Beauty',
            'support_email' => 'hello@vhbbeauty.com',
            'support_phone' => '+84 900 000 000',
            'announcement' => 'Beauty, made personal.',
            'social_links' => [
                ['label' => 'Instagram', 'url' => 'https://instagram.com/'],
                ['label' => 'Facebook', 'url' => 'https://facebook.com/'],
            ],
        ]);

        HomeSlide::updateOrCreate(['sort_order' => 1], [
            'headline' => 'Your everyday glow, considered.',
            'subheadline' => 'A thoughtful edit of beauty essentials for skin, hair and self-care.',
            'image_path' => 'hero/vhb-hero.png',
            'cta_label' => 'Shop the edit',
            'cta_url' => '/products',
            'active' => true,
        ]);

        HomeSlide::updateOrCreate(['sort_order' => 2], [
            'headline' => 'Small rituals. Real confidence.',
            'subheadline' => 'Build a routine that feels as good as it looks.',
            'image_path' => null,
            'cta_label' => 'Discover VHB',
            'cta_url' => '/about',
            'active' => true,
        ]);

        $products = [
            [
                'name' => 'Radiance Vitamin C Serum', 'slug' => 'radiance-vitamin-c-serum', 'sku' => 'VHB-SERUM-001',
                'brand' => 'VHB Beauty', 'category' => 'skincare', 'product_type' => 'simple', 'price' => 489000,
                'sale_price' => 429000, 'stock_status' => 'in_stock', 'stock_quantity' => 42,
                'short_description' => 'A brightening daily serum for a fresh, even-looking glow.',
                'benefits' => ['Brightens the look of dull skin', 'Lightweight daily texture'],
                'tags' => ['vitamin c', 'brightening', 'serum'], 'featured' => true,
                'attributes' => [
                    ['name' => 'Skin type', 'values' => ['Normal', 'Dry', 'Combination'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Key ingredients', 'values' => ['Vitamin C', 'Ferulic acid'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Volume', 'values' => ['30 ml'], 'visible' => true, 'used_for_variations' => false],
                ],
            ],
            [
                'name' => 'Silk Repair Hair Oil', 'slug' => 'silk-repair-hair-oil', 'sku' => 'VHB-HAIR-001',
                'brand' => 'VHB Beauty', 'category' => 'haircare', 'product_type' => 'simple', 'price' => 329000,
                'stock_status' => 'in_stock', 'stock_quantity' => 28,
                'short_description' => 'A soft finishing oil for smoother, shinier-looking hair.',
                'benefits' => ['Smooths dry ends', 'Adds lightweight shine'],
                'tags' => ['hair oil', 'shine', 'repair'], 'featured' => true,
                'attributes' => [
                    ['name' => 'Hair type', 'values' => ['Dry', 'Damaged', 'Frizzy'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Finish', 'values' => ['Soft shine', 'Non-greasy'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Volume', 'values' => ['50 ml'], 'visible' => true, 'used_for_variations' => false],
                ],
            ],
            [
                'name' => 'Cloud Cream Cleanser', 'slug' => 'cloud-cream-cleanser', 'sku' => 'VHB-CLEAN-001',
                'brand' => 'VHB Beauty', 'category' => 'skincare', 'product_type' => 'simple', 'price' => 269000,
                'stock_status' => 'in_stock', 'stock_quantity' => 64,
                'short_description' => 'A gentle cream cleanser made for a calm, comfortable cleanse.',
                'benefits' => ['Comfortable daily cleanse', 'Suitable for sensitive-feeling skin'],
                'tags' => ['cleanser', 'gentle', 'sensitive skin'], 'featured' => true,
                'attributes' => [
                    ['name' => 'Skin type', 'values' => ['Sensitive', 'Dry', 'Normal'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Texture', 'values' => ['Cream'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Volume', 'values' => ['100 ml'], 'visible' => true, 'used_for_variations' => false],
                ],
            ],
            [
                'name' => 'Soft Focus Lip Tint', 'slug' => 'soft-focus-lip-tint', 'sku' => 'VHB-LIP-001',
                'brand' => 'VHB Beauty', 'category' => 'makeup', 'product_type' => 'variable', 'price' => 219000,
                'stock_status' => 'in_stock', 'stock_quantity' => 75,
                'short_description' => 'A buildable tint with a soft, lived-in finish.',
                'benefits' => ['Buildable colour', 'Comfortable finish'],
                'tags' => ['lip tint', 'makeup', 'everyday'], 'featured' => false,
                'attributes' => [
                    ['name' => 'Shade', 'values' => ['Rose', 'Nude', 'Berry'], 'visible' => true, 'used_for_variations' => true],
                    ['name' => 'Finish', 'values' => ['Soft matte'], 'visible' => true, 'used_for_variations' => false],
                    ['name' => 'Volume', 'values' => ['4.5 g'], 'visible' => true, 'used_for_variations' => false],
                ],
                'variations' => [
                    ['sku' => 'VHB-LIP-001-ROSE', 'attributes' => ['Shade' => 'Rose'], 'price' => 219000, 'stock_status' => 'in_stock', 'stock_quantity' => 30],
                    ['sku' => 'VHB-LIP-001-NUDE', 'attributes' => ['Shade' => 'Nude'], 'price' => 219000, 'stock_status' => 'in_stock', 'stock_quantity' => 25],
                    ['sku' => 'VHB-LIP-001-BERRY', 'attributes' => ['Shade' => 'Berry'], 'price' => 229000, 'stock_status' => 'on_backorder', 'stock_quantity' => 20],
                ],
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product + ['status' => 'published']);
        }

        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About Us',
            'page_type' => 'about',
            'template' => 'standard',
            'published' => true,
            'excerpt' => 'A considered collection of everyday beauty essentials for rituals that feel simple, effective and entirely your own.',
            'content' => '<h2>Beauty, made personal.</h2><p>VHB Beauty is a considered collection of everyday essentials for rituals that feel simple, effective and entirely your own.</p><p>We believe good beauty should meet you where you are: easy to understand, lovely to use and chosen with care.</p>',
            'blocks' => [
                ['type' => 'text', 'heading' => 'Chosen with care', 'body' => '<p>Every formula, texture and shade is selected to make everyday rituals feel a little more personal.</p>'],
            ],
            'seo_title' => 'About VHB Beauty',
            'seo_description' => 'Discover the VHB Beauty approach to thoughtful, everyday beauty.',
        ]);

        Page::updateOrCreate(['slug' => 'contact'], [
            'title' => 'Contact',
            'page_type' => 'contact',
            'template' => 'contact',
            'published' => true,
            'excerpt' => 'Ask us about a product, your routine or an order. Our team will get back to you soon.',
            'content' => '<h2>We would love to hear from you.</h2><p>Ask us about a product, your routine or an order. Our team will get back to you soon.</p>',
            'contact_email' => 'hello@vhbbeauty.com',
            'contact_phone' => '+84 900 000 000',
            'contact_address' => 'Ho Chi Minh City, Vietnam',
        ]);

        Post::updateOrCreate(['slug' => 'simple-skincare-layering'], [
            'title' => 'A simple way to layer your skincare',
            'status' => 'published',
            'category' => 'routine',
            'excerpt' => 'A calm, easy-to-follow order for making the most of your everyday routine.',
            'content' => '<h2>Keep it simple</h2><p>Start with a gentle cleanse, follow with a targeted serum and finish with moisturiser and SPF in the morning.</p><p>The best routine is the one you can return to consistently.</p>',
            'author_name' => 'VHB Beauty',
            'tags' => ['skincare', 'routine', 'how-to'],
            'featured' => true,
            'published_at' => now(),
        ]);

        Post::updateOrCreate(['slug' => 'understanding-vitamin-c'], [
            'title' => 'Why vitamin C belongs in your morning ritual',
            'status' => 'published',
            'category' => 'ingredients',
            'excerpt' => 'A friendly introduction to one of the most loved brightening ingredients.',
            'content' => '<h2>Bright, balanced and considered</h2><p>Vitamin C is a popular choice for routines focused on a more even-looking, fresh glow. Choose a texture you enjoy and introduce it gradually.</p>',
            'author_name' => 'VHB Beauty',
            'tags' => ['vitamin c', 'ingredients', 'brightening'],
            'featured' => false,
            'published_at' => now()->subDay(),
        ]);
    }
}
