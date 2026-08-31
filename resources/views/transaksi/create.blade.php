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
                          kategoriUtama: '{{ $tipe === 'masuk' ? 'debit_kas' : 'kasbon' }}', 
                          subKategori: '' 
                      }" 
                      class="space-y-4">
                    @csrf
                    
                    <!-- Simpan otomatis jenis debit/kredit -->
                    <input type="hidden" name="jenis" value="{{ $tipe === 'masuk' ? 'debit' : 'kredit' }}">

                    <!-- 1. Tanggal Transaksi -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    </div>

                    @if($tipe === 'keluar')
                        <!-- 2. Jenis Transaksi Utama -->
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Jenis Transaksi</label>
                            <select name="kategori_utama" x-model="kategoriUtama" required 
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                <option value="kasbon" class="dark:bg-slate-900">1. Kasbon</option>
                                <option value="petty_cash" class="dark:bg-slate-900">2. Petty Cash</option>
                                <option value="invoice_payment" disabled class="dark:bg-slate-900 text-slate-400 bg-slate-50 dark:bg-slate-800/50">3. Invoice Payment (Segera Hadir)</option>
                            </select>
                        </div>

                        <!-- 3. Sub-Kategori Petty Cash (Hanya Tampil Jika Memilih Petty Cash) -->
                        <div x-show="kategoriUtama === 'petty_cash'" x-cloak x-transition>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Kategori Petty Cash</label>
                            <select name="sub_kategori" x-model="subKategori" :required="kategoriUtama === 'petty_cash'"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                <option value="" disabled selected class="dark:bg-slate-900">-- Pilih Sub-Kategori --</option>
                                <option value="building_material" class="dark:bg-slate-900">Building Material (Material/Bangunan)</option>
                                <option value="fuel" class="dark:bg-slate-900">Fuel (BBM Unit)</option>
                                <option value="spare_part_vehicle" class="dark:bg-slate-900">Spare Part Vehicle (Suku Cadang Unit)</option>
                                <option value="electrical" class="dark:bg-slate-900">Electrical (Kelistrikan)</option>
                                <option value="water" class="dark:bg-slate-900">Water (Air Bersih/Konsumsi)</option>
                                <option value="office_equipment" class="dark:bg-slate-900">Office Equipment (Peralatan Kantor)</option>
                                <option value="mess_equipment" class="dark:bg-slate-900">Mess Equipment (Peralatan Mess)</option>
                            </select>
                        </div>

                        <!-- 4. Pilih Unit (Otomatis Muncul untuk Kasbon, Fuel, dan Spare Part Vehicle) -->
                        <div x-show="kategoriUtama === 'kasbon' || subKategori === 'fuel' || subKategori === 'spare_part_vehicle'" x-cloak x-transition>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">
                                Unit Terkait <span x-show="kategoriUtama === 'kasbon' || subKategori === 'fuel' || subKategori === 'spare_part_vehicle'" class="text-rose-500">*</span>
                            </label>
                            <select name="kode_unit" 
                                    :required="kategoriUtama === 'kasbon' || subKategori === 'fuel' || subKategori === 'spare_part_vehicle'"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                <option value="" class="dark:bg-slate-900">-- Pilih Unit --</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->kode_unit }}" class="dark:bg-slate-900">{{ $u->kode_unit }} - {{ $u->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- Kategori Otomatis untuk Transaksi Masuk (Debit) -->
                        <input type="hidden" name="kategori_utama" value="debit_kas">
                    @endif

                    <!-- 5. Deskripsi Transaksi -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Deskripsi / Keterangan</label>
                        <input type="text" name="deskripsi" placeholder="{{ $tipe === 'masuk' ? 'Contoh: Pengisian Kas Awal' : 'Contoh: Pembelian Semen / Solar Unit DT-01' }}" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    </div>

                    <!-- 6. No Nota & Volume Liter (Volume Khusus BBM / Fuel) -->
                    <div class="grid grid-cols-1" :class="subKategori === 'fuel' ? 'sm:grid-cols-2 gap-4' : ''">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">No. Nota (Opsional)</label>
                            <input type="text" name="no_nota" placeholder="Contoh: NT-001" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        </div>

                        <div x-show="subKategori === 'fuel'" x-cloak x-transition>
                            <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Volume (Liter)</label>
                            <input type="number" step="0.01" name="volume" placeholder="Contoh: 15.5" 
                                   class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        </div>
                    </div>

                    <!-- 7. Nominal (Rp) dengan Pemisah Titik Ribuan -->
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-1.5">Nominal (Rp)</label>
                        <input type="text" id="nominal_display" placeholder="Contoh: 250.000" required 
                               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent px-3 py-2 text-sm text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <input type="hidden" name="nominal" id="nominal_real">
                    </div>

                    <!-- 8. Tombol Aksi -->
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