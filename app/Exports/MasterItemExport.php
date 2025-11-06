<?php
namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterItemExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $items = MasterItem::with('kategoriItems')->get();

        return $items->map(function ($item, $index) {
            $kategori_nama = $item->kategoriItems->pluck('nama')->join(', ');
            $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

            return [
                'No' => $index + 1,
                'Nama Kategori' => $kategori_nama,
                'Nama Item' => $item->nama,
                'Supplier' => $item->supplier ?? '-',
                'Harga' => $item->harga_beli,
                'Laba' => $item->laba,
                'Harga Jual' => $harga_jual,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kategori',
            'Nama Item',
            'Nama Supplier',
            'Harga',
            'Laba',
            'Harga Jual',
        ];
    }
}

