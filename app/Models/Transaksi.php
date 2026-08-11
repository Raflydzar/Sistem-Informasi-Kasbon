<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'kas_id', 'unit_id','tanggal',
        'deskripsi','kode_unit','jenis','nominal', 'saldo'
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
