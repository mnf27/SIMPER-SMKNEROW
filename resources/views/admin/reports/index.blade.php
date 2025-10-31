<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Filter --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6">
                <form action="{{ route('admin.reports.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Peminjam</label>
                        <select name="user_id"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Semua</option>

                            <optgroup label="Guru">
                                @foreach($users->where('role', 'guru') as $g)
                                    <option value="{{ $g->id }}" {{ request('user_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </optgroup>

                            <optgroup label="Siswa">
                                @foreach($users->where('role', 'siswa') as $s)
                                    <option value="{{ $s->id }}" {{ request('user_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Rombel</label>
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

                    <div>
                        <label class="block text-gray-700 dark:text-gray-300 text-sm mb-1">Buku</label>
                        <select name="book_id"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                            <option value="">Semua</option>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id }}" {{ request('book_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->judul }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            Filter
                        </button>

                        <a href="{{ route('admin.reports.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                            Hapus Filter
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('admin.reports.export', request()->all()) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Ekspor Excel
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Total Peminjaman</h4>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                            {{ $totalPeminjaman }}
                        </p>
                    </div>

                    <div
                        class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-green-800 dark:text-green-300">Total Buku Dipinjam</h4>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                            {{ $totalBukuDipinjam }}
                        </p>
                    </div>

                    <div
                        class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Masih Aktif</h4>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ $totalAktif }}
                        </p>
                    </div>

                    <div class="bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-xl p-4">
                        <h4 class="text-sm font-medium text-red-800 dark:text-red-300">Terlambat</h4>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                            {{ $totalTerlambat }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Rekap Data Peminjaman
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    {{-- Table --}}
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <th class="py-2 px-3 border text-center">#</th>
                                <th class="py-2 px-3 border">Nama Peminjam</th>
                                <th class="py-2 px-3 border">Role</th>
                                <th class="py-2 px-3 border">Rombel</th>
                                <th class="py-2 px-3 border">Judul Buku</th>
                                <th class="py-2 px-3 border">No. Eksemplar</th>
                                <th class="py-2 px-3 border text-center">Tgl Pinjam</th>
                                <th class="py-2 px-3 border text-center">Tgl Kembali</th>
                                <th class="py-2 px-3 border text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $item)
                                <tr
                                    class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="py-2 px-3 border text-center">{{ $peminjaman->firstItem() + $index }}</td>
                                    <td class="py-2 px-3 border">{{ $item->user->nama ?? '-' }}</td>
                                    <td class="py-2 px-3 border">{{ ucfirst($item->user->role ?? '-') }}</td>
                                    <td class="py-2 px-3 border">{{ $item->user->siswa->rombel->nama ?? '-' }}</td>
                                    <td class="py-2 px-3 border">{{ $item->eksemplar->buku->judul ?? '-' }}</td>
                                    <td class="py-2 px-3 border">{{ $item->eksemplar->no_induk ?? '-' }}</td>
                                    <td class="py-2 px-3 border text-center">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-2 px-3 border text-center">
                                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-2 px-3 border text-center">{{ ucfirst($item->status ?? '-') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $peminjaman->links() }}</div>
            </div>

            {{-- Grafik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        Peminjaman per Bulan</h3>
                    <div class="w-full h-[15rem]">
                        <canvas id="peminjamanChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        Top 5 Buku Paling Sering Dipinjam
                    </h3>
                    <div class="w-full h-[15rem]">
                        <canvas id="topBooksChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('peminjamanChart'), {
            type: 'bar',
            data: {
                labels: @json($bulanLabels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($jumlahData),
                    backgroundColor: 'rgba(37,99,235,0.7)',
                    borderColor: 'rgba(37,99,235,1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('topBooksChart'), {
            type: 'bar',
            data: {
                labels: @json($topBookLabels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($topBookCounts),
                    backgroundColor: [
                        'rgba(37,99,235,0.7)',
                        'rgba(16,185,129,0.7)',
                        'rgba(245,158,11,0.7)',
                        'rgba(239,68,68,0.7)',
                        'rgba(139,92,246,0.7)',
                    ],
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                maintainAspectRatio: false
            }
        });
    </script>
</x-app-layout>