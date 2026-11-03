@php
    $categoryLabel = str($product->category ?: 'beauty')->replace('-', ' ')->title();
    $galleryPaths = collect([$product->image_path])
        ->merge($product->gallery ?? [])
        ->filter()
        ->unique()
        ->values();
    $imageUrls = $galleryPaths
        ->map(fn (string $path): string => Storage::disk('public')->url($path))
        ->values();
    $visibleAttributes = collect($product->attributes ?? [])
        ->filter(fn (mixed $attribute): bool => is_array($attribute) && ($attribute['visible'] ?? true) && ! empty($attribute['values']))
        ->values();
    $variations = collect($product->variations ?? [])
        ->filter(fn (mixed $variation): bool => is_array($variation) && ! empty($variation['attributes']))
        ->values();
    $variationAttributeNames = $variations
        ->flatMap(fn (array $variation): array => array_keys($variation['attributes'] ?? []))
        ->filter()
        ->unique()
        ->values();
    $variationPayload = $variations->map(function (array $variation) use ($product): array {
        $variationPrice = $variation['sale_price'] ?? $variation['price'] ?? null;

        return [
            'attributes' => collect($variation['attributes'] ?? [])->map(fn (mixed $value): string => (string) $value)->all(),
            'priceLabel' => $variationPrice !== null ? $product->formatAmount((float) $variationPrice) : 'Contact us for pricing',
            'sku' => $variation['sku'] ?? $product->sku ?? 'Not assigned',
            'stockLabel' => str($variation['stock_status'] ?? 'in_stock')->replace('_', ' ')->title()->toString(),
            'stockStatus' => $variation['stock_status'] ?? 'in_stock',
            'imageUrl' => ! empty($variation['image_path']) ? Storage::disk('public')->url($variation['image_path']) : null,
        ];
    })->values();
    $stockLabel = str($product->stock_status ?: 'in_stock')->replace('_', ' ')->title();
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'description' => $product->short_description,
        'sku' => $product->sku,
        'brand' => [
            '@type' => 'Brand',
            'name' => $product->brand ?: 'VHB Beauty',
        ],
        'category' => $categoryLabel,
        'image' => $imageUrls->all(),
        'url' => url('/products/' . $product->slug),
    ];

    if ($product->hasDisplayPrice() && $product->current_price !== null) {
        $structuredData['offers'] = [
            '@type' => 'Offer',
            'priceCurrency' => $product->currency ?: 'VND',
            'price' => $product->current_price,
            'availability' => $product->stock_status === 'out_of_stock'
                ? 'https://schema.org/OutOfStock'
                : 'https://schema.org/InStock',
            'url' => url('/products/' . $product->slug),
        ];
    }
@endphp

@extends('layouts.site', [
    'title' => ($product->seo_title ?: $product->name) . ' - VHB Beauty',
    'description' => $product->seo_description ?: $product->short_description,
])

@push('head')
    <script type="application/ld+json">@json($structuredData)</script>
@endpush

@section('content')
    <nav class="product-breadcrumb container" aria-label="Breadcrumb">
        <a href="{{ url('/products') }}">Products</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">{{ $product->name }}</span>
    </nav>

    <section class="section product-detail product-template">
        <div class="product-media">
            <div class="detail-image" data-product-main-image>
                @if ($imageUrls->isNotEmpty())
                    <img src="{{ $imageUrls->first() }}" alt="{{ $product->name }}" data-main-product-image>
                @else
                    <span aria-hidden="true">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                @endif
            </div>

            @if ($imageUrls->count() > 1)
                <div class="product-gallery" aria-label="{{ $product->name }} gallery">
                    @foreach ($imageUrls as $imageUrl)
                        <button
                            class="product-gallery-item{{ $loop->first ? ' is-active' : '' }}"
                            type="button"
                            data-gallery-thumb
                            data-image-src="{{ $imageUrl }}"
                            aria-label="View image {{ $loop->iteration }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                        >
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }} image {{ $loop->iteration }}" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="detail-copy">
            <p class="eyebrow">{{ $categoryLabel }} @if ($product->brand) / {{ $product->brand }} @endif</p>
            <h1>{{ $product->name }}</h1>
            <div class="detail-price-row">
                <p class="detail-price" data-product-price>
                    {{ $product->hasDisplayPrice() ? $product->priceLabel() : 'Contact us for pricing' }}
                </p>
                @if ($product->hasActiveSale())
                    <del data-product-compare>{{ $product->formatAmount($product->price) }}</del>
                @endif
            </div>
            <p class="detail-lede">{{ $product->short_description }}</p>

            @if ($variations->isNotEmpty())
                <div class="product-option-picker" data-variation-picker>
                    <script type="application/json" data-variation-data>@json($variationPayload)</script>
                    <p class="product-option-heading">Choose your option</p>
                    @foreach ($variationAttributeNames as $attributeName)
                        @php
                            $optionValues = $variations
                                ->map(fn (array $variation): mixed => $variation['attributes'][$attributeName] ?? null)
                                ->filter()
                                ->unique()
                                ->values();
                        @endphp
                        <label class="product-option">
                            <span>{{ $attributeName }}</span>
                            <select data-variation-select data-attribute="{{ $attributeName }}">
                                @foreach ($optionValues as $optionValue)
                                    <option value="{{ $optionValue }}">{{ $optionValue }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endforeach
                    <p class="variation-note" data-variation-note>Select an option to see its availability.</p>
                </div>
            @endif

            <p class="product-meta">
                SKU: <span data-product-sku>{{ $product->sku ?: 'Not assigned' }}</span>
                <span aria-hidden="true">/</span>
                <span data-product-stock>{{ $stockLabel }}</span>
            </p>

            @if ($product->benefits)
                <ul class="benefits">
                    @foreach ($product->benefits as $benefit)
                        <li>{{ $benefit }}</li>
                    @endforeach
                </ul>
            @endif

            <a class="button button-primary product-contact-cta" data-product-contact-cta href="{{ url('/contact') }}">
                <span data-product-cta-label>{{ $product->hasDisplayPrice() ? 'Ask about this product' : 'Contact us for pricing' }}</span>
                <span aria-hidden="true">↗</span>
            </a>
        </div>
    </section>

    @if (filled(strip_tags((string) $product->description)))
        <section class="section rich-copy product-description">
            <p class="eyebrow">About the formula</p>
            {!! $product->description !!}
        </section>
    @endif

    @foreach ($product->staticBlocks as $staticBlock)
        @include('partials.static-block', ['staticBlock' => $staticBlock])
    @endforeach

    @if ($visibleAttributes->isNotEmpty())
        <section class="section product-attributes">
            <p class="eyebrow">Product details</p>
            <h2>Made for your ritual</h2>
            <div class="attribute-grid">
                @foreach ($visibleAttributes as $attribute)
                    <div>
                        <strong>{{ $attribute['name'] }}</strong>
                        <span>{{ implode(', ', (array) $attribute['values']) }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($relatedProducts->isNotEmpty())
        <section class="section related-products">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">You may also like</p>
                    <h2>More for your ritual.</h2>
                </div>
                <a class="text-link" href="{{ url('/products') }}">View all products <span aria-hidden="true">↗</span></a>
            </div>
            <div class="product-grid related-product-grid">
                @foreach ($relatedProducts as $relatedProduct)
                    @include('products._card', ['product' => $relatedProduct, 'cardIndex' => $loop->iteration])
                @endforeach
            </div>
        </section>
    @endif
@endsection
