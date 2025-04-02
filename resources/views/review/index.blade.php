@extends('layouts.base')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">
                            <i class="bi bi-heart-fill me-2"></i>Your Plushie Reviews
                        </h2>
                        <p class="text-muted">Share your cuddles and happiness with others!</p>
                    </div>

                    @if($reviews->isEmpty())
                        <div class="empty-reviews text-center py-5">
                            <div class="empty-state mb-4">
                                <i class="bi bi-chat-heart text-primary" style="font-size: 4rem; opacity: 0.5;"></i>
                            </div>
                            <h5 class="text-muted mb-3">You haven't hugged and reviewed any plushies yet!</h5>
                            <p class="text-muted mb-4">After receiving your orders, come back here to share your experience.</p>
                            <a href="{{ route('orders.my') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-box2-heart me-2"></i>Go to My Orders
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($reviews as $review)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 review-card">
                                        <div class="card-header border-0 bg-primary-subtle text-primary-emphasis rounded-top-4 py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-gift-fill me-2"></i>
                                                    <span class="badge rounded-pill px-3 py-2 bg-white text-primary shadow-sm">
                                                        Order #{{ $review->orderinfo_id }}
                                                    </span>
                                                </div>
                                                <div class="review-date">
                                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body p-4">
                                            <div class="mb-3">
                                                <h5 class="card-title fw-bold text-primary mb-2 d-flex align-items-center">
                                                    <i class="bi bi-stars me-2"></i>{{ $review->item->item_name }}
                                                </h5>
                                                
                                                {{-- Star Rating --}}
                                                <div class="mb-3 d-flex align-items-center">
                                                    <div class="stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star-fill fs-5 {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <span class="ms-2 text-muted">{{ $review->rating }}/5</span>
                                                </div>
                                            </div>

                                            {{-- Review Text --}}
                                            <div class="review-text mb-4 p-3 bg-light rounded-4">
                                                <i class="bi bi-quote fs-4 text-primary opacity-50"></i>
                                                <p class="card-text {{ empty($review->review_text) ? 'text-muted fst-italic' : '' }}">
                                                    {{ $review->review_text ?? 'No review text provided.' }}
                                                </p>
                                            </div>

                                            {{-- Media Carousel --}}
                                            @if($review->images->count())
                                                <div id="carouselReview{{ $review->review_id }}" class="carousel slide mb-4" data-bs-ride="carousel">
                                                    <div class="carousel-inner rounded-4 shadow-sm">
                                                        @foreach($review->images as $index => $media)
                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                @if(Str::endsWith($media->image_path, ['.mp4', '.webm', '.mov']))
                                                                    <video controls class="d-block w-100 rounded-4" style="height: 220px; object-fit: cover;">
                                                                        <source src="{{ asset('storage/' . str_replace('public/', '', $media->image_path)) }}">
                                                                    </video>
                                                                @else
                                                                    <img src="{{ asset('storage/' . str_replace('public/', '', $media->image_path)) }}"
                                                                        class="d-block w-100 rounded-4" style="height: 220px; object-fit: cover;" alt="Review Media">
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    @if($review->images->count() > 1)
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselReview{{ $review->review_id }}" data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon"></span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselReview{{ $review->review_id }}" data-bs-slide="next">
                                                            <span class="carousel-control-next-icon"></span>
                                                        </button>
                                                        
                                                        <div class="carousel-indicators position-relative mt-2">
                                                            @foreach($review->images as $index => $media)
                                                                <button type="button" data-bs-target="#carouselReview{{ $review->review_id }}" 
                                                                    data-bs-slide-to="{{ $index }}" 
                                                                    class="{{ $index === 0 ? 'active' : '' }}"
                                                                    style="width: 10px; height: 10px; border-radius: 50%; background-color: #ff6b6b;">
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                @if($review->trashed())
                                                    <form action="{{ route('reviews.restore', $review->review_id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning rounded-pill btn-sm px-3">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('reviews.edit', $review->review_id) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">
                                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                                    </a>

                                                    <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm px-3">
                                                            <i class="bi bi-trash3-fill me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for plushie store theme */
    body {
        background-color: #f8f9fa;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .text-primary {
        color: #ff6b6b !important;
    }
    
    .bg-primary {
        background-color: #ff6b6b !important;
    }
    
    .btn-primary {
        background-color: #ff6b6b !important;
        border-color: #ff6b6b !important;
    }
    
    .btn-outline-primary {
        color: #ff6b6b !important;
        border-color: #ff6b6b !important;
    }
    
    .btn-outline-primary:hover {
        background-color: #ff6b6b !important;
        color: white !important;
    }
    
    .bg-primary-subtle {
        background-color: #fff0f0 !important;
    }
    
    .text-primary-emphasis {
        color: #e63946 !important;
    }
    
    .btn-outline-warning {
        color: #ff9800 !important;
        border-color: #ff9800 !important;
    }
    
    .btn-outline-warning:hover {
        background-color: #ff9800 !important;
        color: white !important;
    }
    
    .btn-outline-danger {
        color: #ff5252 !important;
        border-color: #ff5252 !important;
    }
    
    .btn-outline-danger:hover {
        background-color: #ff5252 !important;
        color: white !important;
    }
    
    .stars .bi-star-fill {
        margin-right: 2px;
    }
    
    .review-card {
        transition: all 0.3s ease;
    }
    
    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .carousel .carousel-inner {
        border: 3px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    
    .review-text {
        position: relative;
        line-height: 1.6;
    }
    
    .review-text .bi-quote {
        position: absolute;
        top: -5px;
        left: 8px;
    }
    
    .review-text p {
        position: relative;
        z-index: 1;
        padding-left: 10px;
        margin-bottom: 0;
    }
    
    .empty-state {
        opacity: 0.7;
    }
    
    .carousel-indicators {
        margin-bottom: 0;
    }
</style>
@endsection