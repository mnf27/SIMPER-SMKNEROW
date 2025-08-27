<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Laporan Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            {{-- Form Filter --}}
            <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div>
                    <label for="peminjam" class="block text-sm font-medium text-gray-700">Peminjam</label>
                    <input type="text" name="peminjam" id="peminjam" value="{{ request('peminjam') }}"
                        placeholder="Nama / NIS / NIP" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Filter
                    </button>
                </div>
            </form>

            {{-- Tabel Data --}}
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Peminjam</th>
                            <th class="px-4 py-2 border">Judul Buku</th>
                            <th class="px-4 py-2 border">Tanggal Pinjam</th>
                            <th class="px-4 py-2 border">Tanggal Jatuh Tempo</th>
                            <th class="px-4 py-2 border">Tanggal Pengembalian</th>
                            <th class="px-4 py-2 border">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border">
                                    @if ($report->user->role === 'siswa' && $report->user->siswa)
                                        {{ $report->user->siswa->nama }} (NIS: {{ $report->user->siswa->nis }})
                                    @elseif ($report->user->role === 'guru' && $report->user->guru)
                                        {{ $report->user->guru->nama }} (NIP: {{ $report->user->guru->nip }})
                                    @else
                                        {{ $report->user->email }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">{{ $report->book->judul ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $report->tanggal_peminjaman?->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 border">{{ $report->tanggal_jatuh_tempo?->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 border">{{ $report->tanggal_pengembalian?->format('d-m-Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 rounded text-xs
                                                @if($report->status == 'aktif') bg-yellow-200 text-yellow-800
                                                @elseif($report->status == 'dikembalikan') bg-green-200 text-green-800
                                                @else bg-red-200 text-red-800 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 border text-center text-gray-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tombol Export --}}
            <div class="mt-6 flex justify-end">
                <a href="{{ route('reports.export', request()->all()) }}"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Export Excel
                </a>
            </div>
        </div>
    </div>
</x-app-layout>