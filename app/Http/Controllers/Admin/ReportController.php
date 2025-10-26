<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Rombel;
use App\Models\Eksemplar;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Peminjaman::with(['user', 'user.siswa.rombel', 'eksemplar.buku'])
            ->orderBy('tanggal_pinjam', 'desc');

        // filter tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $baseQuery->whereBetween('tanggal_pinjam', [
                $request->tanggal_awal,
                $request->tanggal_akhir,
            ]);
        }

        // filter user
        if ($request->filled('user_id')) {
            $baseQuery->where('id_user', $request->user_id);
        }

        // filter rombel (via relasi siswa)
        if ($request->filled('rombel_id')) {
            $baseQuery->whereHas('user.siswa', function ($q) use ($request) {
                $q->where('id_rombel', $request->rombel_id);
            });
        }

        // filter buku
        if ($request->filled('book_id')) {
            $baseQuery->whereHas('eksemplar.buku', function ($q) use ($request) {
                $q->where('id', $request->book_id);
            });
        }

        $statsQuery = clone $baseQuery;

        $totalPeminjaman = $statsQuery->count();
        $totalBukuDipinjam = $statsQuery->sum('jumlah');
        if ($totalBukuDipinjam == 0) {
            $totalBukuDipinjam = $totalPeminjaman;
        }

        $totalAktif = (clone $baseQuery)->where('status', 'aktif')->count();
        $totalTerlambat = (clone $baseQuery)->where('status', 'terlambat')->count();

        $peminjaman = $baseQuery->paginate(10);

        // Grafik peminjaman per bulan
        $peminjamanPerBulan = Peminjaman::select(
            DB::raw('MONTH(tanggal_pinjam) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $bulanLabels = [];
        $jumlahData = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanLabels[] = Carbon::create()->month($i)->locale('id')->translatedFormat('F');
            $jumlahData[] = $peminjamanPerBulan[$i] ?? 0;
        }

        // ===== Grafik Buku Terpopuler =====
        $topBooks = Peminjaman::select('buku.id', 'buku.judul', DB::raw('COUNT(*) as total'))
            ->join('eksemplar', 'peminjaman.eksemplar_id', '=', 'eksemplar.id')
            ->join('buku', 'eksemplar.buku_id', '=', 'buku.id')
            ->groupBy('buku.id', 'buku.judul')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topBookLabels = $topBooks->pluck('judul')->toArray();
        $topBookCounts = $topBooks->pluck('total')->toArray();

        $users = User::whereIn('role', ['guru', 'siswa'])
            ->select('id', 'nama', 'role')
            ->get();
        $rombels = Rombel::select('id', 'nama')->get();
        $bukus = Buku::select('id', 'judul')->get();

        return view('admin.reports.index', compact(
            'totalPeminjaman',
            'totalBukuDipinjam',
            'totalAktif',
            'totalTerlambat',
            'peminjaman',
            'users',
            'rombels',
            'bukus',
            'bulanLabels',
            'jumlahData',
            'topBookLabels',
            'topBookCounts'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(new LoanExport($request), 'laporan_peminjaman.xlsx');
    }
}
