<?php

use App\Models\HomeSlide;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('home', [
        'settings' => SiteSetting::first(),
        'slides' => HomeSlide::query()->where('active', true)->orderBy('sort_order')->get(),
        'products' => Product::query()->where('status', 'published')->where('featured', true)->latest()->take(8)->get(),
        'posts' => Post::query()
            ->where('status', 'published')
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->latest('published_at')
            ->take(3)
            ->get(),
    ]);
});

Route::get('/products', function () {
    return view('products.index', [
        'settings' => SiteSetting::first(),
        'products' => Product::query()->where('status', 'published')->latest()->paginate(12),
    ]);
})->name('products.index');

Route::get('/products/{product:slug}', function (Product $product) {
    abort_unless($product->status === 'published', 404);

    $product->load('staticBlocks');

    $relatedProducts = Product::query()
        ->where('status', 'published')
        ->where('category', $product->category)
        ->whereKeyNot($product->getKey())
        ->latest()
        ->take(3)
        ->get();

    return view('products.show', [
        'settings' => SiteSetting::first(),
        'product' => $product,
        'relatedProducts' => $relatedProducts,
    ]);
})->name('products.show');

Route::get('/api/products', function () {
    $query = Product::query()->where('status', 'published');

    if (request()->boolean('featured')) {
        $query->where('featured', true);
    }

    $products = $query
        ->latest()
        ->paginate(min(max((int) request('per_page', 24), 1), 48));

    $products->getCollection()->transform(function (Product $product): array {
        return array_merge($product->toArray(), [
            'image_url' => $product->image_path
                ? Storage::disk('public')->url($product->image_path)
                : null,
            'price_label' => $product->hasDisplayPrice()
                ? $product->priceLabel()
                : 'Contact us',
        ]);
    });

    return $products;
});

Route::get('/journal', function () {
    return view('posts.index', [
        'settings' => SiteSetting::first(),
        'posts' => Post::query()
            ->where('status', 'published')
            ->where(fn ($query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->latest('published_at')
            ->paginate(9),
    ]);
});

Route::get('/journal/{post:slug}', function (Post $post) {
    abort_unless($post->status === 'published' && (! $post->published_at || $post->published_at->isPast()), 404);
    $post->load('staticBlocks');

    return view('posts.show', [
        'settings' => SiteSetting::first(),
        'post' => $post,
    ]);
});

Route::get('/{page:slug}', function (Page $page) {
    abort_unless($page->published, 404);
    $page->load('staticBlocks');

    return view('page', [
        'settings' => SiteSetting::first(),
        'page' => $page,
    ]);
});
