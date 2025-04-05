@extends('layouts.base')

@section('body')
<div class="container py-5">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-pill border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-5">
        <!-- Item Images -->
        <div class="col-lg-6">
            @if ($images->count())
            <div class="position-relative">
                <div id="itemCarousel" class="carousel slide shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($images as $index => $img)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div style="background-color: #FFF5F8; height: 450px; display: flex; align-items: center; justify-content: center;">
                                <img src="{{ Storage::url($img->image_path) }}"
                                    class="d-block img-fluid"
                                    style="max-height: 400px; max-width: 90%; object-fit: contain;"
                                    alt="{{ $item->item_name }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" style="background-color: rgba(255, 107, 158, 0.3); border-radius: 50%; padding: 15px;"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" style="background-color: rgba(255, 107, 158, 0.3); border-radius: 50%; padding: 15px;"></span>
                    </button>
                    @endif
                </div>
            </div>
            @else
            <div class="rounded-4 shadow-sm overflow-hidden" style="background-color: #FFF5F8; height: 450px; display: flex; align-items: center; justify-content: center;">
                <img src="https://via.placeholder.com/400x400?text=Cute+Plushie"
                    class="img-fluid"
                    style="max-height: 400px; max-width: 90%; object-fit: contain;"
                    alt="No image">
            </div>
            @endif
        </div>

        <!-- Item Details -->
        <div class="col-lg-6">
            <div class="p-4 rounded-4 shadow-sm h-100" style="background-color: #FFF9FB;">
                <span class="badge rounded-pill mb-3" style="background-color: #8A6FDF; color: white;">
                    <i class="fas fa-tag me-1"></i> {{ $item->category ?? 'Plushie' }}
                </span>

                <h1 class="fw-bold mb-3" style="color: #FF6B9E;">{{ $item->item_name }}</h1>

                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color: {{ $i <= $averageRating ? '#FFD700' : '#E0E0E0' }};"></i>
                                @endfor
                        </div>
                        <span class="text-muted">({{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }})</span>
                    </div>
                </div>

                <div class="mb-4 p-3 rounded-3" style="background-color: white;">
                    <p class="mb-0" style="line-height: 1.7; color: #555;">{{ $item->description }}</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fs-1 fw-bold mb-0" style="color: #8A6FDF;">₱{{ number_format($item->sell_price, 2) }}</h2>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4">
    <a href="{{ route('addToCart', $item->id) }}" class="btn btn-lg rounded-pill w-100 py-3"
        style="background-color: #FF6B9E; color: white; transition: all 0.3s; text-align: center;">
        <i class="fas fa-cart-plus me-2"></i> Add to Cart
    </a>
</div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews Section -->
    <div class="mt-5 pt-5">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 me-3" style="color: #8A6FDF;">Customer Reviews</h3>
            <div style="height: 2px; flex-grow: 1; background: linear-gradient(to right, #8A6FDF, #FFF5F8);"></div>
        </div>

        @if($reviews->count())
        <div class="row g-4">
            @foreach ($reviews as $review)
            @if (is_null($review->deleted_at))
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4" style="background-color: #FFF9FB;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                            <div class="me-3" style="width: 50px; height: 50px; border-radius: 50%; background-color: #FF6B9E; 
                         display: flex; align-items: center; justify-content: center; color: white; overflow: hidden;">
    @if ($review->profile_image)
        <img src="{{ Storage::url($review->profile_image) }}" 
             alt="Profile Image" 
             style="width: 100%; height: 100%; object-fit: cover;">
    @else
        {{ strtoupper(substr($review->customer_name ?? 'A', 0, 1)) }}
    @endif
</div>
                                <div>
                                    <h6 class="fw-bold mb-0" style="color: #8A6FDF;">
                                        {{ explode(' ', $review->customer_name ?? 'Anonymous')[0] }} {{ str_repeat('*', strlen($review->customer_name ?? '') - strlen(explode(' ', $review->customer_name ?? 'Anonymous')[0])) }}
                                    </h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('F j, Y') }}</small>
                                </div>
                            </div>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '' }}"
                                    style="color: {{ $i <= $review->rating ? '#FFD700' : '#E0E0E0' }};"></i>
                                    @endfor
                            </div>
                        </div>

                        <p class="mt-3 mb-4" style="color: #555;">{{ $review->review_text }}</p>

                        @if($reviewMedia->has($review->id))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($reviewMedia[$review->id] as $media)
                            <img src="{{ Storage::url($media->image_path) }}"
                                class="rounded-3 shadow-sm"
                                style="width: 100px; height: 100px; object-fit: cover;"
                                alt="Review Image">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="text-center p-5 rounded-4 shadow-sm" style="background-color: #FFF5F8;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background-color: white; 
                         display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                <i class="fas fa-comment-alt fa-2x" style="color: #FF6B9E;"></i>
            </div>
            <h5 class="mt-3 fw-bold" style="color: #8A6FDF;">No Reviews Yet</h5>
        </div>
        @endif
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(138, 111, 223, 0.15) !important;
    }

    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255, 107, 158, 0.2);
    }

    .carousel-item {
        transition: transform 0.6s ease-in-out;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 20px;
            padding-right: 20px;
        }
    }
</style>
@endsection