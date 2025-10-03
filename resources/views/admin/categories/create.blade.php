<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
             Tambah Kategori
        </h2>
    </x-slot>

    <div class="py-10 max-w-3xl mx-auto">
        <div
            class="bg-white dark:bg-gray-900 shadow-2xl rounded-3xl p-8 border border-gray-200 dark:border-gray-700 transition duration-300 hover:shadow-blue-200 dark:hover:shadow-blue-900">

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Input Nama --}}
                <div>
                    <label for="nama" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">🏷️ Nama
                        Kategori</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                        class="w-full rounded-xl px-4 py-3 border dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-800 dark:text-gray-100 @error('nama') border-red-500 @enderror"
                        required>

                    {{-- Pesan error --}}
                    @error('nama')
                        <p id="error-nama" class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('admin.categories.index') }}"
                        class="bg-gray-500 text-white px-5 py-2 rounded-xl shadow hover:bg-gray-600 transition transform hover:-translate-y-1">
                        ↩️ Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-xl shadow hover:bg-blue-700 transition transform hover:-translate-y-1">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script untuk hilangkan error otomatis --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputNama = document.getElementById("nama");
            const errorNama = document.getElementById("error-nama");

            if (inputNama && errorNama) {
                inputNama.addEventListener("input", function () {
                    errorNama.style.display = "none"; // sembunyikan error
                    inputNama.classList.remove("border-red-500"); // hapus border merah
                });
            }
        });
    </script>
</x-app-layout>
