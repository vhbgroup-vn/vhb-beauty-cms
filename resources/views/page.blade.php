@extends('layouts.site', ['title' => ($page->seo_title ?: $page->title) . ' — VHB Beauty'])

@section('content')
    @if (! $page->htmlContentIsFullDocument())
        <section class="page-hero" @if ($page->hero_image_path) style="background-image: linear-gradient(90deg, rgba(248,245,240,.96), rgba(248,245,240,.7)), url('{{ Storage::disk('public')->url($page->hero_image_path) }}'); background-size: cover; background-position: center;" @endif><p class="eyebrow">VHB BEAUTY</p><h1>{{ $page->title }}</h1>@if ($page->excerpt)<p>{{ $page->excerpt }}</p>@endif</section>
    @endif

    <section class="section {{ $page->htmlContentIsFullDocument() ? 'page-html-document-section' : 'rich-copy' }}">
        @if ($page->htmlContentIsFullDocument())
            <iframe
                id="page-html-frame-{{ $page->id }}"
                class="page-html-frame"
                title="{{ $page->title }}"
                srcdoc="{{ $page->html_content }}"
                loading="eager"
            ></iframe>
            <script>
                (() => {
                    const frame = document.getElementById('page-html-frame-{{ $page->id }}');
                    if (!frame) return;

                    const resize = () => {
                        const documentInsideFrame = frame.contentDocument;
                        if (!documentInsideFrame) return;
                        const height = Math.max(
                            documentInsideFrame.documentElement?.scrollHeight || 0,
                            documentInsideFrame.body?.scrollHeight || 0,
                        );
                        if (height) frame.style.height = `${height}px`;
                    };

                    frame.addEventListener('load', () => {
                        resize();
                        const documentInsideFrame = frame.contentDocument;
                        if (!documentInsideFrame) return;

                        documentInsideFrame.querySelectorAll('img, video, iframe').forEach((media) => {
                            media.addEventListener('load', resize);
                        });

                        if (documentInsideFrame.body) {
                            new MutationObserver(resize).observe(documentInsideFrame.body, {
                                childList: true,
                                subtree: true,
                                attributes: true,
                            });
                        }
                    });
                })();
            </script>
        @elseif (filled($page->html_content))
            <div class="page-html-content">{!! $page->html_content !!}</div>
        @else
            {!! $page->content !!}
            @foreach ($page->blocks ?? [] as $block)
                <section class="page-block page-block-{{ $block['type'] ?? 'text' }}">
                    @if (!empty($block['heading']))<h2>{{ $block['heading'] }}</h2>@endif
                    @if (!empty($block['body']))<div>{!! $block['body'] !!}</div>@endif
                    @if (!empty($block['image_path']))<img src="{{ Storage::disk('public')->url($block['image_path']) }}" alt="{{ $block['heading'] ?? $page->title }}">@endif
                    @if (!empty($block['button_label']) && !empty($block['button_url']))<a class="button" href="{{ $block['button_url'] }}">{{ $block['button_label'] }}</a>@endif
                </section>
            @endforeach
        @endif
        @if (! $page->htmlContentIsFullDocument() && $page->page_type === 'contact')<div class="contact-card"><p><strong>Email</strong><br>{{ $page->contact_email ?? $settings?->support_email }}</p><p><strong>Phone</strong><br>{{ $page->contact_phone ?? $settings?->support_phone }}</p><p><strong>Address</strong><br>{{ $page->contact_address }}</p></div>@endif
    </section>
    @foreach ($page->staticBlocks as $staticBlock)
        @include('partials.static-block', ['staticBlock' => $staticBlock])
    @endforeach
@endsection
