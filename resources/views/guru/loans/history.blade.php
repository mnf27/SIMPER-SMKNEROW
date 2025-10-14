<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📖 Riwayat Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3">#</th>
                            <th class="p-3">Judul Buku</th>
                            <th class="p-3">Tgl Pinjam</th>
                            <th class="p-3">Tgl Tempo</th>
                            <th class="p-3">Tgl Kembali</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $index => $item)
                                            <tr
                                                class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                                <td class="p-3 text-center">{{ $index + 1 }}</td>
                                                <td class="p-3">{{ $item->buku->judul ?? '-' }}</td>
                                                <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                                                <td class="p-3">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                                                <td class="p-3">
                                                    {{ $item->tanggal_dikembalikan ? \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="p-3">
                                                    <span class="px-2 py-1 rounded-lg text-xs {{ $item->status == 'aktif' ? 'bg-yellow-100 text-yellow-700' :
                            ($item->status == 'dikembalikan' ? 'bg-green-100 text-green-700' :
                                'bg-red-100 text-red-700') }}">
                                                        {{ ucfirst($item->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-gray-500">Belum ada riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>