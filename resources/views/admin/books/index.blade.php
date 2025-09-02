<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4" />
            </svg>
            Kelola Buku
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-5 shadow">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Action Bar --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <a href="{{ route('books.create') }}" 
               class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                + Tambah Buku
            </a>

            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data"
                class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg shadow">
                @csrf
                <input type="file" name="file" 
                       class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <button type="submit" 
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    Import Excel
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <th class="p-3 text-center">#</th>
                        <th class="p-3">Cover</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Penulis</th>
                        <th class="p-3">ISBN</th>
                        <th class="p-3">Penerbit</th>
                        <th class="p-3">Tahun</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Deskripsi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="p-3 text-center">{{ $loop->iteration }}</td>
                            <td class="p-3 text-center">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                         alt="Cover {{ $book->judul }}"
                                         class="h-16 w-auto mx-auto rounded shadow">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 font-semibold">{{ $book->judul }}</td>
                            <td class="p-3">{{ $book->category->nama ?? '-' }}</td>
                            <td class="p-3">{{ $book->penulis }}</td>
                            <td class="p-3">{{ $book->isbn }}</td>
                            <td class="p-3">{{ $book->penerbit }}</td>
                            <td class="p-3 text-center">{{ $book->tahun_terbit }}</td>
                            <td class="p-3 text-center">{{ $book->stok }}</td>
                            <td class="p-3 max-w-xs truncate" title="{{ $book->deskripsi }}">
                                {{ $book->deskripsi ?? '-' }}
                            </td>
                            <td class="p-3 text-center flex justify-center gap-2">
                                <a href="{{ route('books.edit', $book) }}"
                                   class="bg-yellow-500 text-white px-3 py-1 rounded-lg shadow hover:bg-yellow-600 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-700 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-4 text-center text-gray-500">
                                Belum ada buku 📚
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
