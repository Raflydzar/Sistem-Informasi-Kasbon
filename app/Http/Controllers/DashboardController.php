<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Saldo Awal dari database Setting (default 0)
        $saldoAwal = (float) (Setting::where('key', 'saldo_awal')->value('value') ?? 0);

        // 2. Hitung Mutasi Kas & Saldo Akhir
        $totalDebit = Transaksi::where('jenis', 'debit')->sum('nominal');
        $totalKredit = Transaksi::where('jenis', 'kredit')->sum('nominal');
        $totalSaldo = $saldoAwal + $totalDebit - $totalKredit;

        $jumlahKasbon = Transaksi::where('jenis', 'kredit')->count();
        $transaksiHariIni = Transaksi::whereDate('tanggal', today())->count();

        // 3. Data Grafik Debit vs Kredit Bulanan
        $grafikData = Transaksi::selectRaw('MONTH(tanggal) as bulan, jenis, SUM(nominal) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bulan', 'jenis')
            ->get();

        $debitBulanan = array_fill(1, 12, 0);
        $kreditBulanan = array_fill(1, 12, 0);

        foreach ($grafikData as $data) {
            if ($data->jenis === 'debit') {
                $debitBulanan[$data->bulan] = (float) $data->total;
            } else {
                $kreditBulanan[$data->bulan] = (float) $data->total;
            }
        }

        // 4. Data Grafik Frekuensi Transaksi
        $mingguanData = Transaksi::selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subDays(6))
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $bulananData = Transaksi::selectRaw('MONTH(tanggal) as bln, COUNT(*) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bln')->pluck('total', 'bln')->toArray();

        $tahunanData = Transaksi::selectRaw('YEAR(tanggal) as thn, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subYears(3))
            ->groupBy('thn')->pluck('total', 'thn')->toArray();

        // 5. Filter Tabel Riwayat Kasbon
        $query = Transaksi::query();
        $filter = $request->get('filter', '1tahun');

        if ($filter === 'minggu') {
            $query->where('tanggal', '>=', now()->subDays(6));
        } elseif ($filter === 'bulan') {
            $query->whereMonth('tanggal', date('m'))
                  ->whereYear('tanggal', date('Y'));
        } elseif ($filter === 'tahun') {
            $query->whereYear('tanggal', date('Y'));
        } else {
            $query->where('tanggal', '>=', now()->subYear());
        }

        $riwayatKasbon = $query->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard', compact(
            'saldoAwal', 'totalSaldo', 'totalDebit', 'totalKredit', 'jumlahKasbon', 'transaksiHariIni',
            'debitBulanan', 'kreditBulanan', 'mingguanData', 'bulananData', 'tahunanData', 
            'riwayatKasbon', 'filter'
        ));
    }

    // Method untuk menyimpan / mengupdate nilai Saldo Awal
   public function updateSaldoAwal(Request $request)
{
    $request->validate([
        'saldo_awal' => 'required|numeric|min:0',
    ]);

    // 1. Simpan ke Setting
    Setting::updateOrCreate(
        ['key' => 'saldo_awal'],
        ['value' => $request->saldo_awal]
    );

    // 2. Catat otomatis ke tabel Transaksi sebagai Debit pertama
    Transaksi::updateOrCreate(
        ['deskripsi' => 'Saldo Awal Sistem'],
        [
            'tanggal' => now()->startOfYear()->toDateString(),
            'kode_unit' => '-',
            'jenis' => 'debit',
            'nominal' => $request->saldo_awal,
            'saldo' => $request->saldo_awal,
        ]
    );

    return back()->with('success', 'Saldo awal berhasil diperbarui dan dicatat ke transaksi!');
}
}