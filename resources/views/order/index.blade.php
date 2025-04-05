@extends('layouts.base')
@extends('layouts.flash-messages')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold text-primary">My Orders</h2>

                    <!-- Status filter tabs -->
                    <ul class="nav nav-pills nav-fill mb-4 plushie-tabs">
                        <li class="nav-item">
                            <a class="nav-link rounded-pill {{ $activeTab == 'all' ? 'active' : '' }}" href="{{ route('orders.my', ['status' => 'all']) }}">
                                <i class="bi bi-box-seam me-2"></i>All Orders
                                <span class="badge rounded-pill bg-light text-dark ms-2">{{ $statusCounts['all'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill {{ $activeTab == 'Pending' ? 'active' : '' }}" href="{{ route('orders.my', ['status' => 'Pending']) }}">
                                <i class="bi bi-hourglass-split me-2"></i>Pending
                                <span class="badge rounded-pill bg-warning text-dark ms-2">{{ $statusCounts['Pending'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill {{ $activeTab == 'Shipped' ? 'active' : '' }}" href="{{ route('orders.my', ['status' => 'Shipped']) }}">
                                <i class="bi bi-truck me-2"></i>Shipped
                                <span class="badge rounded-pill bg-info text-white ms-2">{{ $statusCounts['Shipped'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill {{ $activeTab == 'Delivered' ? 'active' : '' }}" href="{{ route('orders.my', ['status' => 'Delivered']) }}">
                                <i class="bi bi-house-heart me-2"></i>Delivered
                                <span class="badge rounded-pill bg-success text-white ms-2">{{ $statusCounts['Delivered'] ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill {{ $activeTab == 'Cancelled' ? 'active' : '' }}" href="{{ route('orders.my', ['status' => 'Cancelled']) }}">
                                <i class="bi bi-x-circle me-2"></i>Cancelled
                                <span class="badge rounded-pill bg-danger text-white ms-2">{{ $statusCounts['Cancelled'] ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>

                    @forelse($orders as $order)
                    <div class="card mb-4 shadow-sm border-0 rounded-4 order-card">
                        <div class="card-header border-0 rounded-top-4 d-flex justify-content-between align-items-center py-3
                            @if($order->order_status == 'Pending') bg-warning-subtle text-warning-emphasis 
                            @elseif($order->order_status == 'Shipped') bg-info-subtle text-info-emphasis
                            @elseif($order->order_status == 'Delivered') bg-success-subtle text-success-emphasis
                            @elseif($order->order_status == 'Cancelled') bg-secondary-subtle text-secondary-emphasis
                            @else bg-primary-subtle text-primary-emphasis @endif">

                            <div class="d-flex align-items-center">
                                <div class="order-status-icon me-3">
                                    @if($order->order_status == 'Pending')
                                    <i class="bi bi-hourglass-split fs-3"></i>
                                    @elseif($order->order_status == 'Shipped')
                                    <i class="bi bi-truck fs-3"></i>
                                    @elseif($order->order_status == 'Delivered')
                                    <i class="bi bi-house-heart fs-3"></i>
                                    @elseif($order->order_status == 'Cancelled')
                                    <i class="bi bi-x-circle fs-3"></i>
                                    @else
                                    <i class="bi bi-box-seam fs-3"></i>
                                    @endif
                                </div>
                                <div>
                                    <span class="fw-bold fs-5">Order #{{ $order->id }}</span>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($order->date_placed)->format('F d, Y') }}</div>
                                </div>
                            </div>

                            {{-- Action Buttons based on Order Status --}}
                            <div>
                                <span class="badge rounded-pill 
                                    @if($order->order_status == 'Pending') bg-warning text-dark
                                    @elseif($order->order_status == 'Shipped') bg-info text-white
                                    @elseif($order->order_status == 'Delivered') bg-success text-white
                                    @elseif($order->order_status == 'Cancelled') bg-secondary text-white
                                    @else bg-primary text-white @endif
                                    px-3 py-2 me-2">{{ $order->order_status }}</span>

                                @if ($order->order_status === 'Pending')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm px-3" onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="bi bi-x-circle me-1"></i> Cancel Order
                                    </button>
                                </form>
                                @elseif ($order->order_status === 'Delivered')
                                @php
                                // Check if a review exists for the current order
                                $hasReview = \App\Models\Review::where('order_id', $order->id)
                                ->where('customer_id', auth()->user()->customer->id)
                                ->exists();
                                @endphp

                                @if ($hasReview)
                                <a href="{{ route('reviews.index') }}" class="btn btn-outline-info rounded-pill btn-sm px-3">
                                    <i class="bi bi-star-fill me-1"></i> View Review
                                </a>
                                @else
                                <a href="{{ route('orders.review', $order->id) }}" class="btn btn-outline-primary rounded-pill btn-sm px-3">
                                    <i class="bi bi-pencil me-1"></i> Write Review
                                </a>
                                @endif
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="shipping-icon rounded-circle bg-light p-2 me-2">
                                            <i class="bi bi-truck text-primary"></i>
                                        </div>
                                        <span class="text-muted">Shipping:</span>
                                        <span class="ms-2">
                                            {{ $order->shipping_method ?? 'N/A' }}
                                            @if(isset($order->shipping_rate) && $order->shipping_rate > 0)
                                            (₱{{ number_format($order->shipping_rate, 2) }})
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-1"><span class="text-muted">Subtotal:</span> <span class="fw-medium">₱{{ number_format($order->subtotal, 2) }}</span></p>
                                    <p class="mb-0"><span class="text-muted">Total:</span> <span class="fs-5 fw-bold text-primary">₱{{ number_format($order->total, 2) }}</span></p>
                                </div>
                            </div>

                            <div class="order-items mt-4">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-box2-heart me-2"></i>Your Plushies</h6>
                                <div class="row g-4">
                                    @foreach($order->items as $item)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 plushie-item">
                                            <div class="card-body p-3">
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        @if (!empty($item->image_path))
                                                        <img src="{{ asset(str_replace('public/', 'storage/', $item->image_path)) }}"
                                                            alt="{{ $item->description }}"
                                                            class="rounded-4 plushie-image"
                                                            width="100" height="100"
                                                            style="object-fit: cover;">
                                                        @else
                                                        <div class="placeholder-image rounded-4 d-flex align-items-center justify-content-center bg-light"
                                                            style="width: 100px; height: 100px;">
                                                            <i class="bi bi-image text-secondary fs-2"></i>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">{{ $item->item_name }}</h6>
                                                        <div class="mb-1 text-muted small">
                                                            <span class="badge bg-light text-dark rounded-pill">
                                                                <i class="bi bi-123 me-1"></i>Qty: {{ $item->quantity }}
                                                            </span>
                                                        </div>
                                                        <div class="text-primary fw-medium">₱{{ number_format($item->sell_price, 2) }}</div>
                                                        <div class="text-muted small">Total: ₱{{ number_format($item->sell_price * $item->quantity, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="empty-state mb-3">
                            <i class="bi bi-bag-heart text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted">No orders yet</h5>
                    </div>
                    @endforelse
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

    /* Status-specific colors */
    .bg-warning-subtle {
        background-color: #fff8e1 !important;
    }

    .bg-info-subtle {
        background-color: #e1f5fe !important;
    }

    .bg-success-subtle {
        background-color: #e8f5e9 !important;
    }

    .plushie-tabs .nav-link {
        color: #6c757d;
        margin: 0 5px;
        transition: all 0.3s ease;
    }

    .plushie-tabs .nav-link:hover {
        transform: translateY(-2px);
    }

    .plushie-tabs .nav-link.active {
        background-color: #ff6b6b;
        color: white;
        box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
        transform: translateY(-2px);
    }

    .order-card {
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .plushie-image {
        border: 3px solid #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .plushie-item:hover .plushie-image {
        transform: scale(1.05);
    }

    .empty-state {
        opacity: 0.7;
    }

    .order-status-icon {
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection