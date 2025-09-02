<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 17l-5-5m0 0l5-5m-5 5h16" />
            </svg>
            Edit Buku
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto">
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 border border-gray-200 dark:border-gray-700">
            
            {{-- Pesan error --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 border border-red-300">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div class="col-span-2">
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">📖 Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $book->judul) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required>
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">✍️ Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $book->penulis) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required>
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">🔖 ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">📂 Kategori</label>
                    <select name="category_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Penerbit --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">🏢 Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $book->penerbit) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        required>
                </div>

                {{-- Tahun Terbit --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">📅 Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        min="1900" max="{{ date('Y') }}" required>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">📦 Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $book->stok) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        min="0" required>
                </div>

                {{-- Cover --}}
                <div class="col-span-2">
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">🖼️ Cover (Opsional)</label>
                    <div id="previewWrapper"
                        class="mt-3 {{ $book->cover_image ? '' : 'hidden' }} text-center border-2 border-dashed rounded-xl p-4 dark:border-gray-600">
                        <img id="preview"
                            src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : '#' }}"
                            alt="Preview Cover" class="max-h-52 rounded-lg mx-auto mb-3 shadow">
                        <div>
                            <button type="button" onclick="clearImage()"
                                class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-600 transition shadow">
                                Hapus Gambar
                            </button>
                        </div>
                    </div>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                        class="w-full border rounded-lg px-3 py-2 mt-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600"
                        onchange="previewImage(event)">
                    <input type="hidden" name="remove_cover" id="remove_cover" value="0">
                </div>

                {{-- Deskripsi --}}
                <div class="col-span-2">
                    <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">📝 Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="col-span-2 flex justify-end space-x-3 pt-4">
                    <a href="{{ route('books.index') }}"
                        class="bg-gray-500 text-white px-5 py-2 rounded-lg shadow hover:bg-gray-600 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        💾 Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const wrapper = document.getElementById('previewWrapper');
            const removeCover = document.getElementById('remove_cover');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
                removeCover.value = 0;
            }
        }

        function clearImage() {
            const input = document.getElementById('cover_image');
            const preview = document.getElementById('preview');
            const wrapper = document.getElementById('previewWrapper');
            const removeCover = document.getElementById('remove_cover');

            input.value = "";
            preview.src = "#";
            wrapper.classList.add('hidden');
            removeCover.value = 1;
        }
    </script>
</x-app-layout>
