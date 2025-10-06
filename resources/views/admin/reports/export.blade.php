{{-- resources/views/admin/reports/export.blade.php --}}
<table border="1" cellspacing="0" cellpadding="6" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th style="font-weight:bold; background:#e5e7eb;">No</th>
            <th style="font-weight:bold; background:#e5e7eb;">Nama Peminjam</th>
            <th style="font-weight:bold; background:#e5e7eb;">Role</th>
            <th style="font-weight:bold; background:#e5e7eb;">Rombel</th>
            <th style="font-weight:bold; background:#e5e7eb;">Judul Buku</th>
            <th style="font-weight:bold; background:#e5e7eb;">Kategori</th>
            <th style="font-weight:bold; background:#e5e7eb;">Tanggal Pinjam</th>
            <th style="font-weight:bold; background:#e5e7eb;">Tanggal Kembali</th>
            <th style="font-weight:bold; background:#e5e7eb;">Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($peminjaman as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->user->nama ?? '-' }}</td>
                <td>{{ ucfirst($item->user->role ?? '-') }}</td>
                <td>{{ $item->user->siswa->rombel->nama ?? '-' }}</td>
                <td>{{ $item->buku->judul ?? '-' }}</td>
                <td>{{ $item->buku->category->nama ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center;">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>