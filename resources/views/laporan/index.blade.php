<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Kasbon</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filter Box -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" value="{{ $tglAwal }}" class="w-full mt-1 text-sm rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" class="w-full mt-1 text-sm rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-600 uppercase">Unit</label>
                        <select name="kode_unit" class="w-full mt-1 text-sm rounded-lg border-gray-300">
                            <option value="">-- Semua Unit --</option>
                            @foreach($units as $u)
                                <option value="{{ $u->kode_unit }}" {{ $kodeUnit == $u->kode_unit ? 'selected' : '' }}>
                                    {{ $u->kode_unit }} - {{ $u->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm">
                            Filter
                        </button>
                        <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-lg text-sm text-center">
                            Cetak PDF
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl">
                    <p class="text-xs font-bold text-blue-600 uppercase">Total Debit Periode Ini</p>
                    <p class="text-xl font-semibold text-blue-900 mt-1">Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-rose-50 border border-rose-200 p-4 rounded-xl">
                    <p class="text-xs font-bold text-rose-600 uppercase">Total Kredit Periode Ini</p>
                    <p class="text-xl font-semibold text-rose-900 mt-1">Rp {{ number_format($totalKredit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl">
                    <p class="text-xs font-bold text-emerald-600 uppercase">Selisih Kas Periode Ini</p>
                    <p class="text-xl font-semibold text-emerald-900 mt-1">Rp {{ number_format($selisih, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 border-collapse">
                        <thead class="bg-gray-50 uppercase text-xs text-gray-400">
                            <tr>
                                <th class="p-3 border">Tanggal</th>
                                <th class="p-3 border">Deskripsi</th>
                                <th class="p-3 border">Kode Unit</th>
                                <th class="p-3 border text-right">Debit</th>
                                <th class="p-3 border text-right">Kredit</th>
                                <th class="p-3 border text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 border">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                                    <td class="p-3 border uppercase font-medium">{{ $item->deskripsi }}</td>
                                    <td class="p-3 border font-mono">{{ $item->kode_unit }}</td>
                                    <td class="p-3 border text-right text-emerald-600 font-semibold">{{ $item->jenis == 'debit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                                    <td class="p-3 border text-right text-rose-600 font-semibold">{{ $item->jenis == 'kredit' ? 'Rp '.number_format($item->nominal, 0, ',', '.') : '-' }}</td>
                                    <td class="p-3 border text-right font-bold">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>