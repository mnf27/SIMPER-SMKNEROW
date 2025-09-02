<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 flex items-center gap-2">
            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Data Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-10 max-w-6xl mx-auto space-y-6">

        {{-- Card Daftar Peminjaman --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 gap-4">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    📚 Daftar Peminjaman
                </h3>
                <a href="{{ route('loan.export.excel') }}"
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5 rounded-xl shadow-md transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v9m-4-4l4 4 4-4"/>
                    </svg>
                    Export Excel
                </a>
            </div>

            <div class="p-6 overflow-x-auto" id="loan-table">
                {{-- Tabel ada di partial --}}
                @include('admin.loans.table', ['loans' => $loans])
            </div>
        </div>
    </div>

    {{-- Script auto-refresh pakai jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setInterval(function () {
            $("#loan-table").load("{{ route('admin.loans.table') }}");
        }, 5000); // refresh tiap 5 detik
    </script>
</x-app-layout>
