<?php

use App\Http\Controllers\Admin\ImportUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\RombelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Guru\GuruDashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

        Route::resource('categories', CategoryController::class);
        Route::resource('books', BookController::class);
        Route::post('books/import', [BookController::class, 'import'])->name('books.import');

        Route::resource('loans', LoanController::class)->only(['index']);
        Route::get('/loans/export/excel', [LoanController::class, 'exportExcel'])->name('loan.export.excel');
        Route::get('/loans/table', [LoanController::class, 'table'])->name('admin.loans.table');

        Route::resource('reports', ReportController::class)->only(['index']);
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // =====================
    // ROUTE GURU
    // =====================
    Route::middleware('role:guru')->group(function () {
        Route::get('/guru/books', [\App\Http\Controllers\Guru\BookController::class, 'index'])->name('guru.books.index');
        Route::post('/guru/books{id}/pinjam', [\App\Http\Controllers\Guru\LoanController::class, 'store'])->name('guru.books.pinjam');
        Route::get('/guru/history', [\App\Http\Controllers\Guru\LoanController::class, 'history'])->name('guru.history');
    });

    // =====================
    // ROUTE SISWA
    // =====================
    Route::middleware('role:siswa')->group(function () {
        // contoh jika nanti diaktifkan
        // Route::get('/siswa/pinjam', [\App\Http\Controllers\Siswa\LoanController::class, 'create'])->name('siswa.pinjam');
        // Route::post('/siswa/pinjam', [\App\Http\Controllers\Siswa\LoanController::class, 'store'])->name('siswa.pinjam.store');
        // Route::get('/siswa/history', [\App\Http\Controllers\Siswa\LoanController::class, 'history'])->name('siswa.history');
    });
});

require __DIR__ . '/auth.php';