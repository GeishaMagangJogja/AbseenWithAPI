<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;


// Route lainnya...
Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');

// Route absensi
Route::resource('absensi', 'App\Http\Controllers\AbsensiController');
Route::get('/test-notifikasi', [AbsensiController::class, 'testNotifikasi']);
