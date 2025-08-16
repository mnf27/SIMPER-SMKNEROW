<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <form action="{{ route('books.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label block font-medium text-sm text-gray-700">Judul Buku</label>
                    <input type="text" name="title" class="form-control w-full rounded-md border-gray-300 shadow-sm"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label block font-medium text-sm text-gray-700">Penulis</label>
                    <input type="text" name="author" class="form-control w-full rounded-md border-gray-300 shadow-sm"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label block font-medium text-sm text-gray-700">Kategori</label>
                    <select name="category_id" class="form-control w-full rounded-md border-gray-300 shadow-sm"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label block font-medium text-sm text-gray-700">Stok</label>
                    <input type="number" name="stock" min="0"
                        class="form-control w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md shadow hover:bg-green-700">
                        Simpan
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md shadow hover:bg-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>