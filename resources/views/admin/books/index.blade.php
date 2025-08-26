<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between mb-4">
            <a href="{{ route('books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah Buku</a>

            <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data"
                class="flex space-x-2">
                @csrf
                <input type="file" name="file" class="border rounded p-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Import Excel</button>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
            <table class="w-full border text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Cover</th>
                        <th class="p-2 border">Judul</th>
                        <th class="p-2 border">Kategori</th>
                        <th class="p-2 border">Penulis</th>
                        <th class="p-2 border">ISBN</th>
                        <th class="p-2 border">Penerbit</th>
                        <th class="p-2 border">Tahun</th>
                        <th class="p-2 border">Stok</th>
                        <th class="p-2 border">Deskripsi</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td class="p-2 border text-center">{{ $loop->iteration }}</td>
                            <td class="p-2 border text-center">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover {{ $book->judul }}"
                                        class="h-16 mx-auto rounded">
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-2 border font-semibold">{{ $book->judul }}</td>
                            <td class="p-2 border">{{ $book->category->nama ?? '-' }}</td>
                            <td class="p-2 border">{{ $book->penulis }}</td>
                            <td class="p-2 border">{{ $book->isbn }}</td>
                            <td class="p-2 border">{{ $book->penerbit }}</td>
                            <td class="p-2 border text-center">{{ $book->tahun_terbit }}</td>
                            <td class="p-2 border text-center">{{ $book->stok }}</td>
                            <td class="p-2 border max-w-xs truncate" title="{{ $book->deskripsi }}">
                                {{ $book->deskripsi ?? '-' }}
                            </td>
                            <td class="p-2 border text-center space-x-1">
                                <a href="{{ route('books.edit', $book) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-2 text-center">Belum ada buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>