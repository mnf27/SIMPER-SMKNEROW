<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Daftar Peminjaman</h3>
                <!-- kalau mau export laporan -->
                <a href="{{ route('loan.export.excel') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Export Excel
                </a>
            </div>

            <div class="p-6 overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Nama Peminjam</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Judul Buku</th>
                            <th class="px-4 py-2">Tanggal Pinjam</th>
                            <th class="px-4 py-2">Jatuh Tempo</th>
                            <th class="px-4 py-2">Tanggal Kembali</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $item->user->guru->nama ?? $item->user->siswa->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($item->user->role) }}</td>
                                <td class="px-4 py-2">{{ $item->buku->judul }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_peminjaman }}</td>
                                <td class="px-4 py-2">{{ $item->tanggal_jatuh_tempo }}</td>
                                <td class="px-4 py-2">
                                    {{ $item->tanggal_pengembalian ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($item->status == 'aktif')
                                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">Aktif</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">Dikembalikan</span>
                                    @else
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs">Terlambat</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $item->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-gray-500">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>