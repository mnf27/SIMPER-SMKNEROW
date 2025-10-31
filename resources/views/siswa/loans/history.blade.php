<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-2 px-3 border">#</th>
                                <th class="py-2 px-3 border">Judul Buku</th>
                                <th class="py-2 px-3 border">Tgl Pinjam</th>
                                <th class="py-2 px-3 border">Tgl Tempo</th>
                                <th class="py-2 px-3 border">Tgl Kembali</th>
                                <th class="py-2 px-3 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $index => $item)
                                                    <tr class="border-b border-gray-300 dark:border-gray-700">
                                                        <td class="py-2 px-3 border">{{ $index + 1 }}</td>
                                                        <td class="py-2 px-3 border">{{ $item->buku->judul ?? '-' }}</td>
                                                        <td class="py-2 px-3 border">
                                                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                                                        <td class="py-2 px-3 border">
                                                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}
                                                        </td>
                                                        <td class="py-2 px-3 border">
                                                            {{ $item->tanggal_dikembalikan ? \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d/m/Y') : '-' }}
                                                        </td>
                                                        <td class="py-2 px-3 border">
                                                            <span class="px-2 py-1 rounded-lg text-xs {{ $item->status == 'aktif' ? 'bg-yellow-100 text-yellow-700' :
                                ($item->status == 'dikembalikan' ? 'bg-green-100 text-green-700' :
                                    'bg-red-100 text-red-700') }}">
                                                                {{ ucfirst($item->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Belum ada riwayat peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>