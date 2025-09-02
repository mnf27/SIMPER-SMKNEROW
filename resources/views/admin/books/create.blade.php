<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Buku
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-2xl p-8 border border-gray-200">

            {{-- Pesan error --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold mb-2">Terdapat kesalahan:</h3>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                </div>

                {{-- ISBN --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Kategori</label>
                    <select name="category_id" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                   focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Penerbit --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Penerbit</label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                </div>

                {{-- Tahun Terbit --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Tahun Terbit</label>
                    <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" pattern="\d{4}"
                        maxlength="4" placeholder="contoh: 2020" required>
                    @error('tahun_terbit')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok') }}" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5" required>
                </div>

                {{-- Cover --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Cover (Opsional)</label>
                    <div id="previewWrapper" class="mt-3 hidden text-center">
                        <img id="preview" src="#" alt="Preview Cover"
                            class="max-h-48 rounded-xl border-2 border-dashed p-2 mb-3 mx-auto shadow-sm bg-gray-50">
                        <div>
                            <button type="button" onclick="clearImage()"
                                class="bg-red-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-red-600 transition">
                                Hapus Gambar
                            </button>
                        </div>
                    </div>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5 mt-2"
                        onchange="previewImage(event)">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm 
                                     focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-4 py-2.5">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 pt-6">
                    <a href="{{ route('books.index') }}"
                        class="px-5 py-2.5 rounded-xl bg-gray-400 text-white shadow hover:bg-gray-500 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-500 text-white shadow hover:bg-blue-600 transition">
                        Simpan
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

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    wrapper.classList.remove('hidden');
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

            input.value = "";
            preview.src = "#";
            wrapper.classList.add('hidden');
        }
    </script>
</x-app-layout>