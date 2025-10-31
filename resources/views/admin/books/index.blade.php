<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Buku') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="bookModal()">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Action Bar --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-700 dark:text-gray-200">
                    Import Buku
                </h3>
                <form action="{{ route('admin.books.import') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="file" name="file" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <x-primary-button>Upload</x-primary-button>
                </form>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    Data Buku
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 py-2 border text-center">No</th>
                                <th class="px-3 py-2 border">Judul</th>
                                <th class="px-3 py-2 border">Penulis</th>
                                <th class="px-3 py-2 border">Penerbit</th>
                                <th class="px-3 py-2 border">Cetakan/Edisi</th>
                                <th class="px-3 py-2 border">No. Class</th>
                                <th class="px-3 py-2 border">Asal Buku</th>
                                <th class="px-3 py-2 border text-center">Tahun</th>
                                <th class="px-3 py-2 border text-center">Jumlah Eksemplar</th>
                                <th class="px-3 py-2 border text-center">Keterangan</th>
                                <th class="px-3 py-2 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-3 py-2 border text-center">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2 border font-semibold">{{ $book->judul }}</td>
                                    <td class="px-3 py-2 border">{{ $book->penulis }}</td>
                                    <td class="px-3 py-2 border">{{ $book->penerbit }}</td>
                                    <td class="px-3 py-2 border">{{ $book->cetakan_edisi }}</td>
                                    <td class="px-3 py-2 border">{{ $book->klasifikasi }}</td>
                                    <td class="px-3 py-2 border">{{ $book->asal }}</td>
                                    <td class="px-3 py-2 border text-center">{{ $book->tahun_terbit }}</td>
                                    <td class="px-3 py-2 border text-center font-semibold">{{ $book->eksemplar_tersedia }}
                                    <td class="px-3 py-2 border">{{ $book->keterangan }}</td>
                                    </td>
                                    <td class="px-3 py-2 border text-center flex justify-center gap-2">
                                        {{-- Tombol Detail --}}
                                        <button @click="openDetail({{ $book->id }})"
                                            class="text-blue-500 hover:underline items-center">
                                            Detail
                                        </button>

                                        {{-- Tombol Edit --}}
                                        <button @click="openEdit({{ $book->toJson() }})"
                                            class="text-black-500 hover:underline items-center">
                                            Edit
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline flex">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-4 text-center text-gray-500">
                                        Belum ada buku 📚
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>

            <!-- Modal Detail Buku -->
            <x-modal title="Detail Buku" show="openDetailModal">
                <template x-if="selectedDetail">
                    <div>
                        <!-- Bagian info utama -->
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <img :src="selectedDetail.cover_image 
                    ? '{{ asset('storage') }}/' + selectedDetail.cover_image 
                    : '{{ asset('images/default_cover.png') }}'" alt="Cover Buku"
                                class="w-32 h-44 object-cover rounded-lg border bg-gray-50 mx-auto md:mx-0" />

                            <div class="flex-1">
                                <h3 class="text-xl font-semibold mb-1" x-text="selectedDetail.judul"></h3>
                                <p class="text-gray-700"><span x-text="selectedDetail.penulis"></span></p>
                                <p class="text-gray-700"><span x-text="selectedDetail.tahun_terbit"></span></p>

                                <!-- Info tambahan -->
                                <div class="mt-3 p-3 bg-gray-50 border rounded-lg text-sm">
                                    <p><strong>Total Eksemplar:</strong>
                                        <span x-text="selectedDetail.eksemplar?.length || 0"></span>
                                    </p>
                                    <p><strong>Tersedia:</strong>
                                        <span
                                            x-text="selectedDetail.eksemplar?.filter(e => e.status === 'tersedia').length || 0"></span>
                                    </p>
                                    <p><strong>Dipinjam:</strong>
                                        <span
                                            x-text="selectedDetail.eksemplar?.filter(e => e.status === 'dipinjam').length || 0"></span>
                                    </p>
                                    <template x-if="selectedDetail.updated_at">
                                        <p><strong>Terakhir Diperbarui:</strong>
                                            <span
                                                x-text="new Date(selectedDetail.updated_at).toLocaleString('id-ID')"></span>
                                        </p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian daftar eksemplar -->
                        <template x-if="selectedDetail.eksemplar.length">
                            <div class="border border-gray-300 rounded-lg overflow-hidden">
                                <div class="max-h-40 overflow-y-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-100 sticky top-0">
                                            <tr>
                                                <th class="p-2 border text-center">No Induk</th>
                                                <th class="p-2 border text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="e in selectedDetail.eksemplar" :key="e.id">
                                                <tr class="hover:bg-gray-50">
                                                    <td class="p-2 border text-center" x-text="e.no_induk"></td>
                                                    <td class="p-2 border text-center capitalize" x-text="e.status">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedDetail.eksemplar.length">
                            <p class="text-gray-500 text-center py-6">Belum ada eksemplar untuk buku ini 📚</p>
                        </template>
                    </div>
                </template>
            </x-modal>

            {{-- Modal Edit Buku --}}
            <x-modal title="Edit Buku" show="openModal" class="overflow-y-auto">
                <div class="max-h-[80vh] overflow-y-auto px-1">
                    <form id="formBuku" :action="'/admin/books/' + selectedBook.id" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        {{-- Judul --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" name="judul" x-model="selectedBook.judul"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Penulis --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Penulis</label>
                                <input type="text" name="penulis" x-model="selectedBook.penulis"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            {{-- Penerbit --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Penerbit</label>
                                <input type="text" name="penerbit" x-model="selectedBook.penerbit"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Tahun Terbit --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                                <input type="text" name="tahun_terbit" x-model="selectedBook.tahun_terbit"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    pattern="\d{4}" maxlength="4" placeholder="contoh: 2020" required>
                            </div>

                            {{-- Cetakan/Edisi --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Cetakan/Edisi</label>
                                <input type="text" name="cetakan_edisi" x-model="selectedBook.cetakan_edisi"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- No. Class --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">No. Class</label>
                                <input type="text" name="klasifikasi" x-model="selectedBook.klasifikasi"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            {{-- Asal --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Asal</label>
                                <input type="text" name="asal" x-model="selectedBook.asal"
                                    class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                        </div>


                        {{-- Cover --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Cover (Opsional)</label>
                            <input type="hidden" name="remove_cover" x-model="selectedBook.remove_cover">

                            <div class="mt-3 text-center" x-show="selectedBook.cover_url || preview">
                                <img :src="preview || selectedBook.cover_url" alt="Preview Cover"
                                    class="max-h-48 rounded-lg border-2 border-dashed p-2 mb-3 mx-auto shadow-sm bg-gray-50">

                                <button type="button"
                                    @click="preview = null; selectedBook.cover_url = null; selectedBook.remove_cover = 1; $refs.coverInput.value = null;"
                                    class="bg-red-500 text-white px-4 py-1 rounded-lg text-sm hover:bg-red-600 transition">
                                    Hapus Gambar
                                </button>
                            </div>

                            <input type="file" x-ref="coverInput" name="cover_image" accept="image/*"
                                class="w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                @change="previewImage($event)">
                        </div>

                        {{-- Keterangan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea name="keterangan" rows="4" x-model="selectedBook.keterangan"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" @click="openModal = false"
                                class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                                Batal
                            </button>
                            <button type="submit" form="formBuku"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
</x-app-layout>

{{-- Script preview cover --}}
<script>
    function bookModal() {
        return {
            openModal: false,
            openDetailModal: false,
            mode: 'create',
            selectedBook: {},
            selectedDetail: null,
            preview: null,

            openCreate() {
                this.mode = 'create';
                this.selectedBook = {
                    remove_cover: 0
                }; // default jangan hapus
                this.preview = null;
                this.openModal = true;
            },

            openEdit(book) {
                this.mode = 'edit';
                this.selectedBook = {
                    ...book,
                    remove_cover: 0, // default jangan hapus
                    cover_url: book.cover_image ?
                        "{{ asset('storage') }}/" + book.cover_image : null
                };
                this.preview = null;
                this.openModal = true;
            },

            async openDetail(id) {
                this.selectedDetail = null;
                this.openDetailModal = true;

                try {
                    const res = await fetch(`/admin/books/${id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('Gagal memuat detail buku');
                    this.selectedDetail = await res.json();
                } catch (err) {
                    alert(err.message);
                    this.openDetailModal = false;
                }
            },

            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    this.selectedBook.remove_cover = 0; // kalau upload cover baru, jangan hapus
                    const reader = new FileReader();
                    reader.onload = e => {
                        this.preview = e.target.result
                    }
                    reader.readAsDataURL(file);
                } else {
                    this.preview = null;
                }
            }
        }
    }
</script>