<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Buku
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

            {{-- Form --}}
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border rounded p-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" class="w-full border rounded p-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block font-medium">Kategori</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="w-full border rounded p-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium">Tahun Terbit</label>
                    <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit') }}"
                        class="w-full border rounded p-2" pattern="\d{4}" maxlength="4" required
                        placeholder="contoh: 2020">
                    @error('tahun_terbit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label class="block font-medium">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok') }}" class="w-full border rounded p-2"
                        required>
                </div>

                <div>
                    <label class="block font-medium">Cover (Opsional)</label>
                    {{-- Preview gambar --}}
                    <div id="previewWrapper" class="mt-3 hidden">
                        <img id="preview" src="#" alt="Preview Cover" class="max-h-48 rounded border mb-2">
                        <div>
                        <button type="button" onclick="clearImage()"
                            class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                            Hapus Gambar
                        </button>
                        </div>
                    </div>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                        class="w-full border rounded p-2" onchange="previewImage(event)">
                </div>

                <div>
                    <label class="block font-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full border rounded p-2">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('books.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const wrapper = document.getElementById('previewWrapper');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden'); // tampilkan wrapper
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                clearImage();
            }
        }

        function clearImage() {
            const input = document.getElementById('cover_image');
            const preview = document.getElementById('preview');
            const wrapper = document.getElementById('previewWrapper');

            input.value = ""; // reset file input
            preview.src = "#";
            wrapper.classList.add('hidden'); // sembunyikan preview + tombol
        }
    </script>
</x-app-layout>