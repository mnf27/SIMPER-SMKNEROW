<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Kelola Buku
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto" x-data="bookModal()">
        <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-5 shadow">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Action Bar --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                {{-- Tombol Tambah Buku --}}
                <button @click="openCreate()"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    + Tambah Buku
                </button>

                {{-- Import Excel --}}
                <form action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data"
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
                            <th class="p-3">No Induk</th>
                            <th class="p-3">Penerbit</th>
                            <th class="p-3">Tahun</th>
                            <th class="p-3">Jumlah Eksemplar</th>
                            <th class="p-3">Keterangan</th>
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
                                <td class="p-3">{{ $book->kategori->nama ?? '-' }}</td>
                                <td class="p-3">{{ $book->penulis }}</td>
                                <td class="p-3">{{ $book->no_induk }}</td>
                                <td class="p-3">{{ $book->penerbit }}</td>
                                <td class="p-3 text-center">{{ $book->tahun_terbit }}</td>
                                <td class="p-3 text-center">{{ $book->jumlah_eksemplar }}</td>
                                <td class="p-3 max-w-xs truncate" title="{{ $book->keterangan }}">
                                    {{ $book->keterangan ?? '-' }}
                                </td>
                                <td class="p-3 text-center flex justify-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button @click="openEdit({{ $book->toJson() }})"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded-lg shadow hover:bg-yellow-600 transition">
                                        ✏️ Edit
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
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
                <div class="px-4 py-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="rounded-lg shadow-sm">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Tambah & Edit Buku --}}
        <div x-show="openModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-transition.opacity>

            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-lg transform transition-all 
        max-h-[90vh] flex flex-col" x-transition.scale>

                {{-- Header --}}
                <div class="flex justify-between items-center px-6 py-4 sticky top-0">
                    <h2 class="text-lg font-bold" x-text="mode === 'create' ? 'Tambah Buku' : 'Edit Buku'"></h2>
                    <button @click="openModal = false" class="text-gray-500 hover:text-gray-800">✖</button>
                </div>

                {{-- Body --}}
                <div class="px-6 py-4 overflow-y-auto">
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

                    <form id="formBuku" :action="mode === 'create'
                    ? '{{ route('admin.books.store') }}'
                    : '/admin/books/' + selectedBook.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Judul --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Judul</label>
                                <input type="text" name="judul" x-model="selectedBook.judul" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                            </div>

                            {{-- Penulis --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Penulis</label>
                                <input type="text" name="penulis" x-model="selectedBook.penulis" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                            </div>

                            {{-- No Induk --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">No Induk</label>
                                <input type="text" name="no_induk" x-model="selectedBook.no_induk" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Kategori</label>
                                <select name="id_kategori" x-model="selectedBook.id_kategori" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Penerbit --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Penerbit</label>
                                <input type="text" name="penerbit" x-model="selectedBook.penerbit" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                            </div>

                            {{-- Tahun Terbit --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Tahun Terbit</label>
                                <input type="text" name="tahun_terbit" x-model="selectedBook.tahun_terbit" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" pattern="\d{4}" maxlength="4"
                                    placeholder="contoh: 2020" required>
                            </div>

                            {{-- Jumlah Eksemplar --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Jumlah Eksemplar</label>
                                <input type="number" name="jumlah_eksemplar" x-model="selectedBook.jumlah_eksemplar"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                            focus:ring-2 focus:ring-blue-400 px-4 py-2.5" required>
                            </div>
                        </div>

                        {{-- Cover --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Cover (Opsional)</label>

                            {{-- Hidden input untuk flag hapus cover --}}
                            <input type="hidden" name="remove_cover" x-model="selectedBook.remove_cover">

                            <div class="mt-3 text-center" x-show="selectedBook.cover_url || preview">
                                <img :src="preview || selectedBook.cover_url" alt="Preview Cover"
                                    class="max-h-48 rounded-xl border-2 border-dashed p-2 mb-3 mx-auto shadow-sm bg-gray-50">

                                <div>
                                    <button type="button"
                                        @click="preview = null; selectedBook.cover_url = null; selectedBook.remove_cover = 1; $refs.coverInput.value = null;"
                                        class="bg-red-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-red-600 transition">
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <input type="file" x-ref="coverInput" name="cover_image" accept="image/*" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
               focus:ring-2 focus:ring-blue-400 px-4 py-2.5 mt-2" @change="previewImage($event)">
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="4" x-model="selectedBook.keterangan" class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm
                        focus:ring-2 focus:ring-blue-400 px-4 py-2.5"></textarea>
                        </div>
                    </form>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 px-6 py-4 sticky bottom-0 z-10">
                    <button type="button" @click="openModal = false"
                        class="px-5 py-2.5 rounded-xl bg-gray-400 text-white shadow hover:bg-gray-500 transition">
                        Batal
                    </button>
                    <button type="submit" form="formBuku"
                        class="px-5 py-2.5 rounded-xl bg-blue-500 text-white shadow hover:bg-blue-600 transition"
                        x-text="mode === 'create' ? 'Simpan' : 'Update'"></button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Script preview cover --}}
<script>
    function bookModal() {
        return {
            openModal: false,
            mode: 'create',
            selectedBook: {},
            preview: null,

            openCreate() {
                this.mode = 'create';
                this.selectedBook = { remove_cover: 0 }; // default jangan hapus
                this.preview = null;
                this.openModal = true;
            },

            openEdit(book) {
                this.mode = 'edit';
                this.selectedBook = {
                    ...book,
                    remove_cover: 0, // default jangan hapus
                    cover_url: book.cover_image
                        ? "{{ asset('storage') }}/" + book.cover_image
                        : null
                };
                this.preview = null;
                this.openModal = true;
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.selectedBook.remove_cover = 0; // kalau upload cover baru, jangan hapus
                    const reader = new FileReader();
                    reader.onload = e => { this.preview = e.target.result }
                    reader.readAsDataURL(file);
                } else {
                    this.preview = null;
                }
            }
        }
    }
</script>