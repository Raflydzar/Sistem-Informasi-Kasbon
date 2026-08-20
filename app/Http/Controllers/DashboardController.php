<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $saldoAwal = 0;
        $lastTransaksi = Transaksi::latest('id')->first();
        $totalSaldo = $lastTransaksi ? $lastTransaksi->saldo : $saldoAwal;

        $totalDebit = Transaksi::where('jenis', 'debit')->sum('nominal');
        $totalKredit = Transaksi::where('jenis', 'kredit')->sum('nominal');
        $jumlahKasbon = Transaksi::where('jenis', 'kredit')->count();
        $transaksiHariIni = Transaksi::whereDate('tanggal', today())->count();

        // 1. Data Grafik Debit vs Kredit Bulanan
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

        // 2. Data Grafik Analytics Frekuensi Transaksi
        $mingguanData = Transaksi::selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subDays(6))
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $bulananData = Transaksi::selectRaw('MONTH(tanggal) as bln, COUNT(*) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bln')->pluck('total', 'bln')->toArray();

        $tahunanData = Transaksi::selectRaw('YEAR(tanggal) as thn, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subYears(3))
            ->groupBy('thn')->pluck('total', 'thn')->toArray();

        // 3. Filter Tabel Riwayat Kasbon
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
            // Default 1 tahun terakhir
            $query->where('tanggal', '>=', now()->subYear());
        }

        $riwayatKasbon = $query->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString(); // Mempertahankan parameter filter saat berpindah halaman

        return view('dashboard', compact(
            'totalSaldo', 'totalDebit', 'totalKredit', 'jumlahKasbon', 'transaksiHariIni',
            'debitBulanan', 'kreditBulanan', 'mingguanData', 'bulananData', 'tahunanData', 
            'riwayatKasbon', 'filter'
        ));
    }
}