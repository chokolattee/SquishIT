@extends('layouts.base')

@section('content')
<div class="plushie-container">
    <div class="review-header-main">
        <h2>Share Your Plushie Experience</h2>
        <p class="review-subtitle">Order #{{ $order->id }}</p>
    </div>

    <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        
        @foreach($items as $index => $item)
        <div class="review-card">
            <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->id }}">
            
            <div class="form-group item-name">
                <label class="plushie-label">Your Plushie Friend</label>
                <div class="plushie-item-name">{{ $item->item_name }}</div>
            </div>

            @if($item->images->count())
            <div class="plushie-carousel-container">
                <div id="carouselItem{{ $item->id }}" class="carousel slide plushie-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        @foreach($item->images as $imgIndex => $img)
                        <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . str_replace('public/', '', $img)) }}" class="d-block w-100" alt="Plushie Image">
                        </div>
                        @endforeach
                    </div>

                    @if($item->images->count() > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselItem{{ $item->id }}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselItem{{ $item->id }}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="plushie-label">How Much Do You Love It?</label>
                <div class="star-rating-container">
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="items[{{ $index }}][rating]" id="star{{ $item->id }}_{{ $i }}" value="{{ $i }}" required>
                        <label for="star{{ $item->id }}_{{ $i }}">&#9733;</label>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="plushie-label">Tell Us Your Story</label>
                <textarea name="items[{{ $index }}][review_text]" class="plushie-textarea" rows="4" placeholder="Share your adventures with your plushie friend..."></textarea>
            </div>

            <div class="form-group">
                <label class="plushie-label">Share Cute Photos</label>
                <div class="file-upload-wrapper">
                    <label for="media-upload-{{ $item->id }}" class="custom-file-upload">
                        <i class="upload-icon">📷</i> Choose Files
                    </label>
                    <input id="media-upload-{{ $item->id }}" type="file" name="items[{{ $index }}][media_files][]" multiple accept="image/*,video/*">
                </div>
            </div>
        </div>
        @endforeach
        
        <button type="submit" class="plushie-button">Submit All Reviews</button>
    </form>
</div>

<style>
    .plushie-container {
        max-width: 800px;
        margin: 2rem auto;
        font-family: 'Comic Sans MS', 'Marker Felt', sans-serif;
    }
    
    .review-header-main {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .review-card {
        background-color: #fff8fa;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(255, 192, 203, 0.3);
        padding: 2rem;
        border: 2px dashed #ffb6c1;
        margin-bottom: 2.5rem;
    }
    
    .plushie-icon {
        border-radius: 50%;
        border: 3px solid #ffb6c1;
        background-color: #ffe6ea;
    }
    
    .review-header-main h2 {
        color: #e75480;
        margin: 0.5rem 0;
        font-size: 2rem;
    }
    
    .review-subtitle {
        color: #b5838d;
        font-size: 1rem;
        margin-top: 0;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .plushie-label {
        display: block;
        color: #e75480;
        font-weight: bold;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .plushie-item-name {
        background-color: #ffe6ea;
        padding: 0.7rem;
        border-radius: 15px;
        font-weight: bold;
        color: #e75480;
        text-align: center;
        border: 1px solid #ffb6c1;
        margin-bottom: 1rem;
    }
    
    .plushie-carousel-container {
        margin: 1rem 0;
        text-align: center;
    }
    
    .plushie-carousel {
        max-width: 300px;
        width: 100%;
        margin: 0 auto;
        border: 3px solid #ffb6c1;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(255, 182, 193, 0.4);
    }
    
    .plushie-carousel img {
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
    }
    
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: rgba(231, 84, 128, 0.6);
        border-radius: 50%;
        padding: 12px;
    }
    
    .star-rating-container {
        background-color: #ffe6ea;
        padding: 0.7rem;
        border-radius: 15px;
        text-align: center;
        border: 1px solid #ffb6c1;
    }
    
    .star-rating {
        direction: rtl;
        font-size: 2rem;
        display: inline-flex;
    }
    
    .star-rating input {
        display: none;
    }
    
    .star-rating label {
        color: #ffccd5;
        cursor: pointer;
        padding: 0 5px;
        transition: all 0.2s ease;
    }
    
    .star-rating input:checked~label,
    .star-rating input:checked~label~label {
        color: #ffac33;
    }
    
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #ffac33;
        transform: scale(1.2);
    }
    
    .plushie-textarea {
        width: 100%;
        border: 1px solid #ffb6c1;
        border-radius: 15px;
        padding: 0.8rem;
        font-family: inherit;
        background-color: #fff;
        resize: vertical;
        transition: border 0.3s ease;
    }
    
    .plushie-textarea:focus {
        outline: none;
        border: 1px solid #e75480;
        box-shadow: 0 0 8px rgba(231, 84, 128, 0.3);
    }
    
    .file-upload-wrapper {
        background-color: #ffe6ea;
        padding: 1rem;
        border-radius: 15px;
        text-align: center;
        border: 1px solid #ffb6c1;
    }
    
    [id^="media-upload-"] {
        display: none;
    }
    
    .custom-file-upload {
        background-color: #ffb6c1;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        cursor: pointer;
        display: inline-block;
        transition: all 0.3s ease;
        font-weight: bold;
    }
    
    .custom-file-upload:hover {
        background-color: #e75480;
        transform: scale(1.05);
    }
    
    .upload-icon {
        margin-right: 5px;
    }
    
    .plushie-button {
        background-color: #7cbb5e;
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 30px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: block;
        margin: 1.5rem auto 0;
        font-weight: bold;
        font-family: inherit;
        box-shadow: 0 4px 12px rgba(124, 187, 94, 0.4);
    }
    
    .plushie-button:hover {
        background-color: #69a34f;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(124, 187, 94, 0.5);
    }
    
    .plushie-button:active {
        transform: translateY(1px);
    }
</style>
@endsection