<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Kategori
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-medium">Nama</label>
                <input type="text" name="nama" value="{{ $category->nama }}" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded p-2">{{ $category->deskripsi }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('categories.index') }}" class="ml-2 text-gray-600">Batal</a>
        </form>
    </div>
</x-app-layout>