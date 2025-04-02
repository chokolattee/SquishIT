@extends('layouts.base')

@section('body')
<div class="container py-5">
    <h1 class="text-center mb-4 fw-bold" style="color: #7B5EA7;">Admin Dashboard</h1>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card mb-3 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-blue border-0">
                    <h5 class="mb-0 fw-bold" style="color: #4A6FA5;">Total Orders</h5>
                </div>
                <div class="card-body bg-white text-center">
                    <h2 class="card-title mb-3 display-4 fw-bold" style="color: #4A6FA5;">{{ $totalOrders }}</h2>
                    <a href="{{ route('admin.orders') }}" class="btn btn-pastel-blue rounded-pill px-4 fw-bold">
                        <i class="fas fa-shopping-bag me-2"></i>View Orders
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-green border-0">
                    <h5 class="mb-0 fw-bold" style="color: #4A8C59;">Total Users</h5>
                </div>
                <div class="card-body bg-white text-center">
                    <h2 class="card-title mb-3 display-4 fw-bold" style="color: #4A8C59;">{{ $totalUsers }}</h2>
                    <a href="{{ route('admin.users') }}" class="btn btn-pastel-green rounded-pill px-4 fw-bold">
                        <i class="fas fa-users me-2"></i>View Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row mb-4">
        {{-- Customer Demographics --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-purple border-0">
                    <h5 class="mb-0 fw-bold" style="color: #7B5EA7;">Customer Demographics</h5>
                </div>
                <div class="card-body bg-white p-4">
                    {!! $customerChart->container() !!}
                </div>
            </div>
        </div>

        {{-- Yearly Sales --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-pink border-0">
                    <h5 class="mb-0 fw-bold" style="color: #A54A6F;">Yearly Sales</h5>
                </div>
                <div class="card-body bg-white p-4">
                    {!! $yearChart->container() !!}
                </div>
            </div>
        </div>

        {{-- Monthly Sales --}}
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-blue border-0">
                    <h5 class="mb-0 fw-bold" style="color: #4A6FA5;">Monthly Sales</h5>
                </div>
                <div class="card-body bg-white p-4">
                    {!! $monthChart->container() !!}
                </div>
            </div>
        </div>

        {{-- Sales by Date Range --}}
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-purple border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #7B5EA7;">Sales by Date Range</h5>
                </div>
                <div class="card-body bg-white p-4">
                    <form method="GET" action="{{ route('dashboard.index') }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label fw-bold" style="color: #7B5EA7;">Start Date</label>
                                <input type="date" name="start_date" class="form-control rounded-pill border-pastel-purple" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-5">
                                <label for="end_date" class="form-label fw-bold" style="color: #7B5EA7;">End Date</label>
                                <input type="date" name="end_date" class="form-control rounded-pill border-pastel-purple" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-pastel-purple rounded-pill w-100 fw-bold">
                                    <i class="fas fa-filter me-2"></i>Apply
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Smaller chart inside --}}
                    <div style="height: 150px;">
                        {!! $rangeChart->container() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Sales Contribution --}}
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-pastel-green border-0">
                    <h5 class="mb-0 fw-bold" style="color: #4A8C59;">Product Sales Contribution</h5>
                </div>
                <div class="card-body bg-white p-4">
                    {!! $pieChart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

{{-- Plushie Theme Styles --}}
<style>
    .bg-pastel-pink {
        background-color: #FFD1DC !important;
    }
    
    .bg-pastel-blue {
        background-color: #D1E8FF !important;
    }
    
    .bg-pastel-purple {
        background-color: #E0C1F4 !important;
    }
    
    .bg-pastel-green {
        background-color: #D1FFD6 !important;
    }
    
    .btn-pastel-blue {
        background-color: #D1E8FF;
        border-color: #B8D9FF;
        color: #4A6FA5;
        transition: all 0.3s ease;
    }
    
    .btn-pastel-blue:hover {
        background-color: #B8D9FF;
        color: #4A6FA5;
    }
    
    .btn-pastel-green {
        background-color: #D1FFD6;
        border-color: #B8F0BF;
        color: #4A8C59;
        transition: all 0.3s ease;
    }
    
    .btn-pastel-green:hover {
        background-color: #B8F0BF;
        color: #4A8C59;
    }
    
    .btn-pastel-purple {
        background-color: #E0C1F4;
        border-color: #D1A7F0;
        color: #7B5EA7;
        transition: all 0.3s ease;
    }
    
    .btn-pastel-purple:hover {
        background-color: #D1A7F0;
        color: #7B5EA7;
    }
    
    .border-pastel-purple {
        border-color: #D1A7F0 !important;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(209, 167, 240, 0.25);
        border-color: #D1A7F0;
    }
    
    /* Customize chart appearance */
    canvas {
        border-radius: 0.5rem;
    }
</style>

{{-- Render Chart Scripts --}}
{!! $customerChart->script() !!}
{!! $yearChart->script() !!}
{!! $monthChart->script() !!}
{!! $rangeChart->script() !!}
{!! $pieChart->script() !!}
@endsection