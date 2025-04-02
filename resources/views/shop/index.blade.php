@extends('layouts.base')

@section('title', 'SquishIT - Plushie Paradise')

@section('body')

<style>
    /* Custom fonts */
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Bubblegum+Sans&display=swap');

    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f8f9fa;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f0d6e5' fill-opacity='0.2'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Header styling */
    .hero-header {
        background: linear-gradient(135deg, #ffcae9 0%, #b3d9ff 100%);
        padding: 3rem 0;
        border-bottom: 5px dashed #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .hero-header::before,
    .hero-header::after {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        background-size: contain;
        background-repeat: no-repeat;
        z-index: 1;
        opacity: 0.6;
    }

    .hero-header::before {
        top: 20px;
        left: 5%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ff80b3' d='M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32z'/%3E%3C/svg%3E");
    }

    .hero-header::after {
        bottom: 20px;
        right: 5%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%233d85c6' d='M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32z'/%3E%3C/svg%3E");
        transform: scaleX(-1);
    }

    .store-title {
        font-family: 'Bubblegum Sans', cursive;
        font-size: 3.5rem;
        color: #ff6b9d;
        text-shadow: 3px 3px 0px rgba(255, 255, 255, 0.7);
        margin-bottom: 0.5rem;
    }

    .store-tagline {
        font-size: 1.4rem;
        color: #4a6fa5;
        font-weight: 600;
    }

    /* Card styling */
    .plushie-card {
        transition: all 0.3s ease;
        border-radius: 20px;
        overflow: hidden;
        border: none;
        background-color: white;
    }

    .plushie-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-img-container {
        background-color: #f8f1f7;
        padding: 1.5rem;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .card-img-container::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 30px;
        background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0) 70%);
    }

    .card-img-top {
        max-height: 200px;
        width: auto;
        max-width: 100%;
        object-fit: contain;
        transition: all 0.5s ease;
    }

    .plushie-card:hover .card-img-top {
        transform: scale(1.1) rotate(5deg);
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-family: 'Bubblegum Sans', cursive;
        font-size: 1.4rem;
        color: #ff6b9d;
    }

    .price-tag {
        background-color: #fff0f5;
        border-radius: 50px;
        padding: 0.5rem 1rem;
        font-weight: bold;
        color: #ff6b9d;
        display: inline-block;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
    }

    .card-footer {
        background-color: white;
        border-top: 2px dotted #f0f0f0;
        padding: 1rem;
    }

    /* Button styling */
    .btn-squish {
        border-radius: 50px;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary.btn-squish {
        background-color: #ff80b3;
        color: white;
        box-shadow: 0 4px 0 #d46a94;
    }

    .btn-primary.btn-squish:hover {
        background-color: #ff6b9d;
        transform: translateY(-2px);
        box-shadow: 0 6px 0 #d46a94;
    }

    .btn-primary.btn-squish:active {
        transform: translateY(2px);
        box-shadow: 0 2px 0 #d46a94;
    }

    .btn-outline.btn-squish {
        background-color: white;
        color: #4a6fa5;
        border: 2px solid #b3d9ff;
    }

    .btn-outline.btn-squish:hover {
        background-color: #f0f8ff;
        color: #3d85c6;
    }

    /* Filter section */
    .filter-section {
        background-color: #fff;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        border: 2px solid #f8f1f7;
    }

    .filter-title {
        font-family: 'Bubblegum Sans', cursive;
        color: #4a6fa5;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 50px;
        padding: 0.6rem 1.2rem;
        border: 2px solid #e6f0ff;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #b3d9ff;
        box-shadow: 0 0 0 0.2rem rgba(179, 217, 255, 0.25);
    }

    .form-label {
        font-weight: 600;
        color: #4a6fa5;
        margin-bottom: 0.5rem;
    }

    /* Modal styling */
    .modal-content {
        border-radius: 20px;
        overflow: hidden;
        border: none;
    }

    .modal-header {
        background-color: #f8f1f7;
        border-bottom: 2px dotted #f0f0f0;
    }

    .modal-title {
        font-family: 'Bubblegum Sans', cursive;
        color: #ff6b9d;
        font-size: 1.8rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-body img {
        height: 400px;
        width: auto;
        max-width: 100%;
        object-fit: contain;
    }

    .modal-footer {
        border-top: 2px dotted #f0f0f0;
        padding: 1rem 2rem;
    }

    /* Carousel styling */
    .carousel-item img {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
        opacity: 0.7;
    }

    /* Reviews section */
    .reviews-section {
        background-color: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .review-item {
        background-color: white;
        border-radius: 15px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .review-author {
        font-weight: 700;
        color: #4a6fa5;
    }

    .star-rating {
        color: #ffc107;
        margin: 0.5rem 0;
    }

    /* Pagination styling */
    .pagination .page-item .page-link {
        border-radius: 50px;
        margin: 0 3px;
        color: #4a6fa5;
    }

    .pagination .page-item.active .page-link {
        background-color: #b3d9ff;
        border-color: #b3d9ff;
    }

    .category-badge {
        margin-top: 0.5rem;
        font-size: 0.8rem;
    }

    .category-badge .badge {
        font-family: 'Nunito', sans-serif;
        font-weight: 600;
        padding: 0.35em 0.65em;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .store-title {
            font-size: 2.5rem;
        }

        .store-tagline {
            font-size: 1.2rem;
        }
    }
</style>

@include('layouts.flash-messages')

<div class="container py-5">
    <!-- Filter Section -->
    <div class="filter-section">
        <h3 class="filter-title text-center mb-4"><i class="fas fa-filter me-2"></i>Find Your Perfect Plushie</h3>
        <form method="GET" action="{{ route('shop.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="category_id" class="form-label">Filter by Category</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">-- All Categories --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->description }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="min_price" class="form-label">Min Price (₱)</label>
                    <input type="number" step="0.01" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
                </div>

                <div class="col-md-2">
                    <label for="max_price" class="form-label">Max Price (₱)</label>
                    <input type="number" step="0.01" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-squish btn-primary w-100">Apply Filter</button>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('shop.index') }}" class="btn btn-squish btn-outline w-100">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Plushie Collection -->
    <h2 id="plushies" class="text-center mb-4" style="font-family: 'Bubblegum Sans', cursive; color: #4a6fa5;">Our Adorable Collection</h2>
    <div class="row g-4">
        @foreach ($items as $item)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm plushie-card">
                <div class="card-img-container">
                    @if ($images->has($item->item_id) && $images[$item->item_id]->count())
                    <img src="{{ Storage::url($images[$item->item_id][0]->image_path) }}"
                        class="card-img-top"
                        alt="{{ $item->item_name }}">
                    @else
                    <img src="https://via.placeholder.com/400x250?text=No+Image"
                        class="card-img-top"
                        alt="No image">
                    @endif
                </div>

                <div class="card-body text-center">
                    <h5 class="card-title">{{ $item->item_name }}</h5>
                    <p class="category-badge mb-0">
                        <span class="badge rounded-pill" style="background-color: #b3d9ff; color: #4a6fa5;">
                            {{ $item->category_description ?? 'Uncategorized' }}
                        </span>
                    </p>
                    <p class="card-text text-muted">{{ Str::limit($item->description, 50) }}</p>
                    <p class="price-tag">₱{{ number_format($item->sell_price, 2) }}</p>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-squish btn-primary mb-2 me-2">
                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </a>
                    <button class="btn btn-squish btn-outline" data-bs-toggle="modal" data-bs-target="#itemModal{{ $item->item_id }}">
                        <i class="fas fa-eye me-1"></i> View Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal for Item Details -->
        <div class="modal fade" id="itemModal{{ $item->item_id }}" tabindex="-1" aria-labelledby="itemModalLabel{{ $item->item_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel{{ $item->item_id }}">{{ $item->item_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($images->has($item->item_id))
                        <div id="carouselItem{{ $item->item_id }}" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($images[$item->item_id] as $index => $img)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ Storage::url($img->image_path) }}"
                                        class="d-block mx-auto img-fluid"
                                        alt="{{ $item->item_name }}"
                                        style="height: 400px; object-fit: contain;">
                                </div>
                                @endforeach
                            </div>

                            @if(count($images[$item->item_id]) > 1)
                            <!-- Controls -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselItem{{ $item->item_id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselItem{{ $item->item_id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                        @else
                        <img src="https://via.placeholder.com/400x400?text=No+Image"
                            class="d-block mx-auto img-fluid mb-4"
                            alt="No image"
                            style="height: 400px; object-fit: contain;">
                        @endif

                        <div class="row">
                            <p class="category-badge mb-0">
                                <span class="badge rounded-pill" style="background-color: #b3d9ff; color: #4a6fa5;">
                                    {{ $item->category_description ?? 'Uncategorized' }}
                                </span>
                            </p>
                            <div class="col-md-8">
                                <h4 style="font-family: 'Bubblegum Sans', cursive; color: #4a6fa5;">About this Plushie</h4>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="price-tag mb-3" style="font-size: 1.5rem;">₱{{ number_format($item->sell_price, 2) }}</div>
                                <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-squish btn-primary w-100 mb-3">
                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                </a>
                            </div>
                        </div>

                        <hr>

                        <div class="reviews-section">
                            <h4 style="font-family: 'Bubblegum Sans', cursive; color: #4a6fa5;">Customer Reviews:</h4>
                            @if($reviews->has($item->item_id) && $reviews[$item->item_id]->count())
                            <div>
                                @foreach ($reviews[$item->item_id] ?? [] as $review)
                                <div class="review-item mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="review-author">{{ $review->customer_name }}</span>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('F j, Y') }}</small>
                                        </div>
                                        <small class="text-muted">Order #{{ $review->orderinfo_id }}</small>
                                    </div>

                                    {{-- Rating Stars --}}
                                    @if($review->rating)
                                    <div class="star-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                    </div>
                                    @endif
                                    <p class="mb-3">{{ $review->review_text }}</p>

                                    {{-- Display Review Images --}}
                                    @if (isset($reviewMedia[$review->review_id]))
                                    <div class="row g-2">
                                        @foreach ($reviewMedia[$review->review_id] as $media)
                                        <div class="col-4 col-md-3">
                                            <img src="{{ Storage::url(str_replace('public/', '', $media->image_path)) }}"
                                                class="img-fluid rounded shadow-sm"
                                                alt="Review Image">
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-2">No reviews yet.</p>
                                <small>Be the first to review this plushie!</small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-squish btn-outline" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('addToCart', $item->item_id) }}" class="btn btn-squish btn-primary">
                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal -->
        @endforeach
    </div>

    <!-- Empty State -->
    @if(count($items) === 0)
    <div class="text-center py-5">
        <div style="font-size: 5rem; color: #b3d9ff; margin-bottom: 1rem;">
            <i class="fas fa-search"></i>
        </div>
        <h3 style="font-family: 'Bubblegum Sans', cursive; color: #4a6fa5;">No plushies found</h3>
        <p class="text-muted">Try adjusting your filters or check back later for new additions!</p>
        <a href="{{ route('shop.index') }}" class="btn btn-squish btn-primary mt-3">Clear Filters</a>
    </div>
    @endif

    <!-- Pagination if needed -->
    @if(isset($items) && method_exists($items, 'links'))
    <div class="d-flex justify-content-center mt-5">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection