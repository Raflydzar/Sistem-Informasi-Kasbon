<x-app-layout>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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
                        + Isi Saldo (Debit)
                    </a>
                    <a href="{{ route('transaksi.create', ['tipe' => 'keluar']) }}" 
                       class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition shadow-sm shadow-blue-500/20">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8-8v16"/></svg>
                        Ajukan Kasbon
                    </a>
                </div>
            </div>

            <!-- 2. Ringkasan Kartu Metrik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Saldo Akhir -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Saldo Akhir</span>
                    <p class="text-xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-2">
                        Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Kas saat ini</span>
                </div>

                <!-- Total Kas Masuk (Debit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Debit</span>
                    <p class="text-xl font-bold tracking-tight text-blue-600 dark:text-blue-400 mt-2">
                        Rp {{ number_format($totalDebit, 0, ',', '.') }}
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Total kas masuk</span>
                </div>

                <!-- Total Kas Keluar (Kredit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kredit</span>
                    <p class="text-xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-2">
                        Rp {{ number_format($totalKredit, 0, ',', '.') }}
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Total kasbon keluar</span>
                </div>

                <!-- Jumlah Transaksi Kasbon -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengajuan Kasbon</span>
                    <p class="text-xl font-bold tracking-tight text-amber-600 dark:text-amber-400 mt-2">
                        {{ $jumlahKasbon }} <span class="text-xs font-normal text-slate-400">Kasus</span>
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Total catatan kasbon</span>
                </div>

                <!-- Transaksi Hari Ini -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hari Ini</span>
                    <p class="text-xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400 mt-2">
                        {{ $transaksiHariIni }} <span class="text-xs font-normal text-slate-400">Transaksi</span>
                    </p>
                    <span class="text-[11px] text-slate-400 mt-1">Aktivitas per hari ini</span>
                </div>
            </div>

            <!-- 3. Bagian Grafik Analitik -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Debit vs Kredit Bulanan -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">
                            Arus Kas Bulanan (Tahun {{ date('Y') }})
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
                    <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs font-medium">
                        <a href="{{ route('dashboard', ['filter' => 'minggu']) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'minggu' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                            7 Hari
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'bulan']) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'bulan' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                            Bulan Ini
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'tahun']) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === 'tahun' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                            Tahun Ini
                        </a>
                        <a href="{{ route('dashboard', ['filter' => '1tahun']) }}" 
                           class="px-3 py-1.5 rounded-lg transition {{ $filter === '1tahun' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-white shadow-xs font-semibold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                            1 Thn Terakhir
                        </a>
                    </div>
                </div>

                <!-- Isi Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Jenis</th>
                                <th class="px-6 py-3.5">Keterangan</th>
                                <th class="px-6 py-3.5 text-right">Nominal</th>
                                <th class="px-6 py-3.5 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($riwayatKasbon as $trx)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 text-slate-800 dark:text-slate-200">
                                        {{ $trx->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold {{ $trx->jenis === 'debit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $trx->jenis === 'debit' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-slate-700 dark:text-slate-300">
                                        Rp {{ number_format($trx->saldo ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm">
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
    </div>

    <!-- 5. Script Inisialisasi Chart.js -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            // Inisialisasi Grafik Arus Kas
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
                            borderRadius: 6,
                        },
                        {
                            label: 'Kredit (Kasbon)',
                            data: {!! json_encode(array_values($kreditBulanan)) !!},
                            backgroundColor: '#f43f5e',
                            borderRadius: 6,
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