<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <!-- Judul Halaman Dinamis -->
                <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
                    @if(!$subKategori)
                        Data Semua Transaksi
                    @else
                        Kasbon / {{ ucwords(str_replace('_', ' ', $subKategori)) }}
                    @endif
                </h2>
                <p class="text-xs text-slate-400 mt-1">Buku kas terpadu dan mutasi saldo berjalan.</p>
            </div>

            <!-- Tombol Aksi Tambah Transaksi -->
            <div class="flex items-center gap-2.5">
                <a href="{{ route('transaksi.create', ['tipe' => 'masuk']) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 dark:hover:bg-emerald-900/40 border border-emerald-200/60 dark:border-emerald-800/60 rounded-xl transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Isi Saldo Kas
                </a>

                <!-- Parameter kategori_utama dihapus dari link tambah pengeluaran -->
                <a href="{{ route('transaksi.create', ['tipe' => 'keluar', 'sub_kategori' => $subKategori]) }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition shadow-sm shadow-blue-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengeluaran
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Card Ringkasan Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Saldo Awal -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Saldo Awal</span>
                        <p class="text-2xl font-bold tracking-tight text-blue-600 dark:text-blue-400 mt-1">
                            Rp {{ number_format($saldoAwal ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m-11 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <!-- Saldo Akhir -->
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Saldo Akhir</span>
                        <p class="text-2xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-1">
                            Rp {{ number_format($saldoAkhir ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <!-- 2. Tabel Riwayat Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Daftar Transaksi</h3>
                    <span class="text-xs text-slate-400">Total Data: {{ $transaksis->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/50 dark:bg-slate-800/50 border-y border-slate-200 dark:border-slate-800">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs w-24">Tgl</th>
                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs">Kategori</th>
                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs hidden md:table-cell w-32">Nota</th>

                                <!-- Kolom Unit: Muncul untuk Kasbon Umum, Fuel, dan Spare Part, ATAU jika melihat Semua Kasbon -->
                                @if(in_array($subKategori, ['kasbon_umum', 'fuel', 'spare_part_vehicle']) || !$subKategori)
                                    <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs hidden sm:table-cell w-28">Unit</th>
                                @endif

                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs">Deskripsi</th>

                                <!-- Kolom Volume/Qty: Muncul untuk Fuel, Material, Electrical, ATAU jika melihat Semua Kasbon -->
                                @if(in_array($subKategori, ['fuel', 'building_material', 'electrical']) || !$subKategori)
                                    <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs hidden sm:table-cell w-24 text-center">
                                        {{ $subKategori === 'fuel' ? 'Vol (L)' : 'Qty' }}
                                    </th>
                                @endif

                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs text-right w-36">Nominal</th>
                                <th class="px-4 py-3 font-semibold text-slate-600 dark:text-slate-300 text-xs text-right w-36">Saldo Kas</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @forelse($transaksis as $t)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d M y') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs whitespace-nowrap">
                                        <!-- Penyesuaian nama kategori untuk mengakomodasi data lama dari database -->
                                        @if($t->kategori_utama === 'debit_kas' || $t->kategori_utama === 'isi_saldo' || $t->jenis === 'debit')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                Isi Saldo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 ring-1 ring-inset ring-slate-500/20">
                                                {{ ucwords(str_replace('_', ' ', $t->sub_kategori && $t->sub_kategori !== '-' ? $t->sub_kategori : $t->kategori_utama)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 hidden md:table-cell font-mono">
                                        {{ $t->no_nota }}
                                    </td>

                                    <!-- Isi Kolom Unit -->
                                    @if(in_array($subKategori, ['kasbon_umum', 'fuel', 'spare_part_vehicle']) || !$subKategori)
                                        <td class="px-4 py-3 text-xs hidden sm:table-cell whitespace-nowrap">
                                            @if($t->kode_unit && $t->kode_unit !== '-')
                                                <span class="font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
                                                    {{ $t->kode_unit }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                    @endif

                                    <td class="px-4 py-3 text-sm text-slate-800 dark:text-slate-200">
                                        {{ $t->deskripsi }}
                                    </td>

                                    <!-- Isi Kolom Volume -->
                                    @if(in_array($subKategori, ['fuel', 'building_material', 'electrical']) || !$subKategori)
                                        <td class="px-4 py-3 text-xs hidden sm:table-cell text-center text-slate-600 dark:text-slate-300 whitespace-nowrap font-medium">
                                            @if($t->volume)
                                                {{ $t->sub_kategori === 'fuel' ? number_format($t->volume, 1) . ' L' : number_format($t->volume, 0) . ' Pcs' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif

                                    <!-- Nominal -->
                                    <td class="px-4 py-3 text-sm font-medium text-right whitespace-nowrap {{ $t->jenis === 'debit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $t->jenis === 'debit' ? '+' : '-' }}Rp {{ number_format($t->nominal, 0, ',', '.') }}
                                    </td>

                                    <!-- Saldo Berjalan -->
                                    <td class="px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 text-right whitespace-nowrap font-mono">
                                        Rp {{ number_format($t->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center text-slate-400 text-xs">
                                        Belum ada transaksi yang tercatat pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Navigasi Halaman (Pagination) -->
                @if($transaksis->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $transaksis->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>