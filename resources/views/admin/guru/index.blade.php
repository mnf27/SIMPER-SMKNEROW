<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kelola Guru
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Import Data --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
                @csrf
                <input type="file" name="file"
                       class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg px-4 py-2 w-full md:w-1/2 text-sm"
                       required>
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                    Import Data
                </button>
            </form>
        </div>

        {{-- Tabel Data Guru --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 overflow-x-auto">
            <table class="min-w-full border border-gray-200 dark:border-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">NIP</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Alamat</th>
                        <th class="px-4 py-2 text-left">No. Telp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $guru)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-2">{{ $guru->nip }}</td>
                            <td class="px-4 py-2">{{ $guru->nama }}</td>
                            <td class="px-4 py-2">{{ $guru->email }}</td>
                            <td class="px-4 py-2">{{ $guru->alamat }}</td>
                            <td class="px-4 py-2">{{ $guru->no_telp }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                Belum ada data guru
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
