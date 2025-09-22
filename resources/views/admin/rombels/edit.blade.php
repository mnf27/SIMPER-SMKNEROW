<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Edit Rombel
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <form method="POST" action="{{ route('admin.rombels.update', $rombel->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Rombel
                    </label>
                    <input type="text" name="nama"
                           value="{{ old('nama', $rombel->nama) }}"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                           required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tingkat
                    </label>
                    <select name="tingkat"
                            class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                            required>
                        <option value="10" {{ $rombel->tingkat == 10 ? 'selected' : '' }}>X</option>
                        <option value="11" {{ $rombel->tingkat == 11 ? 'selected' : '' }}>XI</option>
                        <option value="12" {{ $rombel->tingkat == 12 ? 'selected' : '' }}>XII</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jurusan
                    </label>
                    <input type="text" name="jurusan"
                           value="{{ old('jurusan', $rombel->jurusan) }}"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white"
                           required>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                        Update
                    </button>
                    <a href="{{ route('admin.rombels.index') }}"
                       class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
