<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::all();
        
        $tglAwal = $request->get('tgl_awal', now()->startOfMonth()->toDateString());
        $tglAkhir = $request->get('tgl_akhir', now()->toDateString());
        $kodeUnit = $request->get('kode_unit');

        $query = Transaksi::whereBetween('tanggal', [$tglAwal, $tglAkhir]);

        if ($kodeUnit) {
            $query->where('kode_unit', $kodeUnit);
        }

        $transaksis = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        $totalDebit = $transaksis->where('jenis', 'debit')->sum('nominal');
        $totalKredit = $transaksis->where('jenis', 'kredit')->sum('nominal');
        $selisih = $totalDebit - $totalKredit;

        return view('laporan.index', compact(
            'transaksis', 'units', 'tglAwal', 'tglAkhir', 'kodeUnit', 
            'totalDebit', 'totalKredit', 'selisih'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tglAwal = $request->get('tgl_awal', now()->startOfMonth()->toDateString());
        $tglAkhir = $request->get('tgl_akhir', now()->toDateString());
        $kodeUnit = $request->get('kode_unit');

        // 1. Hitung Saldo Awal sebelum periode filter
        $saldoAwal = Transaksi::whereDate('tanggal', '<', $tglAwal)
            ->selectRaw("SUM(CASE WHEN jenis = 'debit' THEN nominal ELSE -nominal END) as total")
            ->value('total') ?? 0;

        $query = Transaksi::query();
        if ($tglAwal && $tglAkhir) {
            $query->whereDate('tanggal', '>=', $tglAwal)->whereDate('tanggal', '<=', $tglAkhir);
        }
        if ($kodeUnit) {
            $query->where('kode_unit', $kodeUnit);
        }

        $transaksis = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        // 2. Fallback Saldo Awal Header jika periode baru dimulai
        $saldoAwalHeader = $saldoAwal;
        if ($saldoAwalHeader == 0) {
            $transaksiSaldoAwal = $transaksis->first(function ($item) {
                return str_contains(strtolower($item->deskripsi), 'saldo awal');
            });
            if ($transaksiSaldoAwal) {
                $saldoAwalHeader = $transaksiSaldoAwal->nominal;
            }
        }

        // 3. Hitung kalkulasi saldo berjalan (Running Balance)
        $runningSaldo = $saldoAwal;
        foreach ($transaksis as $item) {
            if ($item->jenis === 'debit') {
                $runningSaldo += $item->nominal;
            } else {
                $runningSaldo -= $item->nominal;
            }
            $item->running_saldo = $runningSaldo;
        }

        $totalDebit = $transaksis->where('jenis', 'debit')->sum('nominal');
        $totalKredit = $transaksis->where('jenis', 'kredit')->sum('nominal');
        $saldoAkhir = $runningSaldo;

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'transaksis', 'tglAwal', 'tglAkhir', 'kodeUnit', 
            'saldoAwal', 'saldoAwalHeader', 'totalDebit', 'totalKredit', 'saldoAkhir'
        ))->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan_Kasbon_' . $tglAwal . '_s_d_' . $tglAkhir . '.pdf');
    }
}