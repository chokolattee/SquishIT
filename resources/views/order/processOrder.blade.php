@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="p-5 shadow rounded-4" style="background-color: #FFF5F7; border: 3px dashed #FFB6C1;">
                <h2 class="mb-4 fw-bold" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                    <i class="fas fa-receipt me-2"></i> Order #{{ $customer->order_id }}
                </h2>

                <!-- Order Items -->
                <div class="mb-4 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-box-open me-2"></i> Items Ordered
                    </h4>
                    <table class="table table-bordered" style="font-family: 'Nunito', sans-serif;">
                        <thead style="background-color: #FFB6C1; color: white;">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $total = 0; // Initialize total
                            @endphp
                            @foreach ($orderItems as $item)
                            @php
                            $subtotal = $item->quantity * $item->sell_price; // Calculate subtotal for each item
                            $total += $subtotal; // Add subtotal to total
                            @endphp
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->sell_price, 2) }}</td>
                                <td>₱{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Item Total:</strong></td>
                                <td><strong>₱{{ number_format($total, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Shipping Information -->
                <div class="mb-4 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-truck me-2"></i> Shipping Information
                    </h4>
                    <ul class="list-unstyled ps-3" style="font-family: 'Nunito', sans-serif;">
                        <li class="mb-2"><strong>Name:</strong> {{ $customer->fname }} {{ $customer->lname }} </li>
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

                <!-- Update Status -->
                @if(strtolower($customer->status) !== 'delivered' && strtolower($customer->status) !== 'cancelled')
                <div class="mt-5 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-sync-alt me-2"></i> Update Status
                    </h4>
                    <form action="{{ route('admin.orderUpdate', $customer->order_id) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="status" class="col-form-label" style="font-family: 'Nunito', sans-serif;">Order Status:</label>
                            </div>
                            <div class="col-auto">
                                <select class="form-select" id="status" name="status" style="border: 2px solid #FFB6C1; border-radius: 20px;">
                                    @if(strtolower($customer->status) === 'pending')
                                    <option value="shipped">Shipped</option>
                                    <option value="cancelled">Cancelled</option>
                                    @elseif(strtolower($customer->status) === 'shipped')
                                    <option value="delivered">Delivered</option>
                                    @endif
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
                @elseif(strtolower($customer->status) === 'cancelled')
                <div class="mt-5 p-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
                    <h4 class="mb-3" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
                        <i class="fas fa-ban me-2"></i> Update Status
                    </h4>
                    <p class="text-danger" style="font-family: 'Nunito', sans-serif;">This order has been cancelled. Status updates are not allowed.</p>
                </div>
                @endif
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