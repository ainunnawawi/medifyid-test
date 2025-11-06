<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'harga_beli',
        'laba',
        'supplier',
        'jenis',
        'harga_jual',
        'foto'
    ];

    public function kategoriItems()
    {
        return $this->belongsToMany(KategoriItem::class, 'item_kategori');
    }


    public function getKategoriNamaAttribute()
    {
        return $this->kategoris->pluck('nama')->implode(', ');
    }
}
