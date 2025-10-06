<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 Laporan Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Form --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <form action="{{ route('admin.reports.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    {{-- Filter User --}}
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                            Peminjam
                        </label>
                        <select name="user_id"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Rombel --}}
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">
                            Rombel
                        </label>
                        <select name="rombel_id"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($rombels as $r)
                                <option value="{{ $r->id }}" {{ request('rombel_id') == $r->id ? 'selected' : '' }}>
                                    {{ $r->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            🔍 Filter
                        </button>

                        <a href="{{ route('admin.reports.export', request()->all()) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                            ⬇️ Ekspor Excel
                        </a>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3 text-center">#</th>
                            <th class="p-3">Nama Peminjam</th>
                            <th class="p-3">Role</th>
                            <th class="p-3">Rombel</th>
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3 text-center">Tgl Pinjam</th>
                            <th class="p-3 text-center">Tgl Kembali</th>
                            <th class="p-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $index => $item)
                            <tr
                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="p-3 text-center">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $item->user->nama ?? '-' }}</td>
                                <td class="p-3">{{ ucfirst($item->user->role ?? '-') }}</td>
                                <td class="p-3">{{ $item->user->siswa->rombel->nama ?? '-' }}</td>
                                <td class="p-3">{{ $item->buku->judul ?? '-' }}</td>
                                <td class="p-3">{{ $item->buku->kategori->nama ?? '-' }}</td>
                                <td class="p-3 text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}
                                </td>
                                <td class="p-3 text-center">
                                    {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="p-3 text-center">{{ ucfirst($item->status ?? '-') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Grafik Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Grafik Batang: Peminjaman per Bulan --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 col-span-2 flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        📊 Peminjaman per Bulan
                    </h3>
                    <div class="w-full h-80"> {{-- lebih lebar dan agak tinggi --}}
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>

                {{-- Grafik Pie: Distribusi Kategori --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 col-span-1 flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        🥧 Distribusi Peminjaman per Kategori
                    </h3>
                    <div class="w-64 h-64"> {{-- ukuran pie secukupnya --}}
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Grafik Buku Terpopuler --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    📚 Top 5 Buku Paling Sering Dipinjam
                </h3>
                <canvas id="topBooksChart" height="40"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Batang per Bulan
        const ctx1 = document.getElementById('peminjamanChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: @json($bulanLabels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($jumlahData),
                    backgroundColor: 'rgba(37, 99, 235, 0.7)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Grafik Pie per Kategori
        const ctx2 = document.getElementById('kategoriChart').getContext('2d');
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: @json($kategoriLabels),
                datasets: [{
                    data: @json($kategoriJumlah),
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#14B8A6', '#F97316', '#6366F1', '#84CC16', '#E11D48'
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#374151',
                            font: { size: 12, weight: '400' }
                        }
                    }
                }
            }
        });

        // Grafik Buku
        const ctx3 = document.getElementById('topBooksChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: @json($topBookLabels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($topBookCounts),
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(37, 99, 235, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 8,
                    barThickness: 25,
                    maxBarThickness: 30
                }]
            },
            options: {
                indexAxis: 'y', // horizontal bar
                scales: {
                    x: { beginAtZero: true },
                    y: {
                        ticks: { color: '#e5e7eb' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.parsed.x + ' kali dipinjam'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>