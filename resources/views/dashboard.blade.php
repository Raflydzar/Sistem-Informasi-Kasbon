<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div x-data="{ openSaldoModal: false }" class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Notifikasi Sukses -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-800 dark:text-emerald-300 text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 text-lg leading-none">&times;</button>
                </div>
            @endif

            <!-- 1. Header & Tombol Aksi -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Dashboard Kasbon
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Ringkasan keuangan, analitik grafik, dan mutasi saldo kas.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('transaksi.create', ['tipe' => 'masuk']) }}" 
                       class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Isi Saldo (Debit)
                    </a>
                    <a href="{{ route('transaksi.create', ['tipe' => 'keluar']) }}" 
                       class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition shadow-sm shadow-blue-500/20">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8-8v16"/></svg>
                        Ajukan Kasbon
                    </a>
                </div>
            </div>

            <!-- 2. Ringkasan Kartu Metrik (6 Kartu) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                
                <!-- Saldo Awal (Dapat Diedit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Saldo Awal</span>
                        <button @click="openSaldoModal = true" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition" title="Ubah Saldo Awal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    </div>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-slate-700 dark:text-slate-200 mt-2">
                            Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Saldo dasar</span>
                    </div>
                </div>

                <!-- Total Kas Masuk (Debit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Debit</span>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-blue-600 dark:text-blue-400 mt-2">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Kas masuk</span>
                    </div>
                </div>

                <!-- Total Kas Keluar (Kredit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kredit</span>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-2">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Kasbon keluar</span>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Saldo Akhir</span>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-2">
                            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Kas saat ini</span>
                    </div>
                </div>

                <!-- Pengajuan Kasbon -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kasbon</span>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-amber-600 dark:text-amber-400 mt-2">
                            {{ $jumlahKasbon }} <span class="text-xs font-normal text-slate-400">Trx</span>
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Kasus tercatat</span>
                    </div>
                </div>

                <!-- Transaksi Hari Ini -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hari Ini</span>
                    <div>
                        <p class="text-lg font-bold tracking-tight text-indigo-600 dark:text-indigo-400 mt-2">
                            {{ $transaksiHariIni }} <span class="text-xs font-normal text-slate-400">Trx</span>
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 block">Aktivitas harian</span>
                    </div>
                </div>

            </div>

            <!-- 3. Bagian Grafik Analitik -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Debit vs Kredit Bulanan -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">
                            Arus Kas Bulanan Tahun {{ date('Y') }}
                        </h2>
                        <span class="text-xs text-slate-400">Debit vs Kredit</span>
                    </div>
                    <div class="h-64">
                        <canvas id="chartArusKas"></canvas>
                    </div>
                </div>

                <!-- Grafik Frekuensi Transaksi -->
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white mb-1">
                            Aktivitas Transaksi
                        </h2>
                        <p class="text-xs text-slate-400 mb-4">Frekuensi transaksi tahun {{ date('Y') }}</p>
                        <div class="h-56">
                            <canvas id="chartFrekuensi"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Tabel Riwayat Kasbon & Filter -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <!-- Header Tabel & Filter Bar -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">
                            Riwayat Mutasi & Kasbon
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi berdasarkan rentang waktu terpilih.</p>
                    </div>

                   <!-- Filter Rentang Waktu -->
<div class="flex flex-wrap items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-medium">
    
    <!-- 7 Hari -->
    <a href="{{ route('dashboard', ['filter' => 'minggu']) }}" 
       class="px-3 py-1.5 rounded-lg transition {{ $filter === 'minggu' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
        7 Hari
    </a>

    <!-- Dropdown Per Bulan -->
    <div class="relative">
        @php
            $daftarBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
        @endphp
        <select onchange="window.location.href = this.value" 
                class="appearance-none bg-transparent cursor-pointer pl-3 pr-7 py-1.5 rounded-lg transition border-0 text-xs font-medium focus:ring-0 focus:outline-none {{ $filter === 'bulan' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
            <option value="" disabled {{ $filter !== 'bulan' ? 'selected' : '' }} class="dark:bg-slate-900 text-slate-400">
                Pilih Bulan
            </option>
            @foreach($daftarBulan as $num => $nama)
                <option value="{{ route('dashboard', ['filter' => 'bulan', 'bulan' => $num]) }}" 
                        {{ $filter === 'bulan' && $selectedBulan === $num ? 'selected' : '' }}
                        class="dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                    {{ $nama }}
                </option>
            @endforeach
        </select>
        <!-- Ikon Panah Dropdown -->
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-slate-400">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>

    <!-- Tahun Ini -->
    <a href="{{ route('dashboard', ['filter' => 'tahun']) }}" 
       class="px-3 py-1.5 rounded-lg transition {{ $filter === 'tahun' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
        Tahun Ini
    </a>

    <!-- 1 Tahun Terakhir -->
    <a href="{{ route('dashboard', ['filter' => '1tahun']) }}" 
       class="px-3 py-1.5 rounded-lg transition {{ $filter === '1tahun' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
        1 Tahun Terakhir
    </a>
</div>
                </div>

                <!-- Isi Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-5 py-3.5">Tanggal</th>
                                <th class="px-5 py-3.5">Jenis</th>
                                <th class="px-5 py-3.5">Deskripsi</th>
                                <th class="px-5 py-3.5">Nota</th>
                                <th class="px-5 py-3.5 text-center">Vol (L)</th>
                                <th class="px-5 py-3.5 text-right">Nominal</th>
                                <th class="px-5 py-3.5 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($riwayatKasbon as $trx)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($trx->jenis === 'debit')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                                Debit (Masuk)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400">
                                                Kredit (Kasbon)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-slate-800 dark:text-slate-200">
                                        {{ $trx->deskripsi ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $trx->no_nota ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs text-slate-500">
                                        {{ $trx->volume ? $trx->volume . ' L' : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-semibold {{ $trx->jenis === 'debit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $trx->jenis === 'debit' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-medium text-slate-700 dark:text-slate-300">
                                        Rp {{ number_format($trx->saldo ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center text-slate-400 text-sm">
                                        Tidak ada riwayat transaksi pada filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                @if ($riwayatKasbon->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $riwayatKasbon->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- 5. Modal Pop-Up Edit Saldo Awal -->
        <div x-show="openSaldoModal" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            
            <div @click.away="openSaldoModal = false" 
                 class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 border border-slate-200 dark:border-slate-800 shadow-xl space-y-4">
                
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ubah Saldo Awal</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Nominal ini menjadi saldo dasar perhitungan kasbon.</p>
                </div>

                <form action="{{ route('saldo.awal.update') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Input Tanggal -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Tanggal Saldo Awal</label>
                        <input type="date" name="tanggal" value="{{ $tanggalSaldoAwal ?? date('Y-m-d') }}" required
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>

                    <!-- Input Nominal -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Nominal (Rp)</label>
                        <input type="number" name="saldo_awal" value="{{ $saldoAwal }}" required min="0"
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="openSaldoModal = false" class="px-4 py-2 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-medium transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- 6. Script Inisialisasi Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
           // Inisialisasi Grafik Arus Kas (Gaya Garis Lurus Keuangan)
const ctxArusKas = document.getElementById('chartArusKas').getContext('2d');

new Chart(ctxArusKas, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [
            {
                label: 'Debit (Masuk)',
                data: {!! json_encode(array_values($debitBulanan)) !!},
                backgroundColor: '#10b981',
                borderRadius: 4,
                order: 2
            },
            {
                label: 'Kredit (Kasbon)',
                data: {!! json_encode(array_values($kreditBulanan)) !!},
                backgroundColor: '#f43f5e',
                borderRadius: 4,
                order: 2
            },
            {
                label: 'Tren Debit',
                data: {!! json_encode(array_values($debitBulanan)) !!},
                type: 'line',
                borderColor: '#059669',
                backgroundColor: 'transparent',
                fill: false,
                borderWidth: 2.5,
                tension: 0, // Membuat garis lurus bersudut, tidak melengkung
                pointRadius: 4,
                pointBackgroundColor: '#059669',
                order: 1
            },
            {
                label: 'Tren Kredit',
                data: {!! json_encode(array_values($kreditBulanan)) !!},
                type: 'line',
                borderColor: '#e11d48',
                backgroundColor: 'transparent',
                fill: false,
                borderWidth: 2.5,
                tension: 0,
                pointRadius: 4,
                pointBackgroundColor: '#e11d48',
                order: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                }
            }
        }
    }
});

            // Inisialisasi Grafik Frekuensi Transaksi Bulanan
            const frekuensiDataObj = {!! json_encode($bulananData) !!};
            const frekuensiValues = Array.from({length: 12}, (_, i) => frekuensiDataObj[i + 1] || 0);

            const ctxFrekuensi = document.getElementById('chartFrekuensi').getContext('2d');
            new Chart(ctxFrekuensi, {
                type: 'line',
                data: {
                    labels: bulanLabels,
                    datasets: [{
                        label: 'Total Aktivitas',
                        data: frekuensiValues,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
</x-app-layout>