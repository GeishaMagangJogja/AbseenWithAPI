<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\KontakWaliController;
use App\Http\Controllers\Api\AbsensiController;


Route::post('/siswa', [SiswaController::class, 'store']);
Route::post('/siswa/{siswa}/kontak-wali', [KontakWaliController::class, 'store']);
Route::post('/absensi', [AbsensiController::class, 'store']);
