<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Unit;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap Parameter Kategori Utama & Sub-Kategori dari Sidebar
        $kategoriUtama = $request->get('kategori_utama', 'kasbon');
        $subKategori = $request->get('sub_kategori');

        $query = Transaksi::query();

        // 2. Filter Berdasarkan Kategori Utama
        if ($kategoriUtama) {
            $query->where('kategori_utama', $kategoriUtama);
        }

        // 3. Filter Berdasarkan Sub-Kategori (Jika ada pilihan Petty Cash spesifik)
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
            'kategoriUtama',
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
            'tanggal'        => 'required|date',
            'deskripsi'      => 'required|string',
            'jenis'          => 'required|in:debit,kredit',
            'nominal'        => 'required|numeric|gt:0',
            'kategori_utama' => 'required|string',
            'sub_kategori'   => 'nullable|string|required_if:kategori_utama,petty_cash',
            'no_nota'        => 'nullable|string',
            'volume'         => 'nullable|numeric',
            'kode_unit'      => 'nullable|string',
        ];

        // 2. Validasi Dinamis untuk Pengeluaran (Kredit)
        if ($request->jenis === 'kredit') {
            // Unit wajib untuk: Kasbon, Petty Cash BBM (Fuel), & Spare Part Vehicle
            if ($request->kategori_utama === 'kasbon' || in_array($request->sub_kategori, ['fuel', 'spare_part_vehicle'])) {
                $rules['kode_unit'] = 'required|exists:units,kode_unit';
            }

            // Volume wajib untuk Petty Cash BBM (Fuel)
            if ($request->sub_kategori === 'fuel') {
                $rules['volume'] = 'required|numeric|gt:0';
            }
        }

        $request->validate($rules);

        // 3. Hitung Mutasi Saldo Berjalan
        $lastTransaksi = Transaksi::latest('id')->first();
        $saldoTerakhir = $lastTransaksi ? $lastTransaksi->saldo : 0;

        $saldoBaru = $request->jenis === 'debit'
            ? $saldoTerakhir + $request->nominal
            : $saldoTerakhir - $request->nominal;

        // 4. Simpan Transaksi ke Database
        Transaksi::create([
            'tanggal'        => $request->tanggal,
            'deskripsi'      => $request->deskripsi,
            'kategori_utama' => $request->kategori_utama,
            'sub_kategori'   => $request->sub_kategori,
            'no_nota'        => $request->no_nota ?? '-',
            'volume'         => $request->volume ?? null,
            'kode_unit'      => $request->jenis === 'debit' ? '-' : ($request->kode_unit ?? '-'),
            'jenis'          => $request->jenis,
            'nominal'        => $request->nominal,
            'saldo'          => $saldoBaru,
        ]);

        // 2. Hitung ulang seluruh saldo berjalan dari transaksi paling awal ke akhir
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