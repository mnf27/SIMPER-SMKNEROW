<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            🏫 {{ __('Kelola Rombel') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-6">

            {{-- Tombol tambah --}}
            <div class="flex justify-end">
                <a href="{{ route('admin.rombels.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-xl shadow hover:opacity-90 transition">
                    ➕ Tambah Rombel
                </a>
            </div>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-800 rounded-xl shadow">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Rombel --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                    📋 Daftar Rombel
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 border text-left">Nama Rombel</th>
                                <th class="px-4 py-2 border text-left">Tingkat</th>
                                <th class="px-4 py-2 border text-left">Jurusan</th>
                                <th class="px-4 py-2 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rombels as $rombel)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="border px-4 py-2">{{ $rombel->nama }}</td>
                                    <td class="border px-4 py-2">{{ $rombel->tingkat }}</td>
                                    <td class="border px-4 py-2">{{ $rombel->jurusan }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('admin.rombels.edit', $rombel) }}"
                                                class="px-3 py-1 bg-yellow-500 text-white rounded-lg text-sm shadow hover:opacity-90 transition">
                                                ✏️ Edit
                                            </a>
                                            <form action="{{ route('admin.rombels.destroy', $rombel) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm shadow hover:opacity-90 transition">
                                                    🗑️ Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-gray-500">
                                        Belum ada rombel
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $rombels->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>