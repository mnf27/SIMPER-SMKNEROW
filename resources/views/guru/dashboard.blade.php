<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Welcome --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    Selamat datang, Pak/Bu {{ auth()->user()->nama }}
                </h3>
            </div>

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    Peminjaman Saya
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-3 py-2 border">Judul Buku</th>
                                <th class="px-3 py-2 border">Tanggal Pinjam</th>
                                <th class="px-3 py-2 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman_saya as $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-3 py-2 border">{{ $p->buku->judul }}</td>
                                    <td class="px-3 py-2 border">{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                                    <td class="px-3 py-2 border capitalize">{{ $p->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-gray-500">Belum ada peminjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $peminjaman_saya->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>