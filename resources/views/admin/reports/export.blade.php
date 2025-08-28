<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Peminjam</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($report->user->siswa)
                        {{ $report->user->siswa->nama }} (Siswa)
                    @elseif ($report->user->guru)
                        {{ $report->user->guru->nama }} (Guru)
                    @else
                        {{ $report->user->name }}
                    @endif
                </td>
                <td>{{ $report->book->judul ?? '-' }}</td>
                <td>{{ $report->tanggal_peminjaman }}</td>
                <td>{{ $report->tanggal_jatuh_tempo }}</td>
                <td>{{ $report->tanggal_kembali ?? '-' }}</td>
                <td>{{ ucfirst($report->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>