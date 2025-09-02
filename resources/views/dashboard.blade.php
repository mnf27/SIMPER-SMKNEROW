<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m-5 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
            {{-- Welcome Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    👋 Selamat datang, {{ auth()->user()->name }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    Senang melihatmu kembali. Semoga harimu menyenangkan!
                </p>
            </div>

            {{-- Statistik Card --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow rounded-2xl p-5">
                    <p class="text-lg font-medium">Total Buku</p>
                    <h4 class="text-3xl font-bold mt-2">120</h4>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white shadow rounded-2xl p-5">
                    <p class="text-lg font-medium">Peminjaman Aktif</p>
                    <h4 class="text-3xl font-bold mt-2">35</h4>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow rounded-2xl p-5">
                    <p class="text-lg font-medium">Anggota</p>
                    <h4 class="text-3xl font-bold mt-2">58</h4>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
