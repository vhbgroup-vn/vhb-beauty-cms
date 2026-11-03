@extends('layouts.site', ['title' => ($post->seo_title ?: $post->title) . ' - VHB Beauty', 'description' => $post->excerpt])

@section('content')
    <section class="page-hero"><p class="eyebrow">{{ str($post->category ?? 'journal')->replace('-', ' ')->title() }}</p><h1>{{ $post->title }}</h1><p>{{ $post->excerpt }}</p><small>{{ $post->published_at?->format('d M Y') }} @if ($post->author_name) / {{ $post->author_name }} @endif</small></section>
    <section class="section rich-copy post-content">@if ($post->cover_image_path)<img class="post-cover" src="{{ Storage::disk('public')->url($post->cover_image_path) }}" alt="{{ $post->title }}">@endif{!! $post->content !!}</section>
    @foreach ($post->staticBlocks as $staticBlock)
        @include('partials.static-block', ['staticBlock' => $staticBlock])
    @endforeach
@endsection
