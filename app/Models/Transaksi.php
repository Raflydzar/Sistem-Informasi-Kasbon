<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kas_id',
        'unit_id',
        'tanggal',
        'deskripsi',
        'no_nota',
        'volume',
        'kode_unit',
        'jenis',
        'nominal',
        'saldo',
        'kategori_utama',
        'sub_kategori'
    ];
    public function kas()
    {
        return $this->belongsTo(Kas::class);
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
