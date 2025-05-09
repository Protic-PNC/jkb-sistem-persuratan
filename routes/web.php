<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Dosen_Wali\UserController as DosenWaliUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Ketua_Jurusan\UserController as KetuaJurusanUserController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Dosen_Wali\DashboardController as DosenWaliDashboardController;
use App\Http\Controllers\Bagian_Keuangan\DashboardController as BagianKeuanganDashboardController;
use App\Http\Controllers\Bagian_Perpustakaan\DashboardController as BagianPerpustakaanDashboardController;
use App\Http\Controllers\Admin\PernyataanMagangController as AdminPernyataanMagangController;
use App\Http\Controllers\Ketua_Jurusan\DashboardController as KetuaJurusanDashboardController;
use App\Http\Controllers\Admin\PelanggaranAkademikController as AdminPelanggaranAkademikController;
use App\Http\Controllers\Mahasiswa\UserController as MahasiswaUserController;
use App\Http\Controllers\Mahasiswa\PernyataanMagangController as MahasiswaPernyataanMagangController;
use App\Http\Controllers\Mahasiswa\PelanggaranAkademikController as MahasiswaPelanggaranAkademikController;
use App\Http\Controllers\Dosen_Wali\PelanggaranAkademikController as DosenWaliPelanggaranAkademikController;
use App\Http\Controllers\Ketua_Jurusan\PelanggaranAkademikController as KetuaJurusanPelanggaranAkademikController;
use App\Http\Controllers\Admin\PengunduranDiriController as AdminPengunduranDiriController;
use App\Http\Controllers\Mahasiswa\PengunduranDiriController as MahasiswaPengunduranDiriController;
use App\Http\Controllers\Ketua_Jurusan\PengunduranDiriController as KetuaJurusanPengunduranDiriController;
use App\Http\Controllers\Dosen_Wali\PengunduranDiriController as DosenWaliPengunduranDiriController;
use App\Http\Controllers\Bagian_Keuangan\PengunduranDiriController as BagianKeuanganPengunduranDiriController;
use App\Http\Controllers\Bagian_Perpustakaan\PengunduranDiriController as BagianPerpustakaanPengunduranDiriController;

Route::get('/', [HomeController::class, 'index'])->middleware('auth');
Route::get('/logout', function () {
    return redirect('/login');
});

Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');
Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->middleware('auth');
Route::put('/profile/{user}', [ProfileController::class, 'update'])->middleware('auth');

Route::get('logs', [LogViewerController::class, 'index'])->middleware('admin');

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login')->middleware('guest');
    Route::post('/login', 'authenticate')->middleware('guest');
    Route::post('/logout', 'logout');
});

Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->middleware('admin');

Route::middleware('admin')->group(function () {
    Route::resource('/dashboard/admin/pernyataan-magang', AdminPernyataanMagangController::class);

    Route::get('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/cetak', [AdminPernyataanMagangController::class, 'cetak']);
    Route::post('/check-username', [AdminPernyataanMagangController::class, 'checkUsername'])->name('check-username');

    Route::get('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/upload', [AdminPernyataanMagangController::class, 'uploadForm'])->name('pernyataan.upload.form');
    Route::post('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/upload', [AdminPernyataanMagangController::class, 'upload'])->name('pernyataan.upload');
    Route::post('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/setujui', [AdminPernyataanMagangController::class, 'setujui'])->name('pernyataan.setujui');
    Route::post('/dashboard/admin/pernyataan-magang/{pernyataanMagang}/tolak', [AdminPernyataanMagangController::class, 'tolak'])->name('pernyataan.tolak');
    Route::post('/dashboard/admin/pernyataan-magang/{noSurat}/reminder', [AdminPernyataanMagangController::class, 'sendReminder'])->name('pernyataan.reminder');
    Route::post('/dashboard/admin/pernyataan-magang/reset', [AdminPernyataanMagangController::class, 'resetAll'])->name('pernyataan.reset');
});

Route::middleware('admin')->prefix('dashboard/admin')->group(function () {
    Route::resource('pelanggaran-akademik', AdminPelanggaranAkademikController::class);
    Route::get('pelanggaran-akademik/{pelanggaranAkademik}/cetak', [AdminPelanggaranAkademikController::class, 'cetak']);
    Route::post('pelanggaran-akademik/{pelanggaranAkademik}/setujui', [AdminPelanggaranAkademikController::class, 'setujui'])
        ->name('pelanggaran-akademik.setujui');
    Route::post('pelanggaran-akademik/{pelanggaranAkademik}/tolak', [AdminPelanggaranAkademikController::class, 'tolak'])
        ->name('pelanggaran-akademik.tolak');
    Route::post('pelanggaran-akademik/{noSurat}/reminder-tanda-tangan', [AdminPelanggaranAkademikController::class, 'reminderTandaTangan']);
});

Route::get('/dashboard/admin/pengunduran-diri/{pengunduranDiri}/cetak', [AdminPengunduranDiriController::class, 'cetak'])->middleware('admin');
Route::resource('/dashboard/admin/pengunduran-diri', AdminPengunduranDiriController::class)->middleware('admin');
Route::post('/dashboard/admin/pengunduran-diri/{pengunduranDiri}/tolak', [AdminPengunduranDiriController::class, 'tolak'])->middleware('admin');

// Route::resource('/dashboard/admin/user', AdminUserController::class)->except(['show'])->middleware('admin');
Route::get('/dashboard/admin/user/get-mahasiswa-by-npm', [AdminUserController::class, 'getMahasiswaByNPM'])->middleware('admin');
Route::get('/dashboard/admin/user/get-dosen-wali', [AdminUserController::class, 'getDosenWali'])->middleware('admin');
Route::get('/dashboard/admin/user', [AdminUserController::class, 'index'])->middleware('admin');
Route::get('/dashboard/admin/user/create', [AdminUserController::class, 'create'])->middleware('admin');
Route::get('/dashboard/admin/user/import', [AdminUserController::class, 'showImportForm'])->middleware('admin');
Route::post('/dashboard/admin/user/import', [AdminUserController::class, 'importCSV'])->middleware('admin');
Route::get('/dashboard/admin/kelas/import', [AdminKelasController::class, 'showImportForm'])->middleware('admin');
Route::post('/dashboard/admin/kelas/import', [AdminKelasController::class, 'importCSV'])->middleware('admin');
Route::post('/dashboard/admin/user', [AdminUserController::class, 'store'])->middleware('admin');
Route::get('/dashboard/admin/user/{user}/edit', [AdminUserController::class, 'edit'])->middleware('admin');
Route::put('/dashboard/admin/user/{user}', [AdminUserController::class, 'update'])->middleware('admin');
Route::delete('/dashboard/admin/user/{user}', [AdminUserController::class, 'destroy'])->middleware('admin');
Route::get('/dashboard/admin/user/template', [AdminUserController::class, 'downloadTemplate'])->name('akun.download-template')->middleware('admin');
Route::post('/dashboard/admin/user/reset', [AdminUserController::class, 'resetAll'])->middleware('admin');

// Route::resource('/dashboard/admin/kelas', AdminKelasController::class)->except(['show'])->middleware('admin');
Route::get('/dashboard/admin/kelas', [AdminKelasController::class, 'index'])->middleware('admin');
Route::get('/dashboard/admin/kelas/create', [AdminKelasController::class, 'create'])->middleware('admin');
Route::post('/dashboard/admin/kelas', [AdminKelasController::class, 'store'])->middleware('admin');
Route::get('/dashboard/admin/kelas/{kelas}/edit', [AdminKelasController::class, 'edit'])->middleware('admin');
Route::put('/dashboard/admin/kelas/{kelas}', [AdminKelasController::class, 'update'])->middleware('admin');
Route::delete('/dashboard/admin/kelas/{kelas}', [AdminKelasController::class, 'destroy'])->middleware('admin');
Route::get('/dashboard/admin/kelas/template', [AdminKelasController::class, 'downloadTemplate'])->name('kelas.download-template')->middleware('admin');
Route::post('/dashboard/admin/kelas/reset', [AdminKelasController::class, 'resetAll'])->middleware('admin');

Route::get('/dashboard/mahasiswa', [MahasiswaDashboardController::class, 'index'])->middleware('mahasiswa');
Route::get('/dashboard/mahasiswa/user/get-mahasiswa-by-npm', [MahasiswaUserController::class, 'getMahasiswaByNPM'])->middleware('mahasiswa');
Route::middleware('mahasiswa')->prefix('dashboard/mahasiswa')->group(function () {
    Route::resource('pernyataan-magang', MahasiswaPernyataanMagangController::class);
    Route::get('/dashboard/mahasiswa/pernyataan-magang/{pernyataanMagang}/cetak', [MahasiswaPernyataanMagangController::class, 'cetak']);
    Route::get('pernyataan-magang/{pernyataanMagang}/upload', [MahasiswaPernyataanMagangController::class, 'uploadForm'])
        ->name('pernyataan.upload.form');
    Route::post('pernyataan-magang/{pernyataanMagang}/upload', [MahasiswaPernyataanMagangController::class, 'upload'])
        ->name('pernyataan.upload');
});

Route::get('/dashboard/mahasiswa/pelanggaran-akademik', [MahasiswaPelanggaranAkademikController::class, 'index'])->middleware('mahasiswa');
Route::get('/dashboard/mahasiswa/pelanggaran-akademik/{pelanggaranAkademik}/cetak', [MahasiswaPelanggaranAkademikController::class, 'cetak'])->middleware('mahasiswa');
Route::get('/dashboard/mahasiswa/pelanggaran-akademik/{pelanggaranAkademik}', [MahasiswaPelanggaranAkademikController::class, 'show'])->middleware('mahasiswa');
Route::get('/dashboard/mahasiswa/pelanggaran-akademik/{pelanggaranAkademik}/edit', [MahasiswaPelanggaranAkademikController::class, 'edit'])->middleware('mahasiswa');
Route::put('/dashboard/mahasiswa/pelanggaran-akademik/{pelanggaranAkademik}', [MahasiswaPelanggaranAkademikController::class, 'update'])->middleware('mahasiswa');
Route::delete('/dashboard/mahasiswa/pelanggaran-akademik/{pelanggaranAkademik}', [MahasiswaPelanggaranAkademikController::class, 'destroy'])->middleware('mahasiswa');

Route::get('/dashboard/mahasiswa/pengunduran-diri/{pengunduranDiri}/cetak', [MahasiswaPengunduranDiriController::class, 'cetak'])->middleware('mahasiswa');
Route::resource('/dashboard/mahasiswa/pengunduran-diri', MahasiswaPengunduranDiriController::class)->middleware('mahasiswa');

Route::get('/dashboard/dosen-wali', [DosenWaliDashboardController::class, 'index'])->middleware('dosen-wali');
Route::get('/dashboard/dosen-wali/user/get-mahasiswa-by-npm', [DosenWaliUserController::class, 'getMahasiswaByNPM'])->middleware('dosen-wali');
Route::get('/dashboard/dosen-wali/user/get-dosen-wali', [DosenWaliUserController::class, 'getDosenWali'])->middleware('dosen-wali');
Route::get('/dashboard/dosen-wali/pelanggaran-akademik/{pelanggaranAkademik}/cetak', [DosenWaliPelanggaranAkademikController::class, 'cetak'])->middleware('dosen-wali');
Route::resource('/dashboard/dosen-wali/pelanggaran-akademik', DosenWaliPelanggaranAkademikController::class)->middleware('dosen-wali');
Route::post('/dashboard/dosen-wali/pelanggaran-akademik/{pelanggaranAkademik}/tolak', [DosenWaliPengunduranDiriController::class, 'tolak'])->middleware('dosen-wali');
Route::post('/dashboard/dosen-wali/pelanggaran-akademik/{pelanggaranAkademik}/setujui', [DosenWaliPengunduranDiriController::class, 'setujui'])->middleware('dosen-wali');

Route::get('/dashboard/dosen-wali/pengunduran-diri/{pengunduranDiri}/cetak', [DosenWaliPengunduranDiriController::class, 'cetak'])->middleware('dosen-wali');
Route::resource('/dashboard/dosen-wali/pengunduran-diri', DosenWaliPengunduranDiriController::class)->middleware('dosen-wali');
Route::post('/dashboard/dosen-wali/pengunduran-diri/{pengunduranDiri}/tolak', [DosenWaliPengunduranDiriController::class, 'tolak'])->middleware('dosen-wali');

Route::get('/dashboard/ketua-jurusan', [KetuaJurusanDashboardController::class, 'index'])->middleware('ketua-jurusan');
Route::get('/dashboard/ketua-jurusan/user/get-mahasiswa-by-npm', [KetuaJurusanUserController::class, 'getMahasiswaByNPM'])->middleware('ketua-jurusan');
Route::get('/dashboard/ketua-jurusan/user/get-dosen-wali', [KetuaJurusanUserController::class, 'getDosenWali'])->middleware('ketua-jurusan');
Route::get('/dashboard/ketua-jurusan/pelanggaran-akademik/{pelanggaranAkademik}/cetak', [KetuaJurusanPelanggaranAkademikController::class, 'cetak'])->middleware('ketua-jurusan');
Route::resource('/dashboard/ketua-jurusan/pelanggaran-akademik', KetuaJurusanPelanggaranAkademikController::class)->middleware('ketua-jurusan');

Route::get('/dashboard/ketua-jurusan/pengunduran-diri/{pengunduranDiri}/cetak', [KetuaJurusanPengunduranDiriController::class, 'cetak'])->middleware('ketua-jurusan');
Route::resource('/dashboard/ketua-jurusan/pengunduran-diri', KetuaJurusanPengunduranDiriController::class)->middleware('ketua-jurusan');
Route::post('/dashboard/ketua-jurusan/pengunduran-diri/{pengunduranDiri}/tolak', [KetuaJurusanPengunduranDiriController::class, 'tolak'])->middleware('ketua-jurusan');

Route::get('/dashboard/bagian-keuangan', [BagianKeuanganDashboardController::class, 'index'])->middleware('bagian-keuangan');
Route::get('/dashboard/bagian-keuangan/pengunduran-diri/{pengunduranDiri}/cetak', [BagianKeuanganPengunduranDiriController::class, 'cetak'])->middleware('bagian-keuangan');
Route::resource('/dashboard/bagian-keuangan/pengunduran-diri', BagianKeuanganPengunduranDiriController::class)->middleware('bagian-keuangan');
Route::post('/dashboard/bagian-keuangan/pengunduran-diri/{pengunduranDiri}/tolak', [BagianKeuanganPengunduranDiriController::class, 'tolak'])->middleware('bagian-keuangan');

Route::get('/dashboard/bagian-perpustakaan', [BagianPerpustakaanDashboardController::class, 'index'])->middleware('bagian-perpustakaan');
Route::get('/dashboard/bagian-perpustakaan/pengunduran-diri/{pengunduranDiri}/cetak', [BagianPerpustakaanPengunduranDiriController::class, 'cetak'])->middleware('bagian-perpustakaan');
Route::resource('/dashboard/bagian-perpustakaan/pengunduran-diri', BagianPerpustakaanPengunduranDiriController::class)->middleware('bagian-perpustakaan');
Route::post('/dashboard/bagian-perpustakaan/pengunduran-diri/{pengunduranDiri}/tolak', [BagianPerpustakaanPengunduranDiriController::class, 'tolak'])->middleware('bagian-perpustakaan');
