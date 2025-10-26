<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">

            {{-- Form Import Guru --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-700 dark:text-gray-200">
                    Import Guru
                </h3>
                <form action="{{ route('admin.import.guru') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="file" name="file" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <x-primary-button>Upload</x-primary-button>
                </form>
            </div>

            {{-- Table Guru --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    Data Guru Terbaru
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 py-2 border">Nama</th>
                                <th class="px-3 py-2 border">Username</th>
                                <th class="px-3 py-2 border">NIP</th>
                                <th class="px-3 py-2 border">NUPTK</th>
                                <th class="px-3 py-2 border">Jenis Kelamin</th>
                                <th class="px-3 py-2 border">Status Kepegawaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $g)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 border">{{ $g->user->nama }}</td>
                                    <td class="px-3 py-2 border font-mono">{{ $g->user->username }}</td>
                                    <td class="px-3 py-2 border">{{ $g->nip }}</td>
                                    <td class="px-3 py-2 border">{{ $g->nuptk }}</td>
                                    <td class="px-3 py-2 border">{{ $g->jenis_kelamin }}</td>
                                    <td class="px-3 py-2 border">{{ $g->status_kepegawaian }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-gray-500">Belum ada data guru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $guru->links() }}
                    </div>
                </div>
            </div>

            {{-- Form Import Siswa --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold flex items-center gap-2 mb-4 text-gray-700 dark:text-gray-200">
                    Import Siswa
                </h3>
                <form action="{{ route('admin.import.siswa') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <input type="file" name="file" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" />
                    <x-primary-button>Upload</x-primary-button>
                </form>
            </div>

            {{-- Table Siswa --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    Data Siswa Terbaru
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 py-2 border">Nama</th>
                                <th class="px-3 py-2 border">Username</th>
                                <th class="px-3 py-2 border">NIPD</th>
                                <th class="px-3 py-2 border">Jenis Kelamin</th>
                                <th class="px-3 py-2 border">NISN</th>
                                <th class="px-3 py-2 border">Rombel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $s)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 border">{{ $s->user->nama }}</td>
                                    <td class="px-3 py-2 border font-mono">{{ $s->user->username }}</td>
                                    <td class="px-3 py-2 border">{{ $s->nipd }}</td>
                                    <td class="px-3 py-2 border">{{ $s->jenis_kelamin }}</td>
                                    <td class="px-3 py-2 border">{{ $s->nisn }}</td>
                                    <td class="px-3 py-2 border">{{ $s->rombel->nama }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-gray-500">Belum ada data siswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $siswa->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>