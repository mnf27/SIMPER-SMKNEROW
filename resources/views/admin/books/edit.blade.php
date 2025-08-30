<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            {{-- Pesan error --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label class="block font-medium">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $book->judul) }}"
                        class="w-full border rounded p-2" required>
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block font-medium">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $book->penulis) }}"
                        class="w-full border rounded p-2" required>
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block font-medium">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                        class="w-full border rounded p-2" required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block font-medium">Kategori</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Penerbit --}}
                <div>
                    <label class="block font-medium">Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $book->penerbit) }}"
                        class="w-full border rounded p-2" required>
                </div>

                {{-- Tahun Terbit --}}
                <div>
                    <label class="block font-medium">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                        class="w-full border rounded p-2" min="1900" max="{{ date('Y') }}" required>
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block font-medium">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', $book->stok) }}"
                        class="w-full border rounded p-2" min="0" required>
                </div>

                {{-- Cover --}}
                <div>
                    <label class="block font-medium">Cover (Opsional)</label>

                    {{-- Preview gambar --}}
                    <div id="previewWrapper" class="mt-3 {{ $book->cover_image ? '' : 'hidden' }}">
                        <img id="preview" src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : '#' }}"
                            alt="Preview Cover" class="max-h-48 rounded border mb-2">

                        <div>
                            <button type="button" onclick="clearImage()"
                                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                Hapus Gambar
                            </button>
                        </div>
                    </div>

                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                        class="w-full border rounded p-2" onchange="previewImage(event)">

                    {{-- Flag untuk hapus cover lama --}}
                    <input type="hidden" name="remove_cover" id="remove_cover" value="0">
                </div>


                {{-- Deskripsi --}}
                <div>
                    <label class="block font-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full border rounded p-2">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('books.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
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
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
                removeCover.value = 0; // kalau upload baru, reset flag
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
            removeCover.value = 1; // kasih flag hapus cover
        }
    </script>
</x-app-layout>