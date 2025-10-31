<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Rombel;
use App\Models\User;
use App\Models\Buku;
use App\Models\Eksemplar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $rombels = Rombel::all();
        $bukus = Buku::select('id', 'judul')->get();

        // Update otomatis status "terlambat"
        Peminjaman::where('status', 'aktif')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->update(['status' => 'terlambat']);

        // Ambil data peminjaman beserta relasi user dan buku
        $loans = Peminjaman::with(['user', 'eksemplar.buku'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('eksemplar.buku', fn($b) => $b->where('judul', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $eksemplars = Eksemplar::with('buku')->where('status', 'tersedia')->get();

        return view('admin.loans.index', compact('loans', 'status', 'rombels', 'bukus', 'eksemplars'));
    }

    public function confirm($id)
    {
        $peminjaman = Peminjaman::with(['eksemplar.buku', 'user'])->findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        // Tentukan tanggal pinjam dan tanggal kembali
        $tanggalPinjam = now();
        $tanggalKembali = null;

        if ($peminjaman->user->role === 'siswa') {
            // Siswa hanya boleh pinjam untuk hari ini
            $tanggalKembali = $tanggalPinjam;
        } elseif ($peminjaman->user->role === 'guru') {
            // Guru bebas, tidak ada batas waktu (biarkan null)
            $tanggalKembali = null;
        }

        // Update status peminjaman
        $peminjaman->update([
            'status' => 'aktif',
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
        ]);

        // Ubah status eksemplar menjadi "dipinjam"
        if ($peminjaman->eksemplar) {
            $peminjaman->eksemplar->update(['status' => 'dipinjam']);
        }

        return back()->with('success', 'Peminjaman berhasil dikonfirmasi.');
    }

    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        $peminjaman->update(['status' => 'ditolak']); // pastikan enum 'ditolak' sudah ada di migration

        return back()->with('info', 'Peminjaman berhasil ditolak.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'eksemplar_id' => 'required|exists:eksemplar,id',
            'tanggal_pinjam' => 'required|date',
            'jumlah' => 'nullable|integer|min:1',
        ]);

        $user = User::findOrFail($request->id_user);
        $eksemplar = Eksemplar::findOrFail($request->eksemplar_id);

        // Cegah jika eksemplar tidak tersedia
        if ($eksemplar->status !== 'tersedia') {
            return back()->with('error', 'Eksemplar ini sedang dipinjam atau tidak tersedia.');
        }

        // Batas peminjaman untuk siswa
        if ($user->role === 'siswa') {
            // Hitung total peminjaman yang dilakukan hari ini
            $jumlahHariIni = Peminjaman::where('id_user', $user->id)
                ->whereDate('tanggal_pinjam', Carbon::today())
                ->count();

            if ($jumlahHariIni >= 2) {
                return back()->with('error', 'Siswa ini sudah mencapai batas 2 kali peminjaman hari ini.');
            }

            $masihMeminjam = Peminjaman::where('id_user', $user->id)
                ->whereIn('status', ['aktif', 'terlambat'])
                ->exists();

            if ($masihMeminjam) {
                return back()->with('error', 'Siswa ini masih memiliki buku yang belum dikembalikan, tidak dapat meminjam lagi.');
            }
        }

        // Tanggal kembali
        if ($user->role === 'guru') {
            $stokTersedia = Eksemplar::where('buku_id', $eksemplar->buku_id)
                ->where('status', 'tersedia')
                ->count();

            $jumlahPinjam = $request->jumlah ?? 1;

            if ($jumlahPinjam > $stokTersedia) {
                return back()->with('error', "Stok buku tidak mencukupi. Tersedia hanya $stokTersedia eksemplar.");
            }

            $eksemplarList = Eksemplar::where('buku_id', $eksemplar->buku_id)
                ->where('status', 'tersedia')
                ->take($jumlahPinjam)
                ->get();

            foreach ($eksemplarList as $e) {
                Peminjaman::create([
                    'id_user' => $user->id,
                    'eksemplar_id' => $e->id,
                    'tanggal_pinjam' => $request->tanggal_pinjam,
                    'tanggal_kembali' => null, // guru tidak dibatasi waktu
                    'status' => 'aktif',
                    'jumlah' => 1,
                ]);
                $e->update(['status' => 'dipinjam']);
            }

            return redirect()->route('admin.loans.index')->with('success', 'Peminjaman berhasil dibuat.');
        }

        // Simpan data peminjaman
        Peminjaman::create([
            'id_user' => $user->id,
            'eksemplar_id' => $eksemplar->id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_pinjam,
            'status' => 'aktif',
            'jumlah' => 1,
        ]);

        // Ubah status eksemplar menjadi "dipinjam"
        $eksemplar->update(['status' => 'dipinjam']);

        return redirect()->route('admin.loans.index')
            ->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function returnMultiple(Request $request)
    {
        if (!$request->has('loan_ids') || !is_array($request->loan_ids) || count($request->loan_ids) === 0) {
            return back()->with('error', 'Tidak ada peminjaman yang dipilih.');
        }

        $loanIds = $request->loan_ids;

        // Ambil data peminjaman yang masih aktif atau terlambat saja
        $loans = Peminjaman::with('eksemplar.buku')
            ->whereIn('id', $loanIds)
            ->whereIn('status', ['aktif', 'terlambat'])
            ->get();

        if ($loans->isEmpty()) {
            return back()->with('error', 'Semua peminjaman yang dipilih sudah dikembalikan.');
        }

        foreach ($loans as $loan) {
            $loan->update([
                'status' => 'dikembalikan',
                'tanggal_dikembalikan' => Carbon::now(),
            ]);

            if ($loan->eksemplar) {
                $loan->eksemplar->update(['status' => 'tersedia']);

                $buku = $loan->eksemplar->buku;
                if ($buku) {
                    $buku->increment('jumlah_eksemplar', 1);
                }
            }
        }

        return back()->with('success', 'Berhasil mengembalikan ' . $loans->count() . ' peminjaman.');
    }

    public function getGuru()
    {
        return response()->json(User::where('role', 'guru')->select('id', 'nama')->get());
    }

    public function getSiswa($rombelId)
    {
        return response()->json(
            User::where('role', 'siswa')
                ->whereHas('siswa', fn($q) => $q->where('id_rombel', $rombelId))
                ->select('id', 'nama')
                ->get()
        );
    }

    public function checkLimit($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        // Guru tidak dibatasi
        if ($user->role === 'guru') {
            return response()->json(['allowed' => true]);
        }

        // Hitung total peminjaman hari ini
        $jumlahHariIni = Peminjaman::where('id_user', $user->id)
            ->whereDate('tanggal_pinjam', Carbon::today())
            ->count();

        if ($jumlahHariIni >= 2) {
            return response()->json([
                'allowed' => false,
                'message' => 'Siswa ini sudah mencapai batas maksimal 2 kali peminjaman hari ini.'
            ]);
        }

        return response()->json(['allowed' => true]);
    }

    public function checkDuplicate($userId, $bukuId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        if ($user->role !== 'siswa') {
            return response()->json(['allowed' => true]);
        }

        $masihMeminjam = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['aktif', 'terlambat'])
            ->exists();

        if ($masihMeminjam) {
            return response()->json([
                'allowed' => false,
                'message' => 'Siswa ini masih memiliki buku yang belum dikembalikan, tidak dapat meminjam lagi.',
            ]);
        }

        return response()->json(['allowed' => true]);
    }

    public function getEksemplar($bukuId)
    {
        $eksemplar = Eksemplar::with('buku')
            ->where('buku_id', $bukuId)
            ->where('status', 'tersedia')
            ->select('id', 'no_induk', 'buku_id')
            ->get();

        return response()->json($eksemplar);
    }
}