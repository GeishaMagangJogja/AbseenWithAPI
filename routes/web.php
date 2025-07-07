<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Route test WhatsApp
Route::get('/test-wa', function() {
    $testPhone = '6281327659373'; // Ganti dengan nomor tujuan yang valid
    $testMessage = 'Test notifikasi dari sistem absensi';
    
    try {
        $response = Http::withHeaders([
            'Authorization' => env('WHATSAPP_API_KEY'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $testPhone,
            'message' => $testMessage,
        ]);
        
        return response()->json([
            'status' => $response->status(),
            'response' => $response->json(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
});

// Route lainnya...
Route::get('/', function () {
    return view('welcome');
});

// Route absensi
Route::resource('absensi', 'App\Http\Controllers\AbsensiController');