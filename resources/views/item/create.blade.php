@php
use Collective\Html\FormFacade as Form;
use App\Models\Category;
@endphp

@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4" style="border: 3px dashed #FFB6C1; background-color: #FFF5F7;">
                <div class="card-header text-white" style="background-color: #FF69B4; border-bottom: 2px solid #FFD1DC;">
                    <h4 class="mb-0" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif;">
                        <i class="fas fa-plus-circle me-2"></i> Add New Plushie
                    </h4>
                </div>
                <div class="card-body">
                    {!! Form::open(['route' => 'items.store', 'files' => true]) !!}

                    <!-- Item Name -->
                    <div class="mb-3">
                        {!! Form::label('item_name', 'Item Name', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::text('item_name', null, [
                            'class' => 'form-control',
                            'id' => 'item_name',
                            'required' => true,
                            'placeholder' => 'Enter your plushie name',
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('item_name')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        {!! Form::label('description', 'Description', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::text('description', null, [
                            'class' => 'form-control',
                            'id' => 'description',
                            'required' => true,
                            'placeholder' => 'Describe your cuddly friend',
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('description')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cost Price -->
                    <div class="mb-3">
                        {!! Form::label('cost_price', 'Cost Price', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::number('cost_price', 0.00, [
                            'min' => 0.00,
                            'step' => 0.01,
                            'class' => 'form-control',
                            'id' => 'cost_price',
                            'required' => true,
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('cost_price')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sell Price -->
                    <div class="mb-3">
                        {!! Form::label('sell_price', 'Sell Price', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::number('sell_price', 0.00, [
                            'min' => 0.00,
                            'step' => 0.01,
                            'class' => 'form-control',
                            'id' => 'sell_price',
                            'required' => true,
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('sell_price')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        {!! Form::label('category_id', 'Category', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::select('category_id', ['' => 'Choose a plushie category'] + Category::pluck('description', 'category_id')->toArray(), null, [
                            'class' => 'form-control',
                            'required' => true,
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('category_id')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="mb-3">
                        {!! Form::label('qty', 'Quantity', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::number('qty', null, [
                            'class' => 'form-control',
                            'id' => 'qty',
                            'placeholder' => 'How many?',
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('qty')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload Images -->
                    <div class="mb-4">
                        {!! Form::label('images[]', 'Plushie Photos', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        <div class="p-3 mb-2 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                            <p class="mb-0" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; font-size: 0.9rem; color: #FF69B4;">
                                <i class="fas fa-info-circle me-2"></i> Add adorable photos of your plushie friend!
                            </p>
                        </div>
                        {!! Form::file('images[]', [
                            'class' => 'form-control',
                            'multiple' => true,
                            'required' => true,
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('images')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                        @if ($errors->has('images.*'))
                        @foreach ($errors->get('images.*') as $messages)
                        @foreach ($messages as $message)
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @endforeach
                        @endforeach
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        {!! Form::submit('Add Item', [
                            'class' => 'btn btn-lg',
                            'style' => 'background-color: #FF69B4; color: white; border-radius: 20px; font-family: "Comic Sans MS", "Nunito", sans-serif; font-weight: 600;'
                        ]) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .form-control:focus {
        border-color: #FF69B4 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25) !important;
    }
</style>
@endpush
@endsection