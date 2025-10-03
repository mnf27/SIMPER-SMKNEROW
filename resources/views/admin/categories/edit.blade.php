<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Edit Kategori
        </h2>
    </x-slot>

    <div class="py-10 max-w-3xl mx-auto">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST"
            class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 transition">

            @csrf
            @method('PUT')

            {{-- Input Nama --}}
            <div class="mb-6">
                <label for="nama" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $category->nama) }}"
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 @error('nama') border-red-500 @enderror">

                {{-- Pesan error --}}
                @error('nama')
                    <p id="error-nama" class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md transition font-medium">
                    Update
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
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