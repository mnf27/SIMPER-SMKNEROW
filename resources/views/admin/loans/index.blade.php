<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ 
            openTambah: false 
        }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6">

                {{-- Header card --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        Daftar Peminjaman
                    </h3>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Filter --}}
                        <form method="GET" class="flex flex-wrap items-center gap-2">
                            <select name="status" onchange="this.form.submit()"
                                class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                                    Dikembalikan</option>
                                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>
                                    Terlambat</option>
                            </select>

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau judul"
                                class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Cari
                            </button>
                        </form>

                        {{-- Tombol tambah --}}
                        <button @click="openTambah = true"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            + Tambah Peminjaman
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.loans.return-multiple') }}" method="POST" id="mass-return-form">
                    @csrf
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                        <button type="submit" onclick="return confirm('Yakin ingin mengembalikan semua yang dipilih?')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Kembalikan
                        </button>
                    </div>

                    {{-- Tabel --}}
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="py-2 px-3 text-center">
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th class="py-2 px-3">Peminjam</th>
                                <th class="py-2 px-3">Role</th>
                                <th class="py-2 px-3">Judul Buku</th>
                                <th class="py-2 px-3">No. Induk</th>
                                <th class="py-2 px-3">Tgl Pinjam</th>
                                <th class="py-2 px-3">Jatuh Tempo</th>
                                <th class="py-2 px-3">Dikembalikan</th>
                                <th class="py-2 px-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loans as $loan)
                                <tr class="border-b border-gray-300 dark:border-gray-700">
                                    <td class="py-2 px-3 text-center">
                                        @if(in_array($loan->status, ['aktif', 'terlambat']))
                                            <input type="checkbox" name="loan_ids[]" value="{{ $loan->id }}"
                                                class="loan-checkbox">
                                        @endif
                                    </td>
                                    <td class="py-2 px-3">{{ $loan->user->nama }}</td>
                                    <td class="py-2 px-3">{{ ucfirst($loan->user->role) }}</td>
                                    <td class="py-2 px-3">{{ $loan->eksemplar->buku->judul }}</td>
                                    <td class="py-2 px-3">{{ $loan->eksemplar->no_induk }}</td>
                                    <td class="py-2 px-3">{{ $loan->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="py-2 px-3">{{ $loan->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-2 px-3">{{ $loan->tanggal_dikembalikan?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                                        @if($loan->status == 'aktif') bg-blue-100 text-blue-700
                                                        @elseif($loan->status == 'dikembalikan') bg-green-100 text-green-700
                                                        @elseif($loan->status == 'terlambat') bg-red-100 text-red-700 @endif">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">Belum ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $loans->appends(request()->query())->links() }}</div>
                </form>
            </div>

            {{-- Modal Tambah --}}
            <x-modal title="Tambah Peminjaman Buku" show="openTambah">
                <form action="{{ route('admin.loans.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilih Role</label>
                            <select id="role-select" name="role"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Role --</option>
                                <option value="guru">Guru</option>
                                <option value="siswa">Siswa</option>
                            </select>
                        </div>
                        <div id="rombel-wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Pilih Rombel</label>
                            <select id="rombel-select"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Rombel --</option>
                                @foreach ($rombels as $rombel)
                                    <option value="{{ $rombel->id }}">{{ $rombel->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="user-wrapper" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Nama Peminjam</label>
                        <select name="id_user" id="user-select"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Peminjam --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pilih Buku</label>
                        <select id="buku-select"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($bukus as $buku)
                                <option value="{{ $buku->id }}">{{ $buku->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div id="eksemplar-wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Pilih No. Induk</label>
                            <select name="eksemplar_id" id="eksemplar-select"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih No. Induk --</option>
                            </select>
                        </div>
                        <div id="jumlah-wrapper" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">Jumlah Buku</label>
                            <input type="number" name="jumlah" id="jumlah-input" min="1" value="1"
                                class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" value="{{ now()->toDateString() }}"
                            class="w-full mt-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" @click="openTambah=false"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>

{{-- ========== SCRIPT INTERAKSI ========== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.getElementById('role-select');
        const rombelWrapper = document.getElementById('rombel-wrapper');
        const rombelSelect = document.getElementById('rombel-select');
        const userWrapper = document.getElementById('user-wrapper');
        const userSelect = document.getElementById('user-select');
        const bukuSelect = document.getElementById('buku-select');
        const eksemplarWrapper = document.getElementById('eksemplar-wrapper');
        const eksemplarSelect = document.getElementById('eksemplar-select');
        const jumlahWrapper = document.getElementById('jumlah-wrapper');

        // Pilih role
        roleSelect.addEventListener('change', async () => {
            const role = roleSelect.value;
            userSelect.innerHTML = `<option value="">-- Pilih Peminjam --</option>`;

            if (role === 'guru') {
                rombelWrapper.classList.add('hidden');
                userWrapper.classList.remove('hidden');
                jumlahWrapper.classList.remove('hidden');

                const res = await fetch('{{ route("admin.loans.getGuru") }}');
                const data = await res.json();
                data.forEach(g => userSelect.innerHTML += `<option value="${g.id}">${g.nama}</option>`);

            } else if (role === 'siswa') {
                userWrapper.classList.add('hidden');
                rombelWrapper.classList.remove('hidden');
                jumlahWrapper.classList.add('hidden');

            } else {
                rombelWrapper.classList.add('hidden');
                userWrapper.classList.add('hidden');
                jumlahWrapper.classList.add('hidden');
            }
        });

        // Pilih rombel → tampilkan siswa
        rombelSelect.addEventListener('change', async () => {
            const rombelId = rombelSelect.value;
            if (!rombelId) return;

            const res = await fetch(`/admin/loans/get-siswa/${rombelId}`);
            const data = await res.json();

            userSelect.innerHTML = `<option value="">-- Pilih Siswa --</option>`;
            data.forEach(s => userSelect.innerHTML += `<option value="${s.id}">${s.nama}</option>`);
            userWrapper.classList.remove('hidden');
        });

        // Cek batas peminjaman siswa
        userSelect.addEventListener('change', async () => {
            const userId = userSelect.value;
            if (!userId) return;

            try {
                const res = await fetch(`/admin/loans/check-limit/${userId}`);
                const data = await res.json();

                if (!data.allowed) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Bisa!',
                        text: data.message
                    });
                    userSelect.value = '';
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal memeriksa batas peminjaman siswa.'
                });
            }
        });

        bukuSelect.addEventListener('change', async () => {
            const bukuId = bukuSelect.value;
            const userId = userSelect.value;
            eksemplarSelect.innerHTML = `<option value="">-- Pilih No. Induk --</option>`;

            if (!bukuId) return eksemplarWrapper.classList.add('hidden');

            if (!userId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Pilih peminjam terlebih dahulu sebelum memilih buku.'
                });
                bukuSelect.value = '';
                return;
            }

            try {
                const dupRes = await fetch(`/admin/loans/check-duplicate/${userId}/${bukuId}`);
                const dupData = await dupRes.json();

                if (!dupData.allowed) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Bisa!',
                        text: dupData.message
                    });
                    bukuSelect.value = '';
                    return;
                }

                const res = await fetch(`/admin/loans/get-eksemplar/${bukuId}`);
                const data = await res.json();

                if (data.length === 0) {
                    eksemplarSelect.innerHTML = `<option value="">Tidak ada eksemplar tersedia</option>`;
                    Swal.fire({
                        icon: 'info',
                        title: 'Kosong!',
                        text: 'Tidak ada eksemplar buku yang tersedia saat ini.'
                    });
                } else {
                    data.forEach(e => eksemplarSelect.innerHTML += `<option value="${e.id}">No. ${e.no_induk}</option>`);
                }

                eksemplarWrapper.classList.remove('hidden');
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Gagal memeriksa data buku: ' + err.message
                });
            }
        });
    });
</script>

<script>
    document.getElementById('select-all')?.addEventListener('change', function () {
        document.querySelectorAll('.loan-checkbox').forEach(chk => chk.checked = this.checked);
    });

    // ========== VALIDASI AKTIFKAN TOMBOL SIMPAN ==========
    const form = document.querySelector('form[action="{{ route('admin.loans.store') }}"]');
    const submitBtn = form.querySelector('button[type="submit"]');

    function checkFormValidity() {
        const role = document.getElementById('role-select').value;
        const rombel = document.getElementById('rombel-select').value;
        const user = document.getElementById('user-select').value;
        const buku = document.getElementById('buku-select').value;
        const eksemplar = document.getElementById('eksemplar-select').value;
        const jumlah = document.getElementById('jumlah-input').value;
        const tanggal = form.querySelector('input[name="tanggal_pinjam"]').value;

        let valid = false;

        if (role === 'guru') {
            valid = role && user && buku && jumlah && tanggal;
        } else if (role === 'siswa') {
            valid = role && rombel && user && buku && eksemplar && tanggal;
        }

        if (valid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // pantau setiap perubahan field
    ['change', 'input'].forEach(evt => {
        form.addEventListener(evt, checkFormValidity);
    });

    // nonaktifkan tombol di awal
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

</script>