@php
    $cardIndex = $cardIndex ?? 0;
    $categoryLabel = str($product->category ?? 'beauty')->replace('-', ' ')->title();
    $imageUrl = $product->image_path ? Storage::disk('public')->url($product->image_path) : null;
@endphp

<article class="product-card">
    <a class="product-card-link" href="{{ route('products.show', ['product' => $product->slug]) }}">
        <div class="product-image product-image-{{ $cardIndex % 4 }}">
            @if ($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
            @else
                <span aria-hidden="true">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
            @endif
            <span class="product-image-label">{{ $categoryLabel }}</span>
        </div>
        <div class="product-card-info">
            <div>
                <p class="product-category">{{ $categoryLabel }}</p>
                <h3>{{ $product->name }}</h3>
            </div>
            <p class="price">{{ $product->hasDisplayPrice() ? $product->priceLabel() : 'Contact us' }}</p>
        </div>
    </a>
</article>
