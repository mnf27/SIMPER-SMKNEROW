<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📚 Daftar Buku
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Pencarian --}}
            <form method="GET" action="{{ route('siswa.books.index') }}" class="flex justify-end mb-4">
                <input type="text" name="search" placeholder="Cari buku..."
                       value="{{ request('search') }}"
                       class="border rounded-lg px-3 py-2 w-1/3 dark:bg-gray-700 dark:text-gray-100">
                <button class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Cari
                </button>
            </form>

            {{-- Table Buku --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3 text-center">#</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Penerbit</th>
                            <th class="p-3 text-center">Stok</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $index => $item)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="p-3 text-center">{{ $buku->firstItem() + $index }}</td>
                                <td class="p-3">{{ $item->judul }}</td>
                                <td class="p-3">{{ $item->penulis ?? '-' }}</td>
                                <td class="p-3">{{ $item->penerbit ?? '-' }}</td>
                                <td class="text-center font-semibold">{{ $item->total_eksemplar }}</td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('siswa.books.pinjam') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="judul" value="{{ $item->judul }}">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg"
                                            {{ $item->total_eksemplar <= 0 ? 'disabled' : '' }}>
                                            Pinjam
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-gray-500">
                                    Tidak ada data buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $buku->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
