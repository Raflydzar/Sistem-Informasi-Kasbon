<x-app-layout>
    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Header & Quick Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Ringkasan Kasbon
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Monitoring arus kas, saldo tersedia, dan pengajuan kasbon.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('transaksi.create', ['tipe' => 'masuk']) }}" 
                       class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition shadow-xs">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Isi Saldo
                    </a>
                    <a href="{{ route('transaksi.create', ['tipe' => 'keluar']) }}" 
                       class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition shadow-sm shadow-blue-500/20">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m8-8v16"/></svg>
                        Ajukan Kasbon
                    </a>
                </div>
            </div>

            <!-- 2. Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Saldo Akhir -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider">Saldo Akhir</span>
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                        </p>
                        <span class="text-xs text-slate-400 mt-1 block">Kas tersedia saat ini</span>
                    </div>
                </div>

                <!-- Total Kas Masuk (Debit) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider">Total Kas Masuk</span>
                        <div class="p-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-blue-600 dark:text-blue-400">
                            Rp {{ number_format($totalDebit ?? 0, 0, ',', '.') }}
                        </p>
                        <span class="text-xs text-slate-400 mt-1 block">Akumulasi pengisian saldo</span>
                    </div>
                </div>

                <!-- Total Kas Keluar (Kasbon) -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider">Total Kasbon</span>
                        <div class="p-2 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400">
                            Rp {{ number_format($totalKredit ?? 0, 0, ',', '.') }}
                        </p>
                        <span class="text-xs text-slate-400 mt-1 block">Akumulasi pinjaman kasbon</span>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between text-slate-500 dark:text-slate-400 mb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider">Total Aktivitas</span>
                        <div class="p-2 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold tracking-tight text-amber-600 dark:text-amber-400">
                            {{ $totalTransaksi ?? 0 }} <span class="text-sm font-normal text-slate-500">Record</span>
                        </p>
                        <span class="text-xs text-slate-400 mt-1 block">Riwayat kas masuk & kasbon</span>
                    </div>
                </div>

            </div>

            <!-- 3. Tabel Riwayat Transaksi Terbaru -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                        Transaksi Terbaru
                    </h2>
                    <a href="{{ route('transaksi.index') }}" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Jenis</th>
                                <th class="px-6 py-3.5">Keterangan</th>
                                <th class="px-6 py-3.5 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($recentTransaksi ?? [] as $trx)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($trx->tanggal ?? $trx->created_at)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (($trx->jenis ?? '') === 'debit' || ($trx->jenis_transaksi ?? '') === 'masuk')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                                Kas Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-400">
                                                Kasbon
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-800 dark:text-slate-200">
                                        {{ $trx->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ (($trx->jenis ?? '') === 'debit' || ($trx->jenis_transaksi ?? '') === 'masuk') ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ (($trx->jenis ?? '') === 'debit' || ($trx->jenis_transaksi ?? '') === 'masuk') ? '+' : '-' }} Rp {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 text-sm">
                                        Belum ada aktivitas transaksi yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>