<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            Laporan Kasbon
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Filter Box -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" value="{{ $tglAwal }}" 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Unit</label>
                        <select name="kode_unit" 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="" class="dark:bg-slate-900">-- Semua Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900" {{ $kodeUnit == $u->kode_unit ? 'selected' : '' }}>
                                    {{ $u->kode_unit }} - {{ $u->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium py-2 px-4 rounded-xl text-sm transition shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" 
                           class="w-full bg-rose-600 hover:bg-rose-500 text-white font-medium py-2 px-4 rounded-xl text-sm text-center transition shadow-sm">
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

            <!-- 3. Tabel Laporan -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50/75 dark:bg-slate-800/50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-5 py-3.5">Tanggal</th>
                                <th class="px-5 py-3.5">Deskripsi</th>
                                <th class="px-5 py-3.5">Nota</th>
                                <th class="px-5 py-3.5 text-center">Volume</th>
                                <th class="px-5 py-3.5">Kode Unit</th>
                                <th class="px-5 py-3.5 text-right">Debit</th>
                                <th class="px-5 py-3.5 text-right">Kredit</th>
                                <th class="px-5 py-3.5 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($transaksis as $item)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-slate-500">
                                        {{ date('d/m/Y', strtotime($item->tanggal)) }}
                                    </td>
                                    <td class="px-5 py-4 uppercase font-medium text-slate-800 dark:text-slate-200">
                                        {{ $item->deskripsi }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $item->no_nota ?? '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-xs text-slate-500">
                                        {{ $item->volume ? $item->volume . ' L' : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-slate-500">
                                        {{ $item->kode_unit }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ $item->jenis == 'debit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-semibold text-rose-600 dark:text-rose-400">
                                        {{ $item->jenis == 'kredit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-slate-900 dark:text-slate-100">
                                        Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-slate-400 text-sm">
                                        Tidak ada data transaksi pada periode ini.
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