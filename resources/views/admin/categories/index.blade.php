<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kelola Kategori Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah
            Kategori</a>

        <div class="mt-4 bg-white shadow rounded-lg p-4">
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Nama</th>
                        <th class="p-2 border">Deskripsi</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="p-2 border">{{ $loop->iteration }}</td>
                            <td class="p-2 border">{{ $category->nama }}</td>
                            <td class="p-2 border">{{ $category->deskripsi }}</td>
                            <td class="p-2 border">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-2 text-center">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>