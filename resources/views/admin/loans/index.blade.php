<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Kelola Peminjaman
        </h2>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto space-y-6">
        {{-- Card Daftar Peminjaman --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">

            {{-- Header --}}
            <div
                class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 gap-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    📚 Daftar Peminjaman
                </h3>

                <div class="flex items-center gap-3">
                    {{-- Filter Status --}}
                    <form method="GET" class="flex items-center">
                        <select name="status" onchange="this.form.submit()"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="dikembalikan" {{ $status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                            </option>
                            <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </form>

                    {{-- Tombol Tambah --}}
                    <div x-data="{ open: false }">
                        <button @click="open = true"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Peminjaman
                        </button>

                        {{-- Modal Tambah --}}
                        <div x-show="open" x-cloak
                            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                            <div @click.away="open = false"
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 w-full max-w-xl p-6">

                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                        📘 Tambah Peminjaman Buku
                                    </h3>
                                    <button @click="open = false"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">✖</button>
                                </div>

                                {{-- Form --}}
                                <form action="{{ route('admin.loans.store') }}" method="POST" class="space-y-6">
                                    @csrf

                                    {{-- Pilih User --}}
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Pilih
                                            User</label>
                                        <select name="id_user"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm">
                                            <option value="">-- Pilih User --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_user')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Pilih Buku --}}
                                    <div>
                                        <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Pilih
                                            Buku</label>
                                        <select name="id_buku"
                                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm">
                                            <option value="">-- Pilih Buku --</option>
                                            @foreach($books as $book)
                                                <option value="{{ $book->id }}">{{ $book->judul }} (stok:
                                                    {{ $book->jumlah_eksemplar }})</option>
                                            @endforeach
                                        </select>
                                        @error('id_buku')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Pinjam & Jatuh Tempo --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Tanggal
                                                Pinjam</label>
                                            <input type="date" name="tanggal_pinjam" value="{{ now()->toDateString() }}"
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Jatuh
                                                Tempo</label>
                                            <input type="date" name="tanggal_kembali"
                                                value="{{ now()->addDays(7)->toDateString() }}"
                                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-xl shadow-sm">
                                        </div>
                                    </div>

                                    {{-- Tombol --}}
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" @click="open = false"
                                            class="px-5 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-md transition">
                                            💾 Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Peminjaman --}}
            <div class="p-6 overflow-x-auto" id="loan-table">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2 text-center">#</th>
                            <th class="px-3 py-2 text-left">User</th>
                            <th class="px-3 py-2 text-left">Buku</th>
                            <th class="px-3 py-2 text-left">Tanggal Pinjam</th>
                            <th class="px-3 py-2 text-left">Jatuh Tempo</th>
                            <th class="px-3 py-2 text-left">Tanggal Dikembalikan</th>
                            <th class="px-3 py-2 text-center">Status</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($loans as $loan)
                            <tr>
                                <td class="px-3 py-2 text-center">{{ $loop->iteration }}</td>
                                <td class="px-3 py-2">{{ $loan->user->nama }}</td>
                                <td class="px-3 py-2">{{ $loan->buku->judul }}</td>
                                <td class="px-3 py-2">{{ $loan->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">{{ $loan->tanggal_kembali->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">{{ $loan->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($loan->status === 'aktif')
                                                bg-blue-100 text-blue-700
                                            @elseif($loan->status === 'dikembalikan')
                                                bg-green-100 text-green-700
                                            @elseif($loan->status === 'terlambat')
                                                bg-red-100 text-red-700
                                            @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center space-y-1">
                                    @if($loan->status === 'aktif')
                                        <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <x-primary-button>Konfirmasi</x-primary-button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>