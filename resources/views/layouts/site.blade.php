<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description ?? 'VHB Beauty curates thoughtful beauty products, trusted brands and everyday rituals for a more personal kind of beauty.' }}">
    <meta name="theme-color" content="#7042FC">
    <title>{{ $title ?? ($settings?->brand_name ?? 'VHB Beauty') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/vhb-logo.png') }}">
    <link rel="preload" as="image" href="{{ asset('images/vhb-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/vhb-product-template-45.css') }}?v=20261028-45">
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <div class="announcement" role="note">
        <span class="announcement-label">VHB BEAUTY</span>
        <span>{{ $settings?->announcement ?? 'Beauty, made personal.' }}</span>
        <a href="{{ url('/products') }}">Explore the collection <span aria-hidden="true">↗</span></a>
    </div>

    <header class="site-header" data-site-header>
        <div class="container site-header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="VHB Beauty home">
                <img src="{{ $settings?->logo_path ? Storage::disk('public')->url($settings->logo_path) : asset('images/vhb-logo.png') }}" alt="VHB Beauty">
            </a>
            <button class="menu-toggle" type="button" aria-label="Open menu" aria-expanded="false" data-menu-toggle>
                <span></span><span></span>
            </button>
            <nav class="nav-links" aria-label="Primary navigation" data-site-menu>
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ url('/about') }}">About Us</a>
                <a href="{{ url('/products') }}">Products</a>
                <a href="{{ url('/journal') }}">Journal</a>
                <a href="{{ url('/contact') }}">Contact</a>
            </nav>
            <a class="nav-cta" href="{{ url('/products') }}">Shop the edit <span aria-hidden="true">↗</span></a>
        </div>
    </header>

    <main id="main-content">@yield('content')</main>

    <footer class="site-footer">
        <div class="container footer-top">
            <div class="footer-brand-block">
                <a class="brand" href="{{ url('/') }}" aria-label="VHB Beauty home">
                    <img src="{{ $settings?->logo_path ? Storage::disk('public')->url($settings->logo_path) : asset('images/vhb-logo.png') }}" alt="VHB Beauty">
                </a>
                <p>Thoughtful choices for your skin and the rituals that help you feel more like yourself.</p>
            </div>
            <div class="footer-links">
                <div>
                    <p class="footer-label">Explore</p>
                    <a href="{{ url('/about') }}">About Us</a>
                    <a href="{{ url('/products') }}">Products</a>
                    <a href="{{ url('/journal') }}">Journal</a>
                </div>
                <div>
                    <p class="footer-label">Connect</p>
                    <a href="{{ url('/contact') }}">Contact</a>
                    @foreach (($settings?->social_links ?? []) as $social)
                        @if (!empty($social['url']))<a href="{{ $social['url'] }}" target="_blank" rel="noreferrer">{{ $social['label'] ?? 'Social' }}</a>@endif
                    @endforeach
                </div>
            </div>
            <div class="footer-contact">
                <p class="footer-label">Need a little help?</p>
                <a href="mailto:{{ $settings?->support_email ?? 'hello@vhbbeauty.com' }}">{{ $settings?->support_email ?? 'hello@vhbbeauty.com' }}</a>
                <a href="tel:{{ $settings?->support_phone ?? '+84900000000' }}">{{ $settings?->support_phone ?? '+84 900 000 000' }}</a>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© {{ now()->year }} VHB Beauty</span>
            <span>Beauty, made personal.</span>
        </div>
    </footer>
    <script src="{{ asset('js/vhb.js') }}?v=20261028-product-template" defer></script>
    @stack('scripts')
</body>
</html>
