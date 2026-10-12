<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Pemesanan Restoran
|--------------------------------------------------------------------------
|
| Route resource untuk pelanggan, meja, pesanan, serta route laporan.
| Tidak ada middleware auth karena sistem tanpa login (PRD Bagian 2.1).
|
*/

// Halaman utama diarahkan ke daftar pesanan
Route::get('/', function () {
    return redirect()->route('pesanan.index');
});

// Route resource untuk CRUD pelanggan (KF-01)
Route::resource('pelanggan', PelangganController::class)->except(['show']);

// Route resource untuk CRUD meja (KF-02)
Route::resource('meja', MejaController::class)->except(['show']);

// Route resource untuk CRUD pesanan (KF-03, KF-04)
Route::resource('pesanan', PesananController::class)->except(['show']);

// Route laporan pemesanan (KF-05)
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
