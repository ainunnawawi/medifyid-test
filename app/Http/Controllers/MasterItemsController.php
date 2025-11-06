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

    $data_search = MasterItem::with('kategoriItems');

    if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
    if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
    if (!empty($hargamin)) $data_search = $data_search->whereBetween('harga_beli', [$hargamin, $hargamax]);

    $data_search = $data_search
        ->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')
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
                'kategori' => $item->kategoriItems->pluck('nama')->implode(', '),
                'foto_url' => $item->foto
                    ? asset('storage/master_items/' . $item->foto)
                    : asset('images/no-image.png'),
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
    $request->validate([
        'nama' => 'required|string|max:255',
        'harga_beli' => 'required|numeric',
        'laba' => 'required|numeric',
        'supplier' => 'required|string',
        'jenis' => 'required|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($method == 'new') {
        $data_item = new MasterItem;
        $kode = str_pad(MasterItem::count('id') + 1, 5, '0', STR_PAD_LEFT);
    } else {
        $data_item = MasterItem::findOrFail($id);
        $kode = $data_item->kode;
    }

    $data_item->fill([
        'nama' => $request->nama,
        'harga_beli' => $request->harga_beli,
        'laba' => $request->laba,
        'kode' => $kode,
        'supplier' => $request->supplier,
        'jenis' => $request->jenis,
    ]);

    // 🟩 Upload Gambar
    if ($request->hasFile('foto')) {
        $filename = time().'_'.$request->file('foto')->getClientOriginalName();
        $request->file('foto')->storeAs('public/master_items', $filename);

        // hapus gambar lama jika edit
        if ($method == 'edit' && $data_item->foto && file_exists(storage_path('app/public/master_items/'.$data_item->foto))) {
            unlink(storage_path('app/public/master_items/'.$data_item->foto));
        }

        $data_item->foto = $filename;
    }

    $data_item->save();

    // simpan kategori
    $data_item->kategoriItems()->sync($request->kategori ?? []);

    return redirect('master-items')->with('success', 'Data berhasil disimpan!');
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
