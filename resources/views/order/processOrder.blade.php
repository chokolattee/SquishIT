@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="p-5 shadow rounded-4" style="background-color: #FFF5F7; border: 3px dashed #FFB6C1;">
                <h2 class="mb-4 fw-bold" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                    <i class="fas fa-receipt me-2"></i> Order #{{ $customer->orderinfo_id }}
                </h2>

                <!-- Shipping Information -->
                <div class="mb-4 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-truck me-2"></i> Shipping Information
                    </h4>
                    <ul class="list-unstyled ps-3" style="font-family: 'Nunito', sans-serif;">
                        <li class="mb-2"><strong>Name:</strong> {{ $customer->lname }} {{ $customer->fname }}</li>
                        <li class="mb-2"><strong>Phone:</strong> {{ $customer->phone }}</li>
                        <li class="mb-2"><strong>Address:</strong> {{ $customer->addressline }}</li>
                        <li class="mb-2"><strong>Shipping Region:</strong> {{ $shippingRegion }}</li>
                        <li class="mb-2"><strong>Shipping Rate:</strong> ₱{{ number_format($shippingRate, 2) }}</li>
                        <li class="mb-0"><strong>Total Amount:</strong> ₱{{ number_format($total + $shippingRate, 2) }}</li>
                    </ul>
                </div>

                <!-- Order Status -->
                <div class="mb-4 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-info-circle me-2"></i> Order Status
                    </h4>
                    <p class="mb-0" style="font-family: 'Nunito', sans-serif;">
                        <strong>Current Status:</strong> 
                        <span class="badge px-3 py-2" style="background-color: #FF69B4; font-family: 'Comic Sans MS', 'Nunito', sans-serif;">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </p>
                </div>

                <!-- Order Items -->
                <div class="mb-4">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-gift me-2"></i> Order Items
                    </h4>
                    <div class="border-top pt-3">
                        @foreach($orders as $order)
                        <div class="d-flex mb-4 align-items-start rounded-4 p-3" style="background-color: #FFECF1; border: 2px solid #FFB6C1;">
                            <div class="d-flex flex-wrap me-3" style="gap: 8px;">
                                @if(isset($images[$order->item_id]))
                                    @foreach ($images[$order->item_id] as $img)
                                    <img src="{{ Storage::url($img->image_path) }}" alt="{{ $order->description }}"
                                        style="width: 70px; height: 70px; object-fit: cover;" class="rounded shadow-sm border">
                                    @endforeach
                                @else
                                    <img src="{{ asset('default-placeholder.png') }}"
                                        class="img-thumbnail" style="width: 70px; height: 70px;" alt="No image">
                                @endif
                            </div>
                            <div class="ms-3 flex-grow-1" style="font-family: 'Nunito', sans-serif;">
                                <h6 class="fw-bold mb-1">{{ $order->description }}</h6>
                                <p class="mb-0">Price: ₱{{ number_format($order->sell_price, 2) }}</p>
                                <p class="mb-0">Quantity: {{ $order->quantity }} Piece(s)</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Update Status -->
                <div class="mt-5 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-sync-alt me-2"></i> Update Status
                    </h4>
                    <form action="{{ route('admin.orderUpdate', $customer->orderinfo_id) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="status" class="col-form-label" style="font-family: 'Nunito', sans-serif;">Order Status:</label>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" id="status" name="status" style="border: 2px solid #FFB6C1; border-radius: 20px;">
                                    @foreach($statusChoices as $choice)
                                    <option value="{{ strtolower($choice) }}" {{ strtolower($status) == strtolower($choice) ? 'selected' : '' }}>
                                        {{ $choice }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-lg" style="background-color: #FF69B4; color: white; border-radius: 20px; font-family: 'Comic Sans MS', 'Nunito', sans-serif; font-weight: 600;">
                                    Update Order Status
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .form-select:focus {
        border-color: #FF69B4 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25) !important;
    }
</style>
@endpush
@endsection