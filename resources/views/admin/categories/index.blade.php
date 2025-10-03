<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
            </svg>
            Kelola Kategori Buku
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto">
        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-100 border border-green-300 text-green-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tombol Tambah --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.categories.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                + Tambah Kategori
            </a>
        </div>

        {{-- Tabel --}}
        <div
            class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead
                    class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Id</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-b last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium">{{ $category->nama }}</td>
                            <td class="px-4 py-3 flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="px-3 py-1.5 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-white font-medium transition shadow">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition shadow">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>