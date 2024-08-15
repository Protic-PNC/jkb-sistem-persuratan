<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PernyataanMagangController as AdminPernyataanMagangController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\PernyataanMagangController as MahasiswaPernyataanMagangController;

Route::get('/', [HomeController::class, 'index'])->middleware('auth');

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login')->middleware('guest', 'auth');
    Route::post('/login', 'authenticate')->middleware('guest');
    Route::post('/logout', 'logout');
});

Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->middleware('admin');
Route::get('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/cetak', [AdminPernyataanMagangController::class, 'cetak'])->middleware('admin');
Route::resource('/dashboard/admin/pernyataan-magang', AdminPernyataanMagangController::class)->middleware('admin');

Route::get('/dashboard/mahasiswa', [MahasiswaDashboardController::class, 'index'])->middleware('mahasiswa');
Route::get('/dashboard/mahasiswa/pernyataan-magang/{pernyataanMagang}/cetak', [MahasiswaPernyataanMagangController::class, 'cetak'])->middleware('mahasiswa');
Route::resource('/dashboard/mahasiswa/pernyataan-magang', MahasiswaPernyataanMagangController::class)->middleware('mahasiswa');
