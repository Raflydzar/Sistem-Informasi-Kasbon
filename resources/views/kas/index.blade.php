<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Master Data Kas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alert Session -->
            @if(session('success'))
                <div class="p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Tambah Kas -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Tambah Kas Baru</h3>
                    <form action="{{ route('kas.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Kas</label>
                            <input type="text" name="nama_kas" placeholder="Contoh: Kas Utama" value="{{ old('nama_kas') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nama_kas') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Saldo Awal (Rp)</label>
                            <input type="number" name="saldo_awal" placeholder="Contoh: 1000000" min="0" value="{{ old('saldo_awal', 0) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('saldo_awal') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700">Simpan Kas</button>
                    </form>
                </div>

                <!-- Tabel Data Kas -->
                <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Daftar Kas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 uppercase text-xs text-gray-400">
                                <tr>
                                    <th class="p-3">#</th>
                                    <th class="p-3">Nama Kas</th>
                                    <th class="p-3 text-right">Saldo Awal</th>
                                    <th class="p-3 text-right">Saldo Sekarang</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($kas as $index => $item)
                                    <tr>
                                        <td class="p-3 font-medium">{{ $kas->firstItem() + $index }}</td>
                                        <td class="p-3 font-semibold text-gray-800">{{ $item->nama_kas }}</td>
                                        <td class="p-3 text-right text-gray-500">Rp{{ number_format($item->saldo_awal, 0, ',', '.') }}</td>
                                        <td class="p-3 text-right font-bold text-emerald-600">Rp{{ number_format($item->saldo_sekarang, 0, ',', '.') }}</td>
                                        <td class="p-3 text-center space-x-2">
                                            <form action="{{ route('kas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kas ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold hover:bg-rose-200">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-400">Belum ada data kas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $kas->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>