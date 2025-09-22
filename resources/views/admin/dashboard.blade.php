<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            📊 {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Welcome --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-semibold">👋 Selamat datang, {{ auth()->user()->nama }}</h3>
                <p class="text-gray-600 dark:text-gray-300">Anda login sebagai <b>Admin</b>.</p>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow rounded-2xl p-5">
                    <p>Total Buku</p>
                    <h4 class="text-3xl font-bold">{{ $jumlah_buku }}</h4>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white shadow rounded-2xl p-5">
                    <p>Peminjaman Aktif</p>
                    <h4 class="text-3xl font-bold">{{ $peminjaman_aktif }}</h4>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow rounded-2xl p-5">
                    <p>Jumlah Guru</p>
                    <h4 class="text-3xl font-bold">{{ $jumlah_guru }}</h4>
                </div>

                <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white shadow rounded-2xl p-5">
                    <p>Jumlah Siswa</p>
                    <h4 class="text-3xl font-bold">{{ $jumlah_siswa }}</h4>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>