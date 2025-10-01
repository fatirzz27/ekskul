<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EkskulController;
use App\Models\Ekskul;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembinaAnggotaController;
use App\Http\Controllers\PembinaAbsensiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route aplikasi.
|
*/

Route::get('/', function () {
    $ekskuls = Ekskul::latest()->take(3)->get(); // Ambil 3 ekskul terbaru untuk welcome page
    return view('welcome', compact('ekskuls'));
});

// PUBLIC (guest bisa lihat daftar & detail)
Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul.index');

// MANAGEMENT (harus login & role admin/pembina)
Route::middleware('auth')->group(function () {

    // form create HARUS diletakkan sebelum /ekskul/{ekskul} untuk menghindari konflik route
    Route::get('/ekskul/create', [EkskulController::class, 'create'])->name('ekskul.create');
    Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');

    Route::get('/ekskul/{ekskul}/edit', [EkskulController::class, 'edit'])->name('ekskul.edit');
    Route::put('/ekskul/{ekskul}', [EkskulController::class, 'update'])->name('ekskul.update');
    Route::delete('/ekskul/{ekskul}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');

    // join / leave
    Route::post('/ekskul/{ekskul}/join', [EkskulController::class, 'join'])->name('ekskul.join');
    Route::delete('/ekskul/{ekskul}/leave', [EkskulController::class, 'leave'])->name('ekskul.leave');

    // halaman kelola
    Route::get('/kelola-ekskul', [EkskulController::class, 'manage'])->name('ekskul.manage');
});

// Route show harus di bawah route yang spesifik untuk menghindari konflik
Route::get('/ekskul/{ekskul}', [EkskulController::class, 'show'])->name('ekskul.show');

// Hanya user login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User Role Management (hanya untuk admin)
        Route::get('/kelola-user', [UserRoleController::class, 'index'])->name('kelola-user');
        Route::put('/user/{id}', [UserRoleController::class, 'update'])->name('user.update');
        Route::get('/user/{id}', [UserRoleController::class, 'editEkskul'])->name('user.editEkskul');
        Route::post('/user/{id}', [UserRoleController::class, 'updateEkskul'])->name('user.updateEkskul');
        Route::delete('/user/{id}', [UserRoleController::class, 'destroy'])->name('user.destroy');


    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('pembina')->name('pembina.')->middleware(['auth'])->group(function () {
    // tampil anggota ekskul
    Route::get('/ekskul/{ekskul}/anggota', [PembinaAnggotaController::class, 'index'])->name('anggota.index');

    // hapus anggota
    Route::delete('/ekskul/{ekskul}/anggota/{userId}', [PembinaAnggotaController::class, 'destroy'])->name('anggota.destroy');

    Route::get('ekskul/{ekskul}/absensi', [PembinaAbsensiController::class, 'index'])->name('absensi.index');
Route::post('ekskul/{ekskul}/absensi', [PembinaAbsensiController::class, 'store'])->name('absensi.store');

});





require __DIR__.'/auth.php';


