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

            {{-- Input Nama --}}
            <div class="mb-4">
                <label for="nama" class="block font-medium">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $category->nama) }}"
                    class="w-full border rounded p-2 @error('nama') border-red-500 @enderror">

                {{-- Pesan error --}}
                @error('nama')
                    <p id= "error-nama" class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Deskripsi --}}
            <div class="mb-4">
                <label for="deskripsi" class="block font-medium">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi"
                    class="w-full border rounded p-2 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('categories.index') }}" class="ml-2 text-gray-600">Batal</a>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputNama = document.getElementById("nama");
            const errorNama = document.getElementById("error-nama");

            if (inputNama && errorNama) {
                inputNama.addEventListener("input", function () {
                    errorNama.style.display = "none";
                    inputNama.classList.remove("border-red-500");
                });
            }
        });
    </script>
</x-app-layout>