<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Rombel') }}
        </h2>
    </x-slot>

    <div class="py-8" x-data="{ openTambah: false, openEdit: false, selected: { id: '', nama: '', kelas: '' } }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Daftar Rombel</h3>
                    <button @click="openTambah = true"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        + Tambah Rombel
                    </button>
                </div>

                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="py-2 px-3 border">#</th>
                            <th class="py-2 px-3 border">Tingkat</th>
                            <th class="py-2 px-3 border">Jurusan</th>
                            <th class="py-2 px-3 border">Nama Rombel</th>
                            <th class="py-2 px-3 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rombels as $rombel)
                            <tr class="border-b border-gray-300 dark:border-gray-700">
                                <td class="py-2 px-3 border">{{ $loop->iteration }}</td>
                                <td class="py-2 px-3 border">{{ $rombel->tingkat }}</td>
                                <td class="py-2 px-3 border">{{ $rombel->jurusan }}</td>
                                <td class="py-2 px-3 border">{{ $rombel->nama }}</td>
                                <td class="py-2 px-3 border text-center space-x-2">
                                    <button @click="
                                                                    openEdit = true;
                                                                    selected.id = '{{ $rombel->id }}';
                                                                    selected.tingkat = '{{ $rombel->tingkat }}';
                                                                    selected.jurusan = '{{ $rombel->jurusan }}';
                                                                    selected.nama = '{{ $rombel->nama }}';
                                                                " class="text-black-500 hover:underline">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.rombels.destroy', $rombel->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline"
                                            onclick="return confirm('Yakin ingin menghapus rombel ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada data rombel.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $rombels->links() }}</div>
            </div>

            <!-- Modal Tambah -->
            <x-modal title="Tambah Rombel" show="openTambah">
                <form action="{{ route('admin.rombels.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tingkat</label>
                        <select name="tingkat"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <input type="text" name="jurusan"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" @click="openTambah = false"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </x-modal>

            <!-- Modal Edit -->
            <x-modal title="Edit Rombel" show="openEdit">
                <form :action="`{{ url('admin/rombels') }}/${selected.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tingkat</label>
                        <select name="tingkat" x-model="selected.tingkat"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="10">X</option>
                            <option value="11">XI</option>
                            <option value="12">XII</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <input type="text" name="jurusan" x-model="selected.jurusan"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama" x-model="selected.nama"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" @click="openEdit = false"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            Update
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>