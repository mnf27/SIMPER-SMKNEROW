<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kelola Kategori Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end">
            <a href="{{ route('categories.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                + Tambah Kategori
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr class="border-b">
                        <th class="py-2 px-3">Id</th>
                        <th class="py-2 px-3">Nama Kategori</th>
                        <th class="py-2 px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $i => $category)
                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2 px-3">{{ $i + 1 }}</td>
                            <td class="py-2 px-3 font-medium">{{ $category->name }}</td>
                            <td class="py-2 px-3 flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus?')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>