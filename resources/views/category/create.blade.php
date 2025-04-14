@php
use Collective\Html\FormFacade as Form;
@endphp

@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm rounded-4" style="border: 3px dashed #FFB6C1; background-color: #FFF5F7;">
                <div class="card-header text-white" style="background-color: #FF69B4; border-bottom: 2px solid #FFD1DC;">
                    <h4 class="mb-0" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif;">
                        <i class="fas fa-plus-circle me-2"></i> Add New Plushie Category
                    </h4>
                </div>
                <div class="card-body">
                {!! Form::open(['route' => 'categories.store', 'method' => 'POST']) !!}
                    <!-- Category Description -->
                    <div class="mb-4">
                        {!! Form::label('description', 'Category Name', ['class' => 'form-label fw-bold', 'style' => 'font-family: "Comic Sans MS", "Nunito", sans-serif; color: #FF69B4;']) !!}
                        {!! Form::text('description', null, [
                            'class' => 'form-control',
                            'id' => 'description',
                            'required' => true,
                            'placeholder' => 'Enter a cute category name',
                            'style' => 'border: 2px solid #FFB6C1; border-radius: 20px;'
                        ]) !!}
                        @error('description')
                        <div class="alert alert-danger mt-2" style="border-radius: 15px; border-left: 5px solid #FF69B4;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        {!! Form::submit('Create Category', [
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