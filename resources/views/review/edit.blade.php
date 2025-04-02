@extends('layouts.base')

@section('content')
<div class="plushie-container">
    <div class="review-card">
        <div class="review-header">
            <h2>Edit Your Plushie Review</h2>
            <p class="review-subtitle">Review #{{ $review->review_id }}</p>
        </div>

        <form action="{{ route('reviews.update', $review->review_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="item_id" value="{{ $review->item_id }}">

            <div class="form-group item-name">
                <label class="plushie-label">Item</label>
                <div class="plushie-item-name">{{ $review->item_name ?? 'Plushie Item' }}</div>
            </div>

            <div class="form-group">
                <label class="plushie-label">Rating</label>
                <div class="star-rating-container">
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }}>
                        <label for="star{{ $i }}">&#9733;</label>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="plushie-label">Comment</label>
                <textarea name="review_text" class="plushie-textarea" rows="4" placeholder="Share your adventures with your plushie friend...">{{ $review->review_text }}</textarea>
            </div>

            <div class="form-group">
                <label class="plushie-label">Share Review Media</label>
                <div class="file-upload-wrapper">
                    <label for="media-upload" class="custom-file-upload">
                        <i class="upload-icon">📷</i> Choose Files
                    </label>
                    <input id="media-upload" type="file" name="media_files[]" multiple accept="image/*,video/*">
                </div>
            </div>

            <button type="submit" class="plushie-button">Update Your Review</button>
        </form>
    </div>
</div>

<style>
    .plushie-container {
        max-width: 800px;
        margin: 2rem auto;
        font-family: 'Comic Sans MS', 'Marker Felt', sans-serif;
    }
    
    .review-card {
        background-color: #fff8fa;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(255, 192, 203, 0.3);
        padding: 2rem;
        border: 2px dashed #ffb6c1;
    }
    
    .review-header {
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 1rem;
        border-bottom: 2px dotted #ffb6c1;
    }
    
    .plushie-icon {
        border-radius: 50%;
        border: 3px solid #ffb6c1;
        background-color: #ffe6ea;
    }
    
    .review-header h2 {
        color: #e75480;
        margin: 0.5rem 0;
        font-size: 1.8rem;
    }
    
    .review-subtitle {
        color: #b5838d;
        font-size: 0.9rem;
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
    
    #media-upload {
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
        background-color: #e75480;
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
        box-shadow: 0 4px 12px rgba(231, 84, 128, 0.3);
    }
    
    .plushie-button:hover {
        background-color: #d13e69;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(231, 84, 128, 0.4);
    }
    
    .plushie-button:active {
        transform: translateY(1px);
    }
</style>
@endsection