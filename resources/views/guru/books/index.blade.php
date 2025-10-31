<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Buku') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ openPinjam: false, selectedBook: null, eksemplars: [] }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Daftar Buku
                    </h3>

                    {{-- Pencarian --}}
                    <form method="GET" action="{{ route('guru.books.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}"
                        class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Cari
                        </button>

                        {{-- Tombol Batalkan hanya muncul kalau ada parameter pencarian --}}
                        @if (request('search'))
                            <a href="{{ route('guru.books.index') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                                Batalkan
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                {{-- Table Buku --}}
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-2 px-3 border text-center">#</th>
                                <th class="py-2 px-3 border">Cover</th>
                                <th class="py-2 px-3 border">Judul</th>
                                <th class="py-2 px-3 border">Penulis</th>
                                <th class="py-2 px-3 border">Penerbit</th>
                                <th class="py-2 px-3 border">Tahun</th>
                                <th class="py-2 px-3 border text-center">Jumlah Eksemplar</th>
                                <th class="py-2 px-3 border">Keterangan</th>
                                <th class="py-2 px-3 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($buku as $index => $item)
                                <tr class="border-b border-gray-300 dark:border-gray-700">
                                    <td class="py-2 px-3 border text-center">{{ $buku->firstItem() + $index }}</td>
                                    <td class="py-2 px-3 border">
                                        @if ($item->cover_image)
                                            <img src="{{ asset('storage/' . $item->cover_image) }}"
                                                alt="Cover {{ $item->judul }}"
                                                class="h-16 w-auto mx-auto rounded shadow" />
                                        @else
                                            <img src="{{ asset('images/default_cover.png' . $item->cover_image) }}"
                                                alt="Cover {{ $item->judul }}"
                                                class="h-16 w-auto mx-auto rounded shadow"/>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 border">{{ $item->judul }}</td>
                                    <td class="py-2 px-3 border">{{ $item->penulis ?? '-' }}</td>
                                    <td class="py-2 px-3 border">{{ $item->penerbit ?? '-' }}</td>
                                    <td class="py-2 px-3 border">{{ $item->tahun_terbit ?? '-' }}</td>
                                    <td class="py-2 px-3 border text-center">{{ $item->eksemplar_tersedia }}</td>
                                    <td class="py-2 px-3 border text-center">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="py-2 px-3 border">
                                        <button
                                            @click="
                                                openPinjam = true;
                                                selectedBook = {{ $item->id }};
                                                fetch(`/guru/books/${selectedBook}/eksemplar`)
                                                    .then(res => res.json())
                                                    .then(data => eksemplars = data);
                                            "
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg"
                                            {{ $item->jumlah_eksemplar <= 0 ? 'disabled' : '' }}>
                                            Pinjam
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        Tidak ada data buku.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $buku->links() }}
                </div>
            </div>
        </div>

        {{-- Modal Pilih Eksemplar --}}
        <x-modal title="Pilih Eksemplar Buku" show="openPinjam">
            <template x-if="eksemplars.length > 0">
                <form :action="`/guru/books/pinjam/${selectedBook}`" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Pilih No Induk Eksemplar
                        </label>
                        <select name="eksemplar_id"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-100">
                            <template x-for="e in eksemplars" :key="e.id">
                                <option :value="e.id" x-text="e.no_induk"></option>
                            </template>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" @click="openPinjam = false"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Konfirmasi Pinjam
                        </button>
                    </div>
                </form>
            </template>

            <template x-if="eksemplars.length === 0">
                <p class="text-center text-gray-500 py-4">Tidak ada eksemplar tersedia.</p>
            </template>
        </x-modal>
    </div>
</x-app-layout>
