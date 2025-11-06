@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- 🔹 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="fa fa-tags me-2"></i> Daftar Kategori Items
        </h4>
        <div>
            <a href="{{ url('kategori-items/form/new') }}" class="btn btn-primary shadow-sm">
                <i class="fa fa-plus-circle me-1"></i> Tambah Kategori
            </a>
        </div>
    </div>

    {{-- 🔹 Card Utama --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light fw-semibold">
            <i class="fa fa-list me-2"></i> Data Kategori
        </div>

        <div class="card-body bg-white">
            {{-- 🔸 Filter Section --}}
            <div class="mb-3">
                @include('kategori_items.index.filter')
            </div>

            {{-- 🔸 Tabel Data --}}
            <div class="table-responsive">
                @include('kategori_items.index.table')
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('kategori_items.index.js')
@endsection
