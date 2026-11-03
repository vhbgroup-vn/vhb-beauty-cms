@extends('layouts.site', ['title' => ($settings?->brand_name ?? 'VHB Beauty') . ' - Beauty, made personal'])

@section('content')
    <section class="hero" data-carousel aria-label="VHB Beauty highlights">
        <div class="hero-track">
            @forelse ($slides as $slide)
                <article class="hero-slide {{ $loop->first ? 'is-active' : '' }}" data-slide>
                    <div class="container hero-slide-inner">
                        <div class="hero-copy">
                            <p class="eyebrow">VHB BEAUTY / {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h1>{{ $slide->headline }}</h1>
                            <p class="hero-lede">{{ $slide->subheadline }}</p>
                            @if ($slide->cta_label)
                                <a class="button button-primary" href="{{ $slide->cta_url ?: url('/products') }}">{{ $slide->cta_label }} <span aria-hidden="true">↗</span></a>
                            @endif
                        </div>
                        <div class="hero-art {{ $slide->image_path ? 'has-image' : 'hero-art-gradient' }}">
                            @if ($slide->image_path)
                                <img src="{{ Storage::disk('public')->url($slide->image_path) }}" alt="VHB Beauty curated beauty products" fetchpriority="{{ $loop->first ? 'high' : 'auto' }}">
                            @else
                                <span class="hero-art-word">RITUAL<br><em>starts here.</em></span>
                            @endif
                            <span class="hero-pattern hero-pattern-one" aria-hidden="true"></span>
                            <span class="hero-pattern hero-pattern-two" aria-hidden="true"></span>
                        </div>
                    </div>
                </article>
            @empty
                <article class="hero-slide is-active" data-slide>
                    <div class="container hero-slide-inner">
                        <div class="hero-copy"><p class="eyebrow">VHB BEAUTY / 01</p><h1>Beauty, made personal.</h1><p class="hero-lede">Thoughtful choices for your skin and the rituals that make beauty feel personal.</p><a class="button button-primary" href="{{ url('/products') }}">Explore products <span aria-hidden="true">↗</span></a></div>
                        <div class="hero-art hero-art-gradient"><span class="hero-art-word">RITUAL<br><em>starts here.</em></span></div>
                    </div>
                </article>
            @endforelse
        </div>
        @if ($slides->count() > 1)
            <div class="container hero-controls">
                    <div class="carousel-dots" role="tablist" aria-label="Choose a banner">
                    @foreach ($slides as $slide)<button type="button" data-dot="{{ $loop->index }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Banner {{ $loop->iteration }}"></button>@endforeach
                </div>
                <span class="hero-index"><span data-current-slide>01</span> / {{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}</span>
            </div>
        @endif
    </section>

    <section class="value-strip" aria-labelledby="value-title">
        <div class="container">
            <div class="section-intro section-intro-compact"><p class="eyebrow">Brand DNA</p><h2 id="value-title">Right choices.<br><em>Real possibilities.</em></h2></div>
            <div class="value-grid">
                <div><span class="value-number">01</span><h3>Right Products</h3><p>Products that meet real needs and real routines.</p></div>
                <div><span class="value-number">02</span><h3>Right Brands</h3><p>Brands with quality, character and a story worth trusting.</p></div>
                <div><span class="value-number">03</span><h3>Right Markets</h3><p>Better beauty choices made accessible to more people.</p></div>
                <div><span class="value-number">04</span><h3>Right Solutions</h3><p>From one considered essential to a complete ritual.</p></div>
            </div>
        </div>
    </section>

    <section class="section featured-products" aria-labelledby="featured-title">
        <div class="container">
            <div class="section-heading"><div><p class="eyebrow">Shop the edit</p><h2 id="featured-title">What your skin<br><em>is asking for.</em></h2></div><a class="text-link" href="{{ url('/products') }}">View all products <span aria-hidden="true">↗</span></a></div>
            <div class="product-grid">
                @forelse ($products as $product)
                    @include('products._card', ['product' => $product, 'cardIndex' => $loop->iteration])
                @empty
                    <p class="empty-state">Add featured products in the CMS to display them here.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="story-section">
        <div class="container story-grid">
            <div class="story-art"><span class="story-mark" aria-hidden="true">V</span><span class="story-caption">A brand unit of VHB GROUP</span><span class="story-orbit story-orbit-one" aria-hidden="true"></span><span class="story-orbit story-orbit-two" aria-hidden="true"></span></div>
            <div class="story-copy"><p class="eyebrow">Our point of view</p><h2>Beauty feels better<br><em>when it feels like you.</em></h2><p>VHB Beauty began with a simple belief: self-care does not need to be complicated. We look for thoughtful formulas, comfortable textures and flexible essentials that move with real life.</p><a class="text-link" href="{{ url('/about') }}">Discover VHB <span aria-hidden="true">↗</span></a></div>
        </div>
    </section>

    @if ($posts->isNotEmpty())
        <section class="section journal-preview" aria-labelledby="journal-title">
            <div class="container"><div class="section-heading"><div><p class="eyebrow">From the journal</p><h2 id="journal-title">Ideas for<br><em>your ritual.</em></h2></div><a class="text-link" href="{{ url('/journal') }}">Read the journal <span aria-hidden="true">↗</span></a></div><div class="post-grid">@foreach ($posts as $post)<a class="post-card" href="{{ url('/journal/' . $post->slug) }}"><div class="post-image">@if ($post->cover_image_path)<img src="{{ Storage::disk('public')->url($post->cover_image_path) }}" alt="{{ $post->title }}" loading="lazy">@else<span>{{ strtoupper(substr($post->title, 0, 1)) }}</span>@endif</div><p class="product-category">{{ str($post->category ?? 'journal')->replace('-', ' ')->title() }}</p><h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p></a>@endforeach</div></div>
        </section>
    @endif

    <section class="contact-cta"><div class="container contact-cta-inner"><div><p class="eyebrow">Need a little help?</p><h2>Find what feels<br><em>right for you.</em></h2></div><div><p>Not sure where to begin? Send us a note and VHB will help you find a ritual that feels genuinely personal.</p><a class="button button-light" href="{{ url('/contact') }}">Contact VHB <span aria-hidden="true">↗</span></a></div></div></section>
@endsection
