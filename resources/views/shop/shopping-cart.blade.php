@extends('layouts.base')

@section('title', 'Plushie Shopping Cart')

@section('body')

<div class="container py-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-lg border-0 rounded-4" style="background-color: #FFF5F7; border: 2px dashed #FFB6C1 !important;">
                <div class="card-header text-center py-3" style="background-color: #FFD1DC; color: #9E4244; font-weight: bold; font-size: 1.5rem; border-bottom: 2px dashed #FFB6C1;">
                    <i class="bi bi-basket-heart-fill me-2"></i> {{ __('Your Plushie Collection') }} <i class="bi bi-basket-heart-fill ms-2"></i>
                </div>

                <div class="card-body p-4">
                    @if (Session::has('cart'))
                    <div class="list-group mb-4">
                        @foreach ($products as $product)
                        <div class="list-group-item mb-3 border-0 rounded-4 shadow-sm" style="background-color: #FFFDFD; border: 1px solid #FFB6C1 !important;">
                            <div class="row align-items-center py-2">

                                <!-- COLUMN 1: Product Image & Details -->
                                <div class="col-md-6 d-flex align-items-center">
                                    @php
                                    $firstImage = $product['item']->images->first()?->image_path;
                                    @endphp

                                    <div style="width: 100px; height: 100px; border-radius: 15px; overflow: hidden; border: 2px solid #FFB6C1; background-color: #FFFDFD;" class="d-flex align-items-center justify-content-center shadow-sm me-3">
                                        <img src="{{ $firstImage ? Storage::url($firstImage) : asset('images/default.png') }}"
                                            alt="Plushie"
                                            style="max-width: 90px; max-height: 90px; object-fit: contain;">
                                    </div>
                                    <div>
                                        <h5 class="mb-1" style="color: #D25A7E; font-weight: bold;">{{ $product['item']['item_name'] }}</h5>
                                        <div style="background-color: #FFE4EE; color: #9E4244; border-radius: 20px; display: inline-block; padding: 0.25rem 0.75rem; font-weight: bold;">
                                            ₱{{ $product['item']['sell_price'] }}
                                        </div>
                                    </div>
                                </div>

                                <!-- COLUMN 2: Quantity + Remove Button -->
                                <div class="col-md-6 d-flex justify-content-end align-items-center flex-wrap">
                                    <form action="{{ route('updateCart', $product['item']['item_id']) }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <div class="input-group me-2" style="max-width: 140px;">
                                            <!-- Decrease Button -->
                                            <button type="button" class="btn" style="background-color: #FFDBE9; color: #D25A7E; border: 2px solid #FFB6C1; border-radius: 10px 0 0 10px;" onclick="decreaseQuantity(this)">-</button>

                                            <!-- Quantity Input -->
                                            <input type="number" name="quantity"
                                                class="form-control text-center item-qty"
                                                style="background-color: #FFFDFD; border: 2px solid #FFB6C1; color: #D25A7E; font-weight: bold;"
                                                value="{{ $product['qty'] }}"
                                                min="1"
                                                readonly
                                                data-price="{{ $product['item']['sell_price'] }}">

                                            <!-- Increase Button -->
                                            <button type="button" class="btn" style="background-color: #FFDBE9; color: #D25A7E; border: 2px solid #FFB6C1; border-radius: 0 10px 10px 0;" onclick="increaseQuantity(this)">+</button>
                                        </div>
                                        <button type="submit" class="btn" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.5rem 1rem; font-weight: bold; box-shadow: 0 2px 5px rgba(255, 182, 193, 0.5);">
                                            <i class="bi bi-check-circle"></i> Update
                                        </button>
                                    </form>

                                    <!-- Remove Button -->
                                    <a href="{{ route('removeItem', $product['item']['item_id']) }}" class="btn ms-2 mt-2 mt-sm-0" style="background-color: #FFC0CB; color: #9E4244; border-radius: 20px; padding: 0.5rem 1rem; font-weight: bold; box-shadow: 0 2px 5px rgba(255, 182, 193, 0.5);">
                                        <i class="bi bi-x-circle"></i> Remove
                                    </a>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        <!-- Customer & Shipping Details -->
                        <div class="card border-0 rounded-4 shadow-sm mb-4" style="background-color: #FFFDFD; border: 2px dashed #FFB6C1 !important;">
                            <div class="card-header text-center" style="background-color: #FFE4EE; color: #D25A7E; font-weight: bold; border-bottom: 2px dashed #FFB6C1;">
                                <i class="bi bi-house-heart me-2"></i> Shipping Details
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('checkout') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                                <i class="bi bi-person-heart"></i> First Name
                                            </label>
                                            <input type="text" class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem;" name="fname" value="{{ old('fname', $customer->fname) }}" readonly>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                                <i class="bi bi-person-heart"></i> Last Name
                                            </label>
                                            <input type="text" class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem;" name="lname" value="{{ old('lname', $customer->lname) }}" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                            <i class="bi bi-house-heart"></i> Address
                                        </label>
                                        <input type="text" class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem;" name="addressline" value="{{ old('addressline', $customer->addressline) }}" readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                                <i class="bi bi-buildings"></i> Town
                                            </label>
                                            <input type="text" class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem;" name="town" value="{{ old('town', $customer->town) }}" readonly>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                                <i class="bi bi-telephone-heart"></i> Phone
                                            </label>
                                            <input type="text" class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem;" name="phone" value="{{ old('phone', $customer->phone) }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Shipping Method Dropdown -->
                                    <div class="mb-4">
                                        <label class="form-label" style="color: #D25A7E; font-weight: bold;">
                                            <i class="bi bi-truck-heart"></i> Shipping Method
                                        </label>
                                        <select class="form-control" style="border: 2px solid #FFB6C1; border-radius: 15px; background-color: #FFF5F7; padding: 0.5rem 1rem; color: #D25A7E;" name="shipping_id" id="shippingSelect" onchange="updateTotalPrice()">
                                            <option value="">Select Shipping Method</option>
                                            @foreach ($shippingOptions as $option)
                                            <option value="{{ $option->shipping_id }}" data-rate="{{ $option->rate }}" {{ old('shipping_id') == $option->shipping_id ? 'selected' : '' }}>
                                                {{ $option->region }} - ₱{{ $option->rate }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="text-end">
                                        <div style="background-color: #FFE4EE; padding: 1rem; border-radius: 15px; display: inline-block; margin-bottom: 1rem;">
                                            <h4 style="color: #D25A7E; margin-bottom: 0;">Total: <strong>₱<span id="totalPrice">{{ $totalPrice + $rate }}</span></strong></h4>
                                        </div>
                                        <div class="d-block">
                                            <button type="submit" class="btn" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.75rem 2rem; font-weight: bold; font-size: 1.1rem; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.5);">
                                                <i class="bi bi-cart-heart-fill me-2"></i> Complete Your Order
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="text-center py-5" style="background-color: #FFE4EE; border-radius: 20px; border: 2px dashed #FFB6C1;">
                        <h4 style="color: #D25A7E; font-weight: bold;">Your Plushie Basket is Empty!</h4>
                        <p style="color: #9E4244; font-size: 1.1rem;">Time to find your new cuddly friends!</p>
                        <a href="{{ route('getItems') }}" class="btn mt-2" style="background-color: #FF80AA; color: white; border-radius: 20px; padding: 0.75rem 2rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.5);">
                            <i class="bi bi-stars me-2"></i> Find Your Plushies
                        </a>
                    </div>
                    @endif
                </div>

                <div class="card-footer text-center py-3" style="background-color: #FFE4EE; border-top: 2px dashed #FFB6C1;">
                    <a href="{{ route('getItems') }}" class="btn" style="background-color: #FFDBE9; color: #D25A7E; border-radius: 20px; padding: 0.5rem 1.5rem; font-weight: bold; box-shadow: 0 4px 8px rgba(255, 182, 193, 0.3);">
                        <i class="bi bi-arrow-left-heart"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalPrice();
    });

    function decreaseQuantity(button) {
        let input = button.nextElementSibling;
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            updateTotalPrice();
        }
    }

    function increaseQuantity(button) {
        let input = button.previousElementSibling;
        input.value = parseInt(input.value) + 1;
        updateTotalPrice();
    }

    function updateTotalPrice() {
        let totalItemPrice = 0;
        const qtyInputs = document.querySelectorAll('.item-qty');

        qtyInputs.forEach(input => {
            const qty = parseInt(input.value);
            const price = parseFloat(input.getAttribute('data-price'));
            totalItemPrice += qty * price;
        });

        const shippingSelect = document.getElementById('shippingSelect');
        const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
        const shippingRate = selectedOption && selectedOption.value ? parseFloat(selectedOption.getAttribute('data-rate')) : 0;

        const total = totalItemPrice + shippingRate;

        document.getElementById('totalPrice').textContent = total.toFixed(2);
    }

    document.getElementById('shippingSelect')?.addEventListener('change', updateTotalPrice);
</script>

@endsection