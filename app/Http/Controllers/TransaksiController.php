<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Unit; // Import Model Unit
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap parameter kategori dari sidebar (misal: 'kasbon' atau 'lainnya')
        $kategori = $request->get('kategori', 'kasbon');

        $query = Transaksi::query();

        // Filter berdasarkan kategori
        if ($kategori === 'kasbon') {
            $query->where('kategori', 'kasbon');
        } elseif ($kategori === 'lainnya') {
            $query->where('kategori', '!=', 'kasbon');
        }

        $transaksis = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->paginate(15)->withQueryString();
        $units = Unit::all(); // Ambil semua data unit
      
        // Mengambil nominal dari transaksi paling awal yang diinput
        $firstTransaksi = Transaksi::orderBy('tanggal', 'asc')->orderBy('id', 'asc')->first();
        $saldoAwal = $firstTransaksi ? $firstTransaksi->nominal : 0;
        
        $lastTransaksi = Transaksi::latest('id')->first();
        $saldoAkhir = $lastTransaksi ? $lastTransaksi->saldo : $saldoAwal;

        return view('transaksi.index', compact('transaksis', 'units', 'saldoAwal', 'saldoAkhir', 'kategori'));
    }

    public function create(Request $request)
    {
        // Tangkap parameter 'tipe' dari URL (default: 'keluar')
        $tipe = $request->query('tipe', 'keluar');
        $units = Unit::all();

        return view('transaksi.create', compact('tipe', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'kategori'  => 'required|string', // Validasi kategori transaksi
            'jenis'     => 'required|in:debit,kredit',
            'nominal'   => 'required|numeric|gt:0',
            'kode_unit' => $request->jenis === 'kredit' ? 'required|exists:units,kode_unit' : 'nullable',
            'no_nota'   => 'nullable|string',
            'volume'    => 'nullable|numeric',
        ]);

        $lastTransaksi = Transaksi::latest('id')->first();
        $saldoTerakhir = $lastTransaksi ? $lastTransaksi->saldo : 0;

        $saldoBaru = $request->jenis === 'debit'
            ? $saldoTerakhir + $request->nominal
            : $saldoTerakhir - $request->nominal;

        Transaksi::create([
            'tanggal'   => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'kategori'  => $request->kategori, // Simpan kategori yang dipilih
            'no_nota'   => $request->no_nota ?? '-',
            'volume'    => $request->volume ?? null,
            'kode_unit' => $request->jenis === 'debit' ? '-' : $request->kode_unit,
            'jenis'     => $request->jenis,
            'nominal'   => $request->nominal,
            'saldo'     => $saldoBaru,
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil disimpan!');
    }
}