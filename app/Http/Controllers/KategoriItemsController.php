<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = KategoriItem::query();

        if (!empty($kode)) {
            $data_search = $data_search->where('kode', $kode);
        }

        if (!empty($nama)) {
            $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        }

        $data_search = $data_search->select('kode', 'nama')->orderBy('id')->get();

        return response()->json([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $kategori = [];
        } else {
            $kategori = KategoriItem::find($id);
        }

        $data['kategori'] = $kategori;
        $data['method'] = $method;

        return view('kategori_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = KategoriItem::where('kode', $kode)->first();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_kategori = new KategoriItem;
            $lastKode = KategoriItem::max('kode');
            $nextNumber = $lastKode ? (int)$lastKode + 1 : 1;
            $kode = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        } else {
            $data_kategori = KategoriItem::find($id);
            $kode = $data_kategori->kode;
        }

        $data_kategori->nama = $request->nama;
        $data_kategori->kode = $kode;
        $data_kategori->save();

        return redirect('kategori-items');
    }

    public function delete($id)
    {
        KategoriItem::find($id)?->delete();
        return redirect('kategori-items');
    }

    public function updateRandomData()
    {
        $data = KategoriItem::get();

        foreach ($data as $item) {
            $kode = str_pad($item->id, 4, '0', STR_PAD_LEFT);
            $item->kode = $kode;
            $item->save();
        }
    }

    public function print($id)
    {
        $kategori = KategoriItem::with('masterItems')->findOrFail($id);

        $pdf = Pdf::loadView('kategori_items.print', [
            'kategori' => $kategori,
            'tanggal_cetak' => now()->format('d F Y H:i'),
        ])->setPaper('A4', 'portrait');

        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}
