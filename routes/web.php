<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil & Pengaturan Kata Sandi
    Route::get('/profile/password', [\App\Http\Controllers\ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Modul Data Master & Sinkronisasi
    Route::middleware('role:admin,waka_hubin')->group(function () {
        Route::get('/master', [MasterDataController::class, 'index'])->name('master.index');
        Route::post('/master/sync', [MasterDataController::class, 'syncNow'])->name('master.sync')->middleware('role:admin');
    });

    // Modul Referensi Siswa & Verifikasi Kesiapan (RBAC Terisolasi)
    Route::middleware('role:admin,waka_hubin,kepsek,kajur,guru_pembimbing,siswa')->group(function () {
        Route::get('/students', [MasterDataController::class, 'students'])->name('students.index');
        Route::get('/students/{id}', [MasterDataController::class, 'studentDetail'])->name('students.show');
        Route::post('/students/{id}/verify', [MasterDataController::class, 'verifyReadiness'])->name('students.verify')->middleware('role:admin,kajur');
    });
});
