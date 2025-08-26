<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Kategori
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form action="{{ route('categories.store') }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Nama</label>
                <input type="text" name="nama" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded p-2"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            <a href="{{ route('categories.index') }}" class="ml-2 text-gray-600">Batal</a>
        </form>
    </div>
</x-app-layout>