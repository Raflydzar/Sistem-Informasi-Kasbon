<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ $tipe === 'masuk' ? 'Pengisian Saldo Kas (Debit)' : 'Form Pengajuan Kasbon (Kredit)' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                
                <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Simpan otomatis tipe debit/kredit -->
                    <input type="hidden" name="jenis" value="{{ $tipe === 'masuk' ? 'debit' : 'kredit' }}">

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    </div>

                    <!-- Pilih Unit (Khusus Kasbon/Kredit) -->
                    @if($tipe === 'keluar')
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Pilih Unit</label>
                        <select name="kode_unit" required 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            <option value="" class="dark:bg-slate-900">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900">{{ $u->kode_unit }} - {{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Deskripsi / Keperluan</label>
                        <input type="text" name="deskripsi" placeholder="{{ $tipe === 'masuk' ? 'Contoh: Pengisian Kas Operasional' : 'Contoh: BBM - NAMA' }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Kategori Transaksi</label>
                        <select name="kategori" required 
                                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                            <option value="kasbon" class="dark:bg-slate-900">Kasbon Karyawan</option>
                            <option value="reimburse" class="dark:bg-slate-900">Reimburse</option>
                            <option value="rtk" class="dark:bg-slate-900">Pembelian RTK (Rumah Tangga Kantor)</option>
                            <option value="operasional" class="dark:bg-slate-900">Operasional (Tambal Ban, dll)</option>
                            <option value="lainnya" class="dark:bg-slate-900">Lain-lain</option>
                        </select>
                    </div>

                    <!-- No Nota & Volume (L) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">No. Nota (Opsional)</label>
                            <input type="text" name="no_nota" placeholder="Contoh: NT-001" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Volume Liter (Opsional)</label>
                            <input type="number" step="0.01" name="volume" placeholder="Contoh: 15.5" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        </div>
                    </div>

                    <!-- Nominal -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Nominal (Rp)</label>
                        <input type="text" id="nominal_display" placeholder="Contoh: 250.000" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <input type="hidden" name="nominal" id="nominal_real">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 text-sm font-medium text-white {{ $tipe === 'masuk' ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-500/20' : 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/20' }} rounded-xl transition shadow-sm">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

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