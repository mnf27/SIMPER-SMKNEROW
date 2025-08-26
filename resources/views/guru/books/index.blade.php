<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Judul</th>
                        <th class="border px-4 py-2">Penulis</th>
                        <th class="border px-4 py-2">Kategori</th>
                        <th class="border px-4 py-2">Stok</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->judul }}</td>
                            <td class="border px-4 py-2">{{ $item->penulis }}</td>
                            <td class="border px-4 py-2">{{ $item->kategori->nama ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $item->stok }}</td>
                            <td class="border px-4 py-2 text-center">
                                @if($item->stok > 0)
                                    <form action="{{ route('guru.books.pinjam', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                            Pinjam
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500">Habis</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>