<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Kelola Buku
        </h2>
    </x-slot>

    {{-- Wrapper Alpine --}}
    <div class="py-8 max-w-7xl mx-auto" x-data="{ openTambah: false }">

        <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-5 shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Action Bar --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <button @click="openTambah = true"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    + Tambah Buku
                </button>

                <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg shadow">
                    @csrf
                    <input type="file" name="file"
                        class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Import Excel
                    </button>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <th class="p-3 text-center">#</th>
                            <th class="p-3">Cover</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">ISBN</th>
                            <th class="p-3">Penerbit</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3">Deskripsi</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="p-3 text-center">{{ $loop->iteration }}</td>
                                <td class="p-3 text-center">
                                    @if ($book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover {{ $book->judul }}"
                                            class="h-16 w-auto mx-auto rounded shadow">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 font-semibold">{{ $book->judul }}</td>
                                <td class="p-3">{{ $book->category->nama ?? '-' }}</td>
                                <td class="p-3">{{ $book->penulis }}</td>
                                <td class="p-3">{{ $book->isbn }}</td>
                                <td class="p-3">{{ $book->penerbit }}</td>
                                <td class="p-3 text-center">{{ $book->tahun_terbit }}</td>
                                <td class="p-3 text-center">{{ $book->stok }}</td>
                                <td class="p-3 max-w-xs truncate" title="{{ $book->deskripsi }}">
                                    {{ $book->deskripsi ?? '-' }}
                                </td>
                                <td class="p-3 text-center flex justify-center gap-2">
                                    <a href="{{ route('books.edit', $book) }}"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded-lg shadow hover:bg-yellow-600 transition">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline-block"
                                        onsubmit="return confirm('Yakin hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-600 text-white px-3 py-1 rounded-lg shadow hover:bg-red-700 transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-4 text-center text-gray-500">
                                    Belum ada buku 📚
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah Buku --}}
        <div x-show="openTambah" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-transition.opacity>

            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-lg transform transition-all 
                max-h-[90vh] flex flex-col" x-transition.scale>

                {{-- Header (sticky) --}}
                <div class="flex justify-between items-center px-6 py-4 sticky top-0 ">
                    <h2 class="text-lg font-bold">Tambah Buku</h2>
                    <button @click="openTambah = false" class="text-gray-500 hover:text-gray-800">✖</button>
                </div>

                {{-- Isi modal scrollable --}}
                <div class="px-6 py-4 overflow-y-auto">
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
                    <form id="formTambahBuku" action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- Grid 2 kolom untuk field kecil --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Judul --}}
                            <div class="md:col-span-2">
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
                    </form>
                </div>

                {{-- Footer (sticky di bawah) --}}
                <div class="flex justify-end gap-3 px-6 py-4 sticky bottom-0 z-10">
                    <button type="button" @click="openTambah = false"
                        class="px-5 py-2.5 rounded-xl bg-gray-400 text-white shadow hover:bg-gray-500 transition">
                        Batal
                    </button>
                    <button type="submit" form="formTambahBuku"
                        class="px-5 py-2.5 rounded-xl bg-blue-500 text-white shadow hover:bg-blue-600 transition">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Script preview cover --}}
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