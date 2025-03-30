@extends('layouts.base')

@section('body')
<div class="container mt-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <h1 class="mb-4">Search Results</h1>

    @if ($searchResults->isEmpty())
    <p class="text-muted">No results found.</p>
    @else
    <p class="mb-4">There are <strong>{{ $searchResults->count() }}</strong> result(s).</p>

    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
    <div class="row">
        @foreach ($modelSearchResults as $searchResult)
        @php
        $item = $searchResult->searchable;
        @endphp
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm text-center">
                @php
                $imagePath = optional($item->firstImage)->image_path;
                @endphp

                <img src="{{ $imagePath ? Storage::url($imagePath) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                    class="card-img-top p-3"
                    style="height: 300px; object-fit: contain;"
                    alt="{{ $item->item_name }}">

                <div class="card-body">
                    <h5 class="card-title">{{ $item->item_name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($item->description, 80) }}</p>
                    <p class="text-primary fw-bold">₱{{ number_format($item->sell_price, 2) }}</p>
                </div>

                <div class="card-footer bg-white border-top-0 d-flex justify-content-center gap-2 pb-3">
                    <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-primary">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </a>
                    <a href="{{ route('item.show', $item->item_id) }}" class="btn btn-outline-dark">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
    @endif
</div>
@endsection