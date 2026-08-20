<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pencatatan Kasbon</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

           <!-- Card Saldo Awal & Saldo Akhir -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Saldo Awal (Biru) -->
    <div class="bg-blue-600 text-white p-5 rounded-xl shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-100">Saldo Awal</p>
            <p class="text-2xl font-semibold mt-1">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</p>
        </div>
        <div class="p-3 bg-white/20 rounded-lg text-white">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m-11 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>

    <!-- Saldo Akhir (Hijau) -->
    <div class="bg-emerald-600 text-white p-5 rounded-xl shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-100">Saldo Akhir</p>
            <p class="text-2xl font-semibold mt-1">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</p>
        </div>
        <div class="p-3 bg-white/20 rounded-lg text-white">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

            <!-- Form Input -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg mb-4">Input Transaksi Baru</h3>
                <form action="{{ route('transaksi.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold uppercase">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold uppercase">Deskripsi</label>
                        <input type="text" name="deskripsi" placeholder="Contoh: BBM - NAMA" required class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase">Pilih Unit</label>
                        <select name="kode_unit" required class="w-full rounded-md border-gray-300 text-sm">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->kode_unit }}">{{ $u->kode_unit }} - {{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase">Jenis</label>
                        <select name="jenis" required class="w-full rounded-md border-gray-300 text-sm">
                            <option value="kredit">Kredit (Pengeluaran)</option>
                            <option value="debit">Debit (Pemasukan)</option>
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-xs font-semibold uppercase">Nominal (Rp)</label>
                        <!-- Input Tampilan (Format Rupiah) -->
                        <input type="text" id="nominal_display" placeholder="Contoh: 200.000" required class="w-full rounded-md border-gray-300 text-sm">

                        <!-- Input Tersembunyi (Angka Asli Kirim ke Database) -->
                        <input type="hidden" name="nominal" id="nominal_real">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md font-semibold hover:bg-indigo-700 text-sm">Simpan</button>
                    </div>
                </form>
            </div>

            <!-- Tabel Transaksi -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-yellow-300 text-gray-600 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3">Kode Unit</th>
                            <th class="p-3 text-right">Debit</th>
                            <th class="p-3 text-right">Kredit</th>
                            <th class="p-3 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $item)
                            <tr class="border-b">
                                <td class="p-3 border">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                <td class="p-3 border uppercase font-medium">{{ $item->deskripsi }}</td>
                                <td class="p-3 border font-mono">{{ $item->kode_unit }}</td>
                                <td class="p-3 border text-right text-emerald-600">{{ $item->jenis == 'debit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                                <td class="p-3 border text-right text-rose-600">{{ $item->jenis == 'kredit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                                <td class="p-3 border text-right font-bold">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-400">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script>
    const displayInput = document.getElementById('nominal_display');
    const realInput = document.getElementById('nominal_real');

    displayInput.addEventListener('input', function(e) {
        // Ambil hanya karakter angka
        let rawValue = this.value.replace(/[^0-9]/g, '');
        
        // Simpan angka murni ke hidden input untuk backend
        realInput.value = rawValue;

        // Format tampilan menggunakan titik pemisah ribuan
        if (rawValue) {
            this.value = new Intl.NumberFormat('id-ID').format(rawValue);
        } else {
            this.value = '';
        }
    });
    </script>
</x-app-layout>