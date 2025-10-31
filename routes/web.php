<?php

use App\Http\Controllers\Admin\ImportUserController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard per role
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/guru/dashboard', [GuruDashboardController::class, 'index'])
        ->middleware('role:guru')
        ->name('guru.dashboard');

    Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])
        ->middleware('role:siswa')
        ->name('siswa.dashboard');

    // =====================
    // ROUTE ADMIN
    // =====================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/import', [ImportUserController::class, 'index'])->name('import.index');
        Route::post('/import-guru', [ImportUserController::class, 'importGuru'])->name('import.guru');
        Route::post('/import-siswa', [ImportUserController::class, 'importSiswa'])->name('import.siswa');

        Route::resource('rombels', RombelController::class);

        Route::resource('books', BookController::class);
        Route::post('books/import', [BookController::class, 'import'])->name('books.import');
        Route::post('{book}/eksemplar', [BookController::class, 'addEksemplar'])->name('eksemplar.store');
        Route::delete('eksemplar/{id}', [BookController::class, 'deleteEksemplar'])->name('eksemplar.destroy');

        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::post('/loans/store', [LoanController::class, 'store'])->name('loans.store');
        Route::get('/loans/get-guru', [LoanController::class, 'getGuru'])->name('loans.getGuru');
        Route::get('/loans/get-siswa/{rombel}', [LoanController::class, 'getSiswa'])->name('loans.getSiswa');
        Route::get('/loans/check-limit/{userId}', [LoanController::class, 'checkLimit'])->name('loans.checkLimit');
        Route::get('/loans/check-duplicate/{userId}/{bukuId}', [LoanController::class, 'checkDuplicate'])->name('loans.checkDuplicate');
        Route::get('/loans/get-eksemplar/{bukuId}', [LoanController::class, 'getEksemplar'])->name('loans.getEksemplar');
        Route::post('/loans/return-multiple', [LoanController::class, 'returnMultiple'])->name('loans.return-multiple');
        Route::post('/loans/{id}/confirm', [LoanController::class, 'confirm'])->name('loans.confirm');
        Route::post('/loans/{id}/reject', [LoanController::class, 'reject'])->name('loans.reject');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // =====================
    // ROUTE GURU
    // =====================
    Route::middleware('role:guru')->group(function () {
        Route::get('/guru/books', [\App\Http\Controllers\Guru\BookController::class, 'index'])->name('guru.books.index');
        Route::get('/guru/books/{id}/eksemplar', [\App\Http\Controllers\Guru\BookController::class, 'getEksemplar'])
            ->name('guru.books.eksemplar');
        Route::post('/guru/books/pinjam/{id}', [\App\Http\Controllers\Guru\LoanController::class, 'store'])->name('guru.books.pinjam');
        Route::get('/guru/history', [\App\Http\Controllers\Guru\LoanController::class, 'history'])->name('guru.loans.history');
    });

    // =====================
    // ROUTE SISWA
    // =====================
    Route::middleware('role:siswa')->group(function () {
        Route::get('/siswa/books', [\App\Http\Controllers\Siswa\BookController::class, 'index'])->name('siswa.books.index');
        Route::get('/siswa/books/{id}/eksemplar', [\App\Http\Controllers\Siswa\BookController::class, 'getEksemplar'])
            ->name('siswa.books.eksemplar');
        Route::post('/siswa/books/pinjam/{id}', [\App\Http\Controllers\Siswa\LoanController::class, 'store'])->name('siswa.books.pinjam');
        Route::get('/siswa/history', [\App\Http\Controllers\Siswa\LoanController::class, 'history'])->name('siswa.loans.history');
    });
});

require __DIR__ . '/auth.php';