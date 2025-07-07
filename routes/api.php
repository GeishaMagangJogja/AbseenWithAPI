<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\KontakWaliController;
use App\Http\Controllers\Api\AbsensiController;

// Public routes for Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (memerlukan token)
Route::middleware('auth:sanctum')->group(function () {
    // Route untuk mendapatkan info user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- Rute Baru Kita ---
    // Rute untuk menambah siswa baru
    Route::post('/siswa', [SiswaController::class, 'store']);
    
    // Rute untuk menambah kontak wali ke siswa tertentu
    Route::post('/siswa/{siswa}/kontak-wali', [KontakWaliController::class, 'store']);

    // Rute untuk melakukan absensi
    Route::post('/absensi', [AbsensiController::class, 'store']);
});