<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Siswa</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reports as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report->siswa->nama }}</td>
                <td>{{ $report->book->judul }}</td>
                <td>{{ $report->tanggal_pinjam }}</td>
                <td>{{ $report->tanggal_kembali }}</td>
                <td>{{ ucfirst($report->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>