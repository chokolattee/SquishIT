@extends('layouts.base')

@section('body')
@include('layouts.flash-messages')

<div class="card shadow-sm rounded-4 p-4" style="background-color: #FFF5F7; border: 3px dashed #FFB6C1;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold mb-0" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
            <i class="fas fa-teddy-bear me-2"></i> Plushie Inventory
        </h2>
        <span class="text-muted" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif;">
            Hello, <strong style="color: #FF69B4;">{{ Auth::user()->name }}</strong> 
            <i class="fas fa-heart ms-1" style="color: #FF69B4;"></i>
        </span>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="POST" enctype="multipart/form-data" action="{{ route('item.import') }}" class="d-flex align-items-stretch gap-2 w-100">
            @csrf
            <div class="flex-grow-1">
                <input type="file" id="uploadName" name="item_upload" class="form-control h-100 border-2" required 
                    style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; height: 38px; border-color: #FFB6C1; border-radius: 20px;">
            </div>
            <button type="submit" class="btn text-white flex-shrink-0" 
                style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; font-weight: 600; height: 38px; line-height: 1; background-color: #FF69B4; border-radius: 20px;">
                <i class="fas fa-file-import me-2"></i> Import Plushies
            </button>
        </form>
    </div>

    <div class="p-3 mb-4 rounded-3" style="background-color: #FFECF1; border-left: 5px solid #FFB6C1;">
        <p class="mb-0" style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
            <i class="fas fa-info-circle me-2"></i> Manage your adorable plushie inventory here!
        </p>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <h4 style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; color: #FF69B4;">
            <i class="fas fa-list-alt me-2"></i> Plushie Collection
        </h4>
        <a href="{{ route('items.create') }}" class="btn" 
            style="font-family: 'Comic Sans MS', 'Nunito', sans-serif; font-weight: 600; background-color: #FF69B4; color: white; border-radius: 20px;">
            <i class="fas fa-plus-circle me-2"></i> Add New Plushie
        </a>
    </div>

    <div class="table-responsive mt-4">
        <div class="card-body rounded-3" style="background-color: #FFF; border: 2px solid #FFD1DC;">
            {{ $dataTable->table(['class' => 'table table-hover w-100', 'id' => 'items-table'], true) }}
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .table th {
        background-color: #FFECF1 !important;
        color: #FF69B4 !important;
        border-bottom: 2px solid #FFB6C1 !important;
        font-family: 'Comic Sans MS', 'Nunito', sans-serif;
    }
    
    .table tbody tr:hover {
        background-color: #FFF5F7 !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #FF69B4 !important;
        color: white !important;
        border: 1px solid #FF69B4 !important;
        border-radius: 50% !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #FFECF1 !important;
        color: #FF69B4 !important;
        border: 1px solid #FFB6C1 !important;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #FFB6C1 !important;
        border-radius: 20px !important;
        padding: 5px 10px !important;
    }
    
    .buttons-create {
        background-color: #FF69B4 !important;
        border-color: #FF69B4 !important;
    }
    
    .form-control:focus {
        border-color: #FF69B4 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 105, 180, 0.25) !important;
    }
</style>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>
{!! $dataTable->scripts() !!}
@endpush
@endsection