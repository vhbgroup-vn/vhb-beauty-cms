@extends('layouts.site', ['title' => 'Products - VHB Beauty', 'description' => 'Explore thoughtful skincare, haircare and beauty products curated by VHB Beauty.'])

@section('content')
    <section class="page-hero"><p class="eyebrow">The VHB edit</p><h1>Beauty essentials<br><em>for your ritual.</em></h1><p>Thoughtful products chosen to make your everyday ritual feel lighter.</p></section>
    <section class="section"><div class="product-grid">
        @forelse ($products as $product)
            @include('products._card', ['product' => $product, 'cardIndex' => $loop->iteration])
        @empty <p class="empty-state">No products have been published yet. Add products in the CMS to display them here.</p> @endforelse
    </div>{{ $products->links() }}</section>
@endsection
