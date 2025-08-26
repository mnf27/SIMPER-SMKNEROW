<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Peminjaman Buku
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Daftar Peminjaman</h3>
                <a href="{{ route('loan.export.excel') }}"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Export Excel
                </a>
            </div>

            <div class="p-6 overflow-x-auto" id="loan-table">
                {{-- tabel kita taruh di file partial --}}
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