<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            Laporan Transaksi Kas
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Filter Box Dinamis -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm"
                 x-data="{ kategoriUtama: '{{ request('kategori_utama', '') }}' }">
                <form method="GET" action="{{ route('laporan.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-end">
                        <!-- Tanggal Awal -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" value="{{ $tglAwal }}" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <!-- Tanggal Akhir -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <!-- Kategori Transaksi (Satu Pintu) -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Kategori Transaksi</label>
                            <select name="sub_kategori" 
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="" class="dark:bg-slate-900">-- Semua Kategori --</option>
                                <option value="kasbon_umum" class="dark:bg-slate-900" {{ request('sub_kategori') === 'kasbon_umum' ? 'selected' : '' }}>Kasbon Umum (Lainnya)</option>
                                <option value="building_material" class="dark:bg-slate-900" {{ request('sub_kategori') === 'building_material' ? 'selected' : '' }}>Building Material</option>
                                <option value="fuel" class="dark:bg-slate-900" {{ request('sub_kategori') === 'fuel' ? 'selected' : '' }}>Fuel (BBM Unit)</option>
                                <option value="spare_part_vehicle" class="dark:bg-slate-900" {{ request('sub_kategori') === 'spare_part_vehicle' ? 'selected' : '' }}>Spare Part Vehicle</option>
                                <option value="electrical" class="dark:bg-slate-900" {{ request('sub_kategori') === 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="water" class="dark:bg-slate-900" {{ request('sub_kategori') === 'water' ? 'selected' : '' }}>Water</option>
                                <option value="office_equipment" class="dark:bg-slate-900" {{ request('sub_kategori') === 'office_equipment' ? 'selected' : '' }}>Office Equipment</option>
                                <option value="mess_equipment" class="dark:bg-slate-900" {{ request('sub_kategori') === 'mess_equipment' ? 'selected' : '' }}>Mess Equipment</option>
                            </select>
                        </div>

                        <!-- Sub-Kategori Petty Cash -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Sub Petty Cash</label>
                            <select name="sub_kategori" :disabled="kategoriUtama !== 'petty_cash'"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition disabled:opacity-40 disabled:cursor-not-allowed">
                                <option value="" class="dark:bg-slate-900">-- Semua Sub --</option>
                                <option value="building_material" class="dark:bg-slate-900" {{ request('sub_kategori') === 'building_material' ? 'selected' : '' }}>Building Material</option>
                                <option value="fuel" class="dark:bg-slate-900" {{ request('sub_kategori') === 'fuel' ? 'selected' : '' }}>Fuel (BBM)</option>
                                <option value="spare_part_vehicle" class="dark:bg-slate-900" {{ request('sub_kategori') === 'spare_part_vehicle' ? 'selected' : '' }}>Spare Part Vehicle</option>
                                <option value="electrical" class="dark:bg-slate-900" {{ request('sub_kategori') === 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="water" class="dark:bg-slate-900" {{ request('sub_kategori') === 'water' ? 'selected' : '' }}>Water</option>
                                <option value="office_equipment" class="dark:bg-slate-900" {{ request('sub_kategori') === 'office_equipment' ? 'selected' : '' }}>Office Equipment</option>
                                <option value="mess_equipment" class="dark:bg-slate-900" {{ request('sub_kategori') === 'mess_equipment' ? 'selected' : '' }}>Mess Equipment</option>
                            </select>
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Unit</label>
                            <select name="kode_unit" 
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="" class="dark:bg-slate-900">-- Semua Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900" {{ $kodeUnit == $u->kode_unit ? 'selected' : '' }}>
                                        {{ $u->kode_unit }} - {{ $u->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="submit" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl text-xs transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white font-medium rounded-xl text-xs transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak PDF
                        </a>
                    </div>
                </form>
            </div>

            <!-- 2. Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Debit Periode Ini</span>
                    <p class="text-xl font-bold tracking-tight text-blue-600 dark:text-blue-400 mt-2">
                        Rp {{ number_format($totalDebit, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Kredit Periode Ini</span>
                    <p class="text-xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-2">
                        Rp {{ number_format($totalKredit, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Selisih Kas Periode Ini</span>
                    <p class="text-xl font-bold tracking-tight text-emerald-600 dark:text-emerald-400 mt-2">
                        Rp {{ number_format($selisih, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- 3. Tabel Laporan Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-4 py-3.5">Tanggal</th>
                                <th class="px-4 py-3.5">Kategori</th>
                                <th class="px-4 py-3.5">Deskripsi</th>
                                <th class="px-4 py-3.5">Nota</th>
                                <th class="px-4 py-3.5 text-center">Vol / Qty</th>
                                <th class="px-4 py-3.5">Kode Unit</th>
                                <th class="px-4 py-3.5 text-right">Debit</th>
                                <th class="px-4 py-3.5 text-right">Kredit</th>
                                <th class="px-4 py-3.5 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($transaksis as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-4 py-3.5 whitespace-nowrap text-xs text-slate-500">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-xs">
                                        @if($item->kategori_utama === 'debit_kas' || $item->jenis === 'debit')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                Kas Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 ring-1 ring-inset ring-slate-500/20">
                                                {{ ucwords(str_replace('_', ' ', $item->sub_kategori ?? $item->kategori_utama)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-slate-800 dark:text-slate-200">
                                        {{ $item->deskripsi }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $item->no_nota ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-center text-xs text-slate-600 dark:text-slate-300 font-medium">
                                        @if($item->volume)
                                            {{ $item->sub_kategori === 'fuel' ? number_format($item->volume, 1) . ' L' : number_format($item->volume, 0) . ' Pcs' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ ($item->kode_unit && $item->kode_unit !== '-') ? $item->kode_unit : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ $item->jenis === 'debit' ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-right font-semibold text-rose-600 dark:text-rose-400">
                                        {{ $item->jenis === 'kredit' ? 'Rp ' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-right font-bold font-mono text-slate-900 dark:text-slate-100">
                                        Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-10 text-center text-slate-400 text-sm">
                                        Tidak ada data transaksi pada periode dan filter ini.
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