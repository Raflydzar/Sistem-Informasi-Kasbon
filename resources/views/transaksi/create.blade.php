<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight">
            {{ $tipe === 'masuk' ? 'Pengisian Saldo Kas (Debit)' : 'Form Pengeluaran Kas (Kredit)' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
                
                <form action="{{ route('transaksi.store') }}" method="POST" 
                      x-data="{ 
                          subKategori: '{{ request('sub_kategori', '') }}' 
                      }" 
                      class="space-y-4">
                    @csrf
                    
                    <input type="hidden" name="jenis" value="{{ $tipe === 'masuk' ? 'debit' : 'kredit' }}">

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>

                    @if($tipe === 'keluar')
                        <!-- Kategori Transaksi (Gabungan Kasbon & Petty Cash) -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Kategori Transaksi</label>
                            <select name="sub_kategori" x-model="subKategori" required 
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="" disabled class="dark:bg-slate-900">-- Pilih Kategori --</option>
                                <option value="kasbon_umum" class="dark:bg-slate-900">Kasbon Umum (Lainnya)</option>
                                <option value="building_material" class="dark:bg-slate-900">Building Material (Material/Bangunan)</option>
                                <option value="fuel" class="dark:bg-slate-900">Fuel (BBM Unit)</option>
                                <option value="spare_part_vehicle" class="dark:bg-slate-900">Spare Part Vehicle (Suku Cadang Unit)</option>
                                <option value="electrical" class="dark:bg-slate-900">Electrical (Kelistrikan)</option>
                                <option value="water" class="dark:bg-slate-900">Water (Air Bersih/Konsumsi)</option>
                                <option value="office_equipment" class="dark:bg-slate-900">Office Equipment (Peralatan Kantor)</option>
                                <option value="mess_equipment" class="dark:bg-slate-900">Mess Equipment (Peralatan Mess)</option>
                            </select>
                        </div>

                        <!-- Pilihan Unit -->
                        <div x-show="['kasbon_umum', 'fuel', 'spare_part_vehicle'].includes(subKategori)" x-cloak x-transition>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">
                                Unit Terkait <span x-show="['kasbon_umum', 'fuel', 'spare_part_vehicle'].includes(subKategori)" class="text-rose-500">*</span>
                            </label>
                            <select name="kode_unit" 
                                    :required="['fuel', 'spare_part_vehicle'].includes(subKategori)"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="" class="dark:bg-slate-900">-- Pilih Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900">{{ $u->kode_unit }} - {{ $u->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Deskripsi / Keterangan</label>
                        <input type="text" name="deskripsi" placeholder="{{ $tipe === 'masuk' ? 'Contoh: Pengisian Kas Awal' : 'Contoh: Pembelian Semen' }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>

                    <!-- No Nota & Volume Liter -->
                    <div class="grid grid-cols-1" :class="subKategori === 'fuel' ? 'sm:grid-cols-2 gap-4' : ''">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">No. Nota (Opsional)</label>
                            <input type="text" name="no_nota" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <!-- Input Fleksibel: Volume (Liter) atau Jumlah (Qty) -->
                        <div x-show="['fuel', 'building_material', 'spare_part_vehicle, 'electrical','office_equipment','mess_equipment'].includes(subKategori)" x-cloak x-transition>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">
                                <span x-text="subKategori === 'fuel' ? 'Volume (Liter)' : 'Jumlah / Qty'"></span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   :step="subKategori === 'fuel' ? '0.01' : '1'" 
                                   :min="subKategori === 'fuel' ? '0.01' : '1'" 
                                   name="volume" 
                                   :placeholder="subKategori === 'fuel' ? 'Contoh: 15.5' : 'Contoh: 10'" 
                                   :required="['fuel', 'building_material', 'electrical'].includes(subKategori)"
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                    </div>

                    <!-- Nominal (Rp) -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Nominal (Rp)</label>
                        <input type="text" id="nominal_display" placeholder="Contoh: 250.000" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 outline-none transition">
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

    <!-- Script Formatter Nominal Rupiah -->
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