<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('role:admin')->group(function () {
        //Kategori
        Route::resource('categories', CategoryController::class);

        // Buku
        Route::resource('books', BookController::class);
        Route::post('books/import', [BookController::class, 'import'])->name('books.import');

        // Guru
        Route::resource('guru', GuruController::class)->only(['index']);
        Route::post('guru/import', [GuruController::class, 'import'])->name('guru.import');

        // Siswa
        Route::resource('siswa', SiswaController::class)->only(['index']);
        Route::post('siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

        // Peminjaman
        Route::resource('loans', LoanController::class)->only(['index']);
        Route::get('/loans/export/excel', [LoanController::class, 'exportExcel'])->name('loan.export.excel');
        Route::get('/loans/table', [LoanController::class, 'table'])->name('admin.loans.table');

        // Report
        Route::resource('reports', ReportController::class)->only(['index']);
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::middleware('role:guru')->group(function () {
        Route::get('/guru/books', [\App\Http\Controllers\Guru\BookController::class, 'index'])->name('guru.books.index');
        Route::post('/guru/books{id}/pinjam', [\App\Http\Controllers\Guru\LoanController::class, 'store'])->name('guru.books.pinjam');
        Route::get('/history', [LoanController::class, 'history']);
    });

    Route::middleware('role:siswa')->group(function () {
        Route::get('/pinjam', [LoanController::class, 'create']);
        Route::post('/pinjam', [LoanController::class, 'store']);
        Route::get('/history', [LoanController::class, 'history']);
    });
});

require __DIR__ . '/auth.php';