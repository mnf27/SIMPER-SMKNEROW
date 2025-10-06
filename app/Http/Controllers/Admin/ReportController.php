<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Rombel;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LoanExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku.kategori', 'user.siswa.rombel'])
            ->orderBy('tanggal_pinjam', 'desc');

        // filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pinjam', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        // filter user
        if ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        // filter rombel (via relasi siswa)
        if ($request->filled('rombel_id')) {
            $query->whereHas('user.siswa', function ($q) use ($request) {
                $q->where('id_rombel', $request->rombel_id);
            });
        }

        // filter kategori
        if ($request->filled('category_id')) {
            $query->whereHas('buku', function ($q) use ($request) {
                $q->where('id_kategori', $request->category_id);
            });
        }

        // filter buku
        if ($request->filled('book_id')) {
            $query->where('id_buku', $request->book_id);
        }

        $peminjaman = $query->paginate(15);

        // chart batang
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

        // Chart pie
        $kategoriData = Peminjaman::select('buku.id_kategori', DB::raw('COUNT(*) as total'))
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->groupBy('buku.id_kategori')
            ->pluck('total', 'buku.id_kategori')
            ->toArray();

        $kategoriLabels = [];
        $kategoriJumlah = [];
        foreach ($kategoriData as $idKategori => $total) {
            $kategori = Kategori::find($idKategori);
            $kategoriLabels[] = $kategori->nama ?? 'Lainnya';
            $kategoriJumlah[] = $total;
        }

        // ===== Grafik Buku Terpopuler =====
        $topBooks = Peminjaman::select('buku.id', 'buku.judul', DB::raw('COUNT(*) as total'))
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->groupBy('buku.id', 'buku.judul')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topBookLabels = $topBooks->pluck('judul')->toArray();
        $topBookCounts = $topBooks->pluck('total')->toArray();

        $users = User::select('id', 'nama')->get();
        $rombels = Rombel::select('id', 'nama')->get();
        $categories = Kategori::select('id', 'nama')->get();
        $bukus = Buku::select('id', 'judul')->get();

        return view('admin.reports.index', compact(
            'peminjaman',
            'users',
            'rombels',
            'categories',
            'bukus',
            'bulanLabels',
            'jumlahData',
            'kategoriLabels',
            'kategoriJumlah',
            'topBookLabels',
            'topBookCounts'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(new LoanExport($request), 'laporan_peminjaman.xlsx');
    }
}
