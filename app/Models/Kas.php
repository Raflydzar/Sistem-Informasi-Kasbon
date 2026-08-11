<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';
    protected $fillable = ['nama_kas','saldo_awal','saldo_sekarang'];
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
