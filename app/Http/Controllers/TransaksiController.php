<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Unit;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap Parameter Sub-Kategori dari Sidebar (Kategori Utama sudah digabung)
        $subKategori = $request->get('sub_kategori');

        $query = Transaksi::query();

        // 2. Filter Berdasarkan Sub-Kategori
        if ($subKategori) {
            $query->where('sub_kategori', $subKategori);
        }

        $transaksis = $query->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(15)
            ->withQueryString();

        $units = Unit::all();

        // Mengambil nominal dari transaksi paling awal yang diinput
        $firstTransaksi = Transaksi::orderBy('tanggal', 'asc')->orderBy('id', 'asc')->first();
        $saldoAwal = $firstTransaksi ? $firstTransaksi->nominal : 0;

        $lastTransaksi = Transaksi::latest('id')->first();
        $saldoAkhir = $lastTransaksi ? $lastTransaksi->saldo : $saldoAwal;

        return view('transaksi.index', compact(
            'transaksis',
            'units',
            'saldoAwal',
            'saldoAkhir',
            'subKategori'
        ));
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
        // 1. Aturan Validasi Dasar
        $rules = [
            'tanggal'      => 'required|date',
            'deskripsi'    => 'required|string',
            'jenis'        => 'required|in:debit,kredit',
            'nominal'      => 'required|numeric|gt:0',
            'sub_kategori' => 'nullable|string', // Kategori Utama dihapus, diganti langsung sub
            'no_nota'      => 'nullable|string',
            'volume'       => 'nullable|numeric',
            'kode_unit'    => 'nullable|string',
        ];

        // 2. Validasi Dinamis untuk Pengeluaran (Kredit)
        if ($request->jenis === 'kredit') {
            $rules['sub_kategori'] = 'required|string';

            // Unit wajib untuk: Kasbon Umum, BBM (Fuel), & Spare Part Vehicle
            if (in_array($request->sub_kategori, ['kasbon_umum', 'fuel', 'spare_part_vehicle'])) {
                $rules['kode_unit'] = 'required|exists:units,kode_unit';
            }

            // Volume (Liter) wajib untuk BBM (Fuel)
            if ($request->sub_kategori === 'fuel') {
                $rules['volume'] = 'required|numeric|gt:0';
            }
        }

        $request->validate($rules);

        // 3. Setup Variabel Kategori Otomatis (Agar database lama tidak error)
        $kategoriUtamaFix = $request->jenis === 'debit' ? 'isi_saldo' : 'kasbon';
        $subKategoriFix   = $request->jenis === 'debit' ? '-' : $request->sub_kategori;

        // 4. Hitung Mutasi Saldo Berjalan Sementara
        $lastTransaksi = Transaksi::latest('id')->first();
        $saldoTerakhir = $lastTransaksi ? $lastTransaksi->saldo : 0;

        $saldoBaru = $request->jenis === 'debit'
            ? $saldoTerakhir + $request->nominal
            : $saldoTerakhir - $request->nominal;

        // 5. Simpan Transaksi ke Database
        Transaksi::create([
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'kategori_utama' => $kategoriUtamaFix, // Diisi otomatis dari backend
            'sub_kategori'   => $subKategoriFix,   // Diisi dari form dropdown tunggal
            'no_nota'        => $request->no_nota ?? '-',
            'volume'         => $request->volume ?? null,
            'kode_unit'      => $request->jenis === 'debit' ? '-' : ($request->kode_unit ?? '-'),
            'jenis'          => $request->jenis,
            'nominal'        => $request->nominal,
            'saldo'          => $saldoBaru,
        ]);

        // 6. Hitung ulang seluruh saldo berjalan dari transaksi paling awal ke akhir (Recalculate)
        $runningBalance = 0;
        $allTransaksis = Transaksi::orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        foreach ($allTransaksis as $t) {
            if ($t->jenis === 'debit') {
                $runningBalance += $t->nominal;
            } else {
                $runningBalance -= $t->nominal;
            }
            $t->update(['saldo' => $runningBalance]);
        }

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil disimpan!');
    }
}