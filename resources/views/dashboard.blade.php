<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard & Analytics</h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Cards Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border">
                    <p class="text-xs font-bold text-gray-400 uppercase">Saldo Akhir</p>
                    <p class="text-xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Debit</p>
                    <p class="text-xl font-extrabold text-blue-600 mt-1">Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Kredit (Kasbon)</p>
                    <p class="text-xl font-extrabold text-rose-600 mt-1">Rp {{ number_format($totalKredit, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Transaksi Kasbon</p>
                    <p class="text-xl font-extrabold text-amber-600 mt-1">{{ $jumlahKasbon }} Transaksi</p>
                </div>
            </div>

            <!-- Grid 2 Grafik -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Grafik 1: Debit vs Kredit Bulanan -->
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h3 class="font-bold text-gray-700 mb-4">Nominal Debit vs Kredit Bulanan ({{ date('Y') }})</h3>
                    <div class="h-64">
                        <canvas id="chartDebitKredit"></canvas>
                    </div>
                </div>

                <!-- Grafik 2: Perbandingan Frekuensi Transaksi -->
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700">Frekuensi Transaksi</h3>
                        <div class="space-x-1">
                            <button onclick="switchChart('minggu')" id="btn-minggu" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-600 text-white">Minggu</button>
                            <button onclick="switchChart('bulan')" id="btn-bulan" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600">Bulan</button>
                            <button onclick="switchChart('tahun')" id="btn-tahun" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600">Tahun</button>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="chartAnalytics"></canvas>
                    </div>
                </div>
            </div>

           <!-- Tabel Riwayat Kasbon Berlanjut -->
<div class="bg-white p-6 rounded-xl shadow-sm border">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
        <h3 class="font-bold text-gray-700">Riwayat Kasbon</h3>
        
        <!-- Form Filter Periode -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <label class="text-xs font-semibold text-gray-500 uppercase">Periode:</label>
            <select name="filter" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 py-1.5 px-3">
                <option value="1tahun" {{ request('filter') == '1tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                <option value="minggu" {{ request('filter') == 'minggu' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun Ini ({{ date('Y') }})</option>
            </select>
        </form>
    </div>

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
                @forelse($riwayatKasbon as $item)
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
                        <td colspan="6" class="p-4 text-center text-gray-400">Tidak ada riwayat transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $riwayatKasbon->links() }}
    </div>
</div>
    </div>

    <script>
        // Init Grafik 1: Debit vs Kredit Bulanan
        const ctx1 = document.getElementById('chartDebitKredit').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [
                    { label: 'Debit / Pemasukan', data: {{ \Illuminate\Support\Js::from(array_values($debitBulanan)) }}, backgroundColor: '#10B981', borderRadius: 4 },
                    { label: 'Kredit / Kasbon', data: {{ \Illuminate\Support\Js::from(array_values($kreditBulanan)) }}, backgroundColor: '#F43F5E', borderRadius: 4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });

        // Init Grafik 2: Frekuensi Transaksi (Dynamic)
        const ctx2 = document.getElementById('chartAnalytics').getContext('2d');
        const chartData = {
            minggu: { labels: {!! json_encode(array_keys($mingguanData)) !!}, data: {!! json_encode(array_values($mingguanData)) !!}, label: 'Transaksi 7 Hari Terakhir' },
            bulan: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], data: {!! json_encode(array_values(array_replace(array_fill(1, 12, 0), $bulananData))) !!}, label: 'Transaksi per Bulan' },
            tahun: { labels: {!! json_encode(array_keys($tahunanData)) !!}, data: {!! json_encode(array_values($tahunanData)) !!}, label: 'Transaksi per Tahun' }
        };

        let analyticsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: chartData.minggu.labels,
                datasets: [{ label: chartData.minggu.label, data: chartData.minggu.data, backgroundColor: '#6366F1', borderRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        function switchChart(type) {
            ['minggu', 'bulan', 'tahun'].forEach(t => {
                document.getElementById(`btn-${t}`).className = (t === type)
                    ? "px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-600 text-white"
                    : "px-2.5 py-1 text-xs font-bold rounded-lg bg-gray-100 text-gray-600";
            });
            analyticsChart.data.labels = chartData[type].labels;
            analyticsChart.data.datasets[0].data = chartData[type].data;
            analyticsChart.data.datasets[0].label = chartData[type].label;
            analyticsChart.update();
        }
    </script>
</x-app-layout>