@if ($staticBlock->published)
    <section class="section static-block">
        @if ($staticBlock->title)<p class="eyebrow">{{ $staticBlock->title }}</p>@endif
        @if (filled($staticBlock->html_content))
            <div class="static-block-content">{!! $staticBlock->html_content !!}</div>
        @else
            <div class="static-block-content">{!! $staticBlock->content !!}</div>
        @endif
    </section>
@endif
