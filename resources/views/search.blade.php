@extends('layouts.base')

@section('body')
<div class="container py-5">
@include('layouts.flash-messages')

    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold" style="color: #FF6B9E;">Plushie <span style="color: #8A6FDF;">Search Results</span></h1>
        <div class="d-flex justify-content-center">
            <div style="width: 150px; height: 3px; background: linear-gradient(to right, #FF6B9E, #8A6FDF);"></div>
        </div>
    </div>

    @if ($searchResults->isEmpty())
    <div class="text-center p-5 rounded-3 shadow-sm" style="background-color: #FFF5F8;">
        <img src="https://via.placeholder.com/150?text=No+Plushies" class="mb-3 rounded-circle" alt="No results">
        <p class="fs-5" style="color: #8A6FDF;">Oops! We couldn't find any plushies matching your search.</p>
        <p style="color: #FF6B9E;">Try different keywords or browse our collections!</p>
    </div>
    @else
    <div class="d-flex align-items-center mb-4">
        <div class="p-2 rounded-circle me-3" style="background-color: #FFF5F8;">
            <i class="fas fa-search" style="color: #FF6B9E;"></i>
        </div>
        <p class="mb-0 fs-5">Found <strong style="color: #8A6FDF;">{{ $searchResults->count() }}</strong> adorable plushies for you!</p>
    </div>

    @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
    <div class="mb-5">
        <h4 class="mb-4 ps-2 border-start border-4" style="border-color: #8A6FDF !important; color: #FF6B9E;">
            {{ ucfirst($type) }} Plushies
        </h4>
        <div class="row g-4">
            @foreach ($modelSearchResults as $searchResult)
            @php
            $item = $searchResult->searchable;
            $imagePath = optional($item->firstImage)->image_path;
            @endphp
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.3s ease;">
                    <div class="position-relative">
                        <img src="{{ $imagePath ? Storage::url($imagePath) : 'https://via.placeholder.com/300x200?text=Cute+Plushie' }}"
                            class="card-img-top"
                            style="height: 220px; object-fit: cover;"
                            alt="{{ $item->item_name }}">
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge rounded-pill" style="background-color: #FFF5F8; color: #FF6B9E;">
                                <i class="fas fa-heart me-1"></i> New
                            </span>
                        </div>
                    </div>

                    <div class="card-body" style="background-color: #FFF9FB;">
                        <h5 class="card-title fw-bold" style="color: #8A6FDF;">{{ $item->item_name }}</h5>
                        <p class="card-text" style="color: #888;">{{ Str::limit($item->description, 80) }}</p>
                        <p class="fw-bold fs-5" style="color: #FF6B9E;">₱{{ number_format($item->sell_price, 2) }}</p>
                    </div>

                    <div class="card-footer d-flex justify-content-between gap-2 border-0" style="background-color: #FFF9FB;">
                        <a href="{{ route('addToCart', $item->id) }}" class="btn btn-sm rounded-pill w-100 py-2" 
                           style="background-color: #FF6B9E; color: white;">
                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                        </a>
                        <a href="{{ route('item.show', $item->id) }}" class="btn btn-sm btn-outline w-100 py-2 rounded-pill"
                           style="color: #8A6FDF; border-color: #8A6FDF;">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(138, 111, 223, 0.15) !important;
    }
    
    .btn:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }
</style>
@endsection