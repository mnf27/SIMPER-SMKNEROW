<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            📚 <span>Daftar Buku</span>
        </h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto px-4">
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Judul</th>
                            <th class="px-6 py-3">Penulis</th>
                            <th class="px-6 py-3">Kategori</th>
                            <th class="px-6 py-3 text-center">Stok</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $item->judul }}</td>
                                <td class="px-6 py-3">{{ $item->penulis }}</td>
                                <td class="px-6 py-3">{{ $item->kategori->nama ?? '-' }}</td>
                                <td class="px-6 py-3 text-center">
                                    @if($item->stok > 0)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                            {{ $item->stok }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded-full">
                                            Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($item->stok > 0)
                                        <form action="{{ route('guru.books.pinjam', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow transition">
                                                Pinjam
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    Tidak ada buku tersedia 📭
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
