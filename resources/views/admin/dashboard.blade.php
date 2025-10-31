<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Welcome --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-semibold flex items-center gap-2 text-gray-700 dark:text-gray-200">Selamat datang, {{ auth()->user()->nama }}</h3>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mb-6">
                {{-- Statistik --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div
                        class="bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                        <h4 class="font-medium text-blue-800 dark:text-blue-300">Total Buku</h4>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $jumlah_buku }}</p>
                    </div>

                    <div
                        class="bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl p-4">
                        <h4 class="font-medium text-green-800 dark:text-green-300">Peminjaman Aktif</h4>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $peminjaman_aktif }}
                        </p>
                    </div>

                    <div
                        class="bg-purple-100 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-xl p-4">
                        <h4 class="font-medium text-purple-800 dark:text-purple-300">Jumlah Guru</h4>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $jumlah_guru }}
                        </p>
                    </div>

                    <div
                        class="bg-pink-100 dark:bg-pink-900/30 border border-pink-200 dark:border-pink-700 rounded-xl p-4">
                        <h4 class="font-medium text-pink-800 dark:text-pink-300">Jumlah Siswa</h4>
                        <p class="text-3xl font-bold text-pink-600 dark:text-pink-400 mt-1">{{ $jumlah_siswa }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>