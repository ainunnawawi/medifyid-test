@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group mb-2">
                    <a href="{{ url('kategori-items') }}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
                </div>
                <div class="card">
                    <div class="card-header">Detail Kategori</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Nama Kategori</th>
                                <td>{{ $data->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kode Kategori</th>
                                <td>{{ $data->kode ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $data->deskripsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ $data->created_at ? $data->created_at->format('d-m-Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ $data->updated_at ? $data->updated_at->format('d-m-Y H:i') : '-' }}</td>
                            </tr>
                        </table>

                        <a class="btn btn-info" href="{{ url('kategori-items/form/edit/' . $data->id) }}">Edit</a>
                        <a class="btn btn-danger" href="{{ url('kategori-items/delete/' . $data->id) }}"
                            onclick="return confirm('Yakin ingin menghapus kategori ini?');">Delete</a>
                        {{-- Tambahkan tombol Download PDF di sini --}}
                        <a href="{{ route('kategori-items.print', $data->id) }}" class="btn btn-danger" target="_blank">
                            <i class="fa fa-file-pdf"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
