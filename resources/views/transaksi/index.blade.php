<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            Pencatatan Kasbon
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. Card Saldo Awal & Saldo Akhir -->
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

            <!-- 2. Form Input Transaksi Baru -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Input Transaksi Baru</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Lengkapi form transaksi kas masuk atau kasbon keluar.</p>
                </div>

                <form action="{{ route('transaksi.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-3.5">
                    @csrf
                    
                    <!-- Tanggal -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Deskripsi</label>
                        <input type="text" name="deskripsi" placeholder="Contoh: BBM - NAMA" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- No. Nota -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">No. Nota (Opsional)</label>
                        <input type="text" name="no_nota" placeholder="Contoh: NT-001" 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Volume (L) -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Volume (L)</label>
                        <input type="number" step="0.01" name="volume" placeholder="Contoh: 15.5" 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <!-- Pilih Unit -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Pilih Unit</label>
                        <select name="kode_unit" required 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="" class="dark:bg-slate-900">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900">{{ $u->kode_unit }} - {{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Jenis</label>
                        <select name="jenis" required 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="kredit" class="dark:bg-slate-900">Kredit (Pengeluaran)</option>
                            <option value="debit" class="dark:bg-slate-900">Debit (Pemasukan)</option>
                        </select>
                    </div>

                    <!-- Nominal -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1">Nominal (Rp)</label>
                        <input type="text" id="nominal_display" placeholder="Contoh: 200.000" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none">
                        <input type="hidden" name="nominal" id="nominal_real">
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white py-2 rounded-xl text-sm font-medium transition shadow-sm shadow-blue-500/20">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Tabel Riwayat Transaksi -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">Daftar Transaksi</h3>
                </div>

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
                                        {{ $item->jenis == 'debit' ? '+ Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-semibold text-rose-600 dark:text-rose-400">
                                        {{ $item->jenis == 'kredit' ? '- Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-slate-900 dark:text-slate-100">
                                        Rp {{ number_format($item->saldo, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-10 text-center text-slate-400 text-sm">
                                        Belum ada transaksi yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Formatter Rupiah -->
    <script>
        const displayInput = document.getElementById('nominal_display');
        const realInput = document.getElementById('nominal_real');

        displayInput.addEventListener('input', function(e) {
            let rawValue = this.value.replace(/[^0-9]/g, '');
            realInput.value = rawValue;

            if (rawValue) {
                this.value = new Intl.NumberFormat('id-ID').format(rawValue);
            } else {
                this.value = '';
            }
        });
    </script>
</x-app-layout>