<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('books', BookController::class);
        Route::get('/admin/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::post('/admin/guru/import', [GuruController::class, 'import'])->name('guru.import');
        Route::resource('siswa', SiswaController::class);
        Route::get('/export-laporan', [ReportController::class, 'export']);
    });

    Route::middleware('role:guru')->group(function () {
        Route::get('/pinjam', [LoanController::class, 'create']);
        Route::post('/pinjam', [LoanController::class, 'store']);
        Route::get('/history', [LoanController::class, 'history']);
    });

    Route::middleware('role:siswa')->group(function () {
        Route::get('/pinjam', [LoanController::class, 'create']);
        Route::post('/pinjam', [LoanController::class, 'store']);
        Route::get('/history', [LoanController::class, 'history']);
    });
});

require __DIR__ . '/auth.php';