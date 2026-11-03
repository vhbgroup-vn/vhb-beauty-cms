@extends('layouts.site', ['title' => 'Journal - VHB Beauty', 'description' => 'Skincare ideas, ingredient notes and beauty stories from VHB Beauty.'])

@section('content')
    <section class="page-hero"><p class="eyebrow">The VHB Journal</p><h1>Ideas for<br><em>your ritual.</em></h1><p>Simple guidance, ingredient notes and considered beauty stories.</p></section>
    <section class="section"><div class="post-grid">
        @forelse ($posts as $post)
            <a class="post-card" href="{{ url('/journal/' . $post->slug) }}"><div class="post-image">@if ($post->cover_image_path)<img src="{{ Storage::disk('public')->url($post->cover_image_path) }}" alt="{{ $post->title }}">@else<span>{{ strtoupper(substr($post->title, 0, 1)) }}</span>@endif</div><p class="product-category">{{ str($post->category ?? 'journal')->replace('-', ' ')->title() }}</p><h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p><small>{{ $post->published_at?->format('d M Y') }}</small></a>
        @empty
            <p class="empty-state">No journal posts have been published yet. Add posts in the CMS to display them here.</p>
        @endforelse
    </div>{{ $posts->links() }}</section>
@endsection
