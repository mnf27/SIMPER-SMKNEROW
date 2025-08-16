<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <form action="{{ route('books.update', $book->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block font-medium text-sm text-gray-700">Judul Buku</label>
                    <input type="text" name="title" value="{{ $book->title }}"
                        class="w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium text-sm text-gray-700">Penulis</label>
                    <input type="text" name="author" value="{{ $book->author }}"
                        class="w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium text-sm text-gray-700">Kategori</label>
                    <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($book->category_id == $category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-medium text-sm text-gray-700">Stok</label>
                    <input type="number" name="stock" min="0" value="{{ $book->stock }}"
                        class="w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700">
                        Update
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