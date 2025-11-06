<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kategori {{ $kategori->nama }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #eee; }
        footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <h3>Detail Kategori</h3>
    <p><strong>Nama Kategori:</strong> {{ $kategori->nama }}</p>
    <p><strong>Kode Kategori:</strong> {{ $kategori->kode }}</p>

    <h4>Daftar Item</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Item</th>
                <th>Nama Item</th>
                <th>Harga Beli</th>
                <th>Laba (%)</th>
                <th>Harga Jual</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori->masterItems as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ number_format($item->harga_beli,0,',','.') }}</td>
                    <td>{{ $item->laba }}</td>
                    <td>{{ number_format($item->harga_beli + $item->harga_beli * $item->laba / 100,0,',','.') }}</td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;">Tidak ada item</td></tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Dicetak pada {{ $tanggal_cetak }}
    </footer>
</body>
</html>
