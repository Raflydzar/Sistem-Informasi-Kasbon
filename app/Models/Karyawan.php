<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $fillable = ['unit_id','nik','nama'];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
