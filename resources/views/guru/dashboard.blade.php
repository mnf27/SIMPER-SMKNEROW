<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            📚 {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Welcome --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-semibold">👋 Halo, {{ auth()->user()->nama }}</h3>
                <p class="text-gray-600 dark:text-gray-300">Anda login sebagai <b>Guru</b>.</p>
            </div>

            {{-- Riwayat Peminjaman --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6">
                <h4 class="text-lg font-semibold mb-4">📖 Peminjaman Saya</h4>
                <table class="min-w-full border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Judul Buku</th>
                            <th class="px-4 py-2">Tanggal Pinjam</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman_saya as $p)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2">{{ $p->buku->judul }}</td>
                                <td class="px-4 py-2">{{ $p->tanggal_pinjam->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 capitalize">{{ $p->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Belum ada peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
