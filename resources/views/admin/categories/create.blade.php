<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Kategori
        </h2>
    </x-slot>

    <div class="py-6 max-w-md mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded p-6">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-200">Nama Kategori</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded mt-1" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="!bg-blue-600 hover:!bg-blue-700 !text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                    <a href="{{ route('categories.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>