<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Kelola Guru
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto">
        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-2 p-4 rounded-xl bg-green-100 border border-green-300 text-green-800">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Form Upload Excel --}}
        <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow p-6">
            <form action="{{ route('guru.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4">
                @csrf
                <input type="file" name="file" required
                       class="block w-full sm:w-auto text-sm text-gray-700 dark:text-gray-200
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-blue-50 file:text-blue-600
                              hover:file:bg-blue-100 cursor-pointer">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl shadow-md transition font-medium">
                    Import Excel
                </button>
            </form>
        </div>

        {{-- Tabel Guru --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="px-4 py-3">NIP</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $g)
                        <tr class="border-b last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 font-medium">{{ $g->nip }}</td>
                            <td class="px-4 py-3">{{ $g->nama }}</td>
                            <td class="px-4 py-3">{{ $g->user->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data guru
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
