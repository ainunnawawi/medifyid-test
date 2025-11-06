<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use Illuminate\Http\Request;
use App\Exports\MasterItemExport;
use Maatwebsite\Excel\Facades\Excel;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::with('kategoriItems'); // ✅ ambil relasi kategori

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search
            ->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')
            ->orderBy('id')
            ->get()
            ->map(function ($item) {
                return [
                    'kode' => $item->kode,
                    'nama' => $item->nama,
                    'jenis' => $item->jenis,
                    'harga_beli' => $item->harga_beli,
                    'harga_jual' => $item->harga_beli + ($item->harga_beli * $item->laba / 100),
                    'supplier' => $item->supplier,
                    // ✅ gabungkan semua nama kategori
                    'kategori' => $item->kategoriItems->pluck('nama')->implode(', '),
                ];
            });

        return response()->json([
            'status' => 200,
            'data' => $data_search,
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new MasterItem;
        } else {
            $item = MasterItem::with('kategoriItems')->find($id);
        }

        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategori'] = KategoriItem::orderBy('nama')->get();

        return view('master_items.form.index', $data);
    }


    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->save();

        // 🟩 Simpan relasi kategori (tabel pivot)
        $data_item->kategoriItems()->sync($request->kategori ?? []);

        return redirect('master-items');
    }


    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }

    public function exportExcel()
    {
        return Excel::download(new MasterItemExport, 'master_items.xlsx');
    }
}
