<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['kode_unit', 'nama_unit'];
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
