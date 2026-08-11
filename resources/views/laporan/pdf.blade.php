<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kasbon</title>
    <style>
    /* Margin Kertas Print (Atas/Bawah: 1.5cm, Kiri/Kanan: 1.2cm) */
    @page { 
        margin: 1.5cm 1.2cm; 
    }
    
    body { 
        font-family: Arial, sans-serif; 
        font-size: 10px; 
        color: #000; 
    }
    
    /* Tabel Utama */
    .main-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 20px; 
    }
    .main-table th, .main-table td { 
        border: 1px solid #000; 
        padding: 4px 6px; 
        font-size: 9px; 
    }
    
    .header-title { font-weight: bold; font-size: 11px; text-transform: uppercase; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .font-bold { font-weight: bold; }
    
    /* Tabel Tanda Tangan */
    .signature-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 25px; 
        border: none; 
    }
    .signature-table td { 
        border: none !important; 
        padding: 0; 
        vertical-align: top; 
    }
</style>
</head>
<body>

    <!-- Tabel Utama -->
    <table class="main-table">
       <!-- Baris Header Judul & Saldo -->
        <tr>
            <td colspan="2" class="header-title font-bold">KASBON</td>
            <td colspan="2" class="header-title font-bold text-center">
                {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('j F Y') }} SD {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('j F Y') }}
            </td>
            <td colspan="2" class="text-right font-bold">
                SALDO AWAL : Rp {{ number_format($saldoAwalHeader, 0, ',', '.') }}<br>
                SALDO AKHIR : Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
            </td>
        </tr>

        <!-- Table Column Headers -->
        <tr style="background-color: #f5f5f5;">
            <th width="12%" class="text-center font-bold">TANGGAL</th>
            <th width="40%" class="text-center font-bold">DESKRIPSI</th>
            <th width="12%" class="text-center font-bold">KODE UNIT</th>
            <th width="12%" class="text-center font-bold">DEBIT</th>
            <th width="12%" class="text-center font-bold">KREDIT</th>
            <th width="12%" class="text-center font-bold">SALDO</th>
        </tr>

        <!-- Row Summary Atas -->
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right font-bold">
                {{ $totalDebit > 0 ? 'Rp '.number_format($totalDebit, 0, ',', '.') : '' }}
            </td>
            <td></td>
            <td class="text-right font-bold">
                Rp {{ number_format($saldoAwal + $totalDebit, 0, ',', '.') }}
            </td>
        </tr>

        <!-- Detail Data Transaksi -->
        @foreach($transaksis as $item)
            <tr>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td class="text-left font-bold" style="text-transform: uppercase;">{{ $item->deskripsi }}</td>
                <td class="text-center">{{ $item->kode_unit }}</td>
                <td class="text-right">
                    {{ $item->jenis == 'debit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '' }}
                </td>
                <td class="text-right">
                    {{ $item->jenis == 'kredit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '' }}
                </td>
                <td class="text-right font-bold">
                    Rp {{ number_format($item->running_saldo, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach

        <!-- Baris Kosong Tambahan -->
        @for($i = count($transaksis); $i < 10; $i++)
            <tr>
                <td style="height: 15px;"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">Rp -</td>
            </tr>
        @endfor
    </table>

    <!-- Tanda Tangan (Menggunakan Tabel agar Rata Pinggir Presisi) -->
    <table class="signature-table">
        <tr>
            <td style="width: 50%; text-align: left;">
                <p style="margin: 0;">Dibuat Oleh,</p>
                <div style="height: 45px;"></div>
                <p class="font-bold" style="margin: 0;">Riszki Nanda S</p>
                <p style="margin: 0; color: #444;">FM General Affair</p>
            </td>
            <td style="width: 50%; text-align: right;">
                <p style="margin: 0;">Diketahui Oleh,</p>
                <div style="height: 45px;"></div>
                <p class="font-bold" style="margin: 0;">Febrian Adi P</p>
                <p style="margin: 0; color: #444;">SPV General Affair</p>
            </td>
        </tr>
    </table>

</body>
</html>