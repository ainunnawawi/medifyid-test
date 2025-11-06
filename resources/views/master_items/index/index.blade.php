@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="fa fa-cubes me-2"></i> Daftar Master Items
        </h4>
        <div>
            <a href="{{ url('master-items/form/new') }}" class="btn btn-primary me-2 shadow-sm">
                <i class="fa fa-plus-circle me-1"></i> Tambah Item
            </a>
            <a href="{{ route('master-items.exportExcel') }}" class="btn btn-success shadow-sm">
                <i class="fa fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light fw-semibold">
            <i class="fa fa-list me-2"></i> Data Items
        </div>
        <div class="card-body bg-white">
            {{-- Filter section --}}
            <div class="mb-3">
                @include('master_items.index.filter')
            </div>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                @include('master_items.index.table')
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @include('master_items.index.js')
@endsection
