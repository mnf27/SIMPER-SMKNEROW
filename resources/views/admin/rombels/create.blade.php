<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            🏫 {{ __('Tambah Rombel') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6">
                <form method="POST" action="{{ route('admin.rombels.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nama Rombel --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Rombel
                        </label>
                        <input type="text" name="nama" required
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                    </div>

                    {{-- Tingkat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tingkat
                        </label>
                        <select name="tingkat" required
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                        </select>
                    </div>

                    {{-- Jurusan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Jurusan
                        </label>
                        <input type="text" name="jurusan" required
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3">
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl shadow hover:opacity-90 transition">
                            💾 Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>