@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{ url('master-items') }}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item</div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama</th>
                            <td width="5%">:</td>
                            <td>{{ $data->nama }}</td>
                        </tr>

                        {{-- ✅ tampilkan kategori sebagai badge --}}
                        <tr>
                            <th>Kategori</th>
                            <td>:</td>
                            <td>
                                @if ($data->kategoriItems->count() > 0)
                                    @foreach ($data->kategoriItems as $kat)
                                        <span class="badge bg-info text-dark">{{ $kat->nama }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada kategori</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Harga Beli</th>
                            <td>:</td>
                            <td>{{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Laba (%)</th>
                            <td>:</td>
                            <td>{{ $data->laba }}</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>{{ number_format($data->harga_beli + ($data->harga_beli * $data->laba) / 100, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td>{{ $data->supplier }}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>:</td>
                            <td>{{ $data->jenis }}</td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <a class="btn btn-info" href="{{ url('master-items/form/edit') }}/{{ $data->id }}">Edit</a>
                        <a class="btn btn-danger" href="{{ url('master-items/delete') }}/{{ $data->id }}"
                           onclick="return confirm('Yakin ingin menghapus item ini?');">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
