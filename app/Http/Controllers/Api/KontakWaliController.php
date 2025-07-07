<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa; // Kita butuh model Siswa

class KontakWaliController extends Controller
{
    // Menggunakan Route Model Binding: Laravel otomatis mencari Siswa berdasarkan ID di URL
    public function store(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama_wali' => 'required|string',
            'no_hp' => 'required|string',
            'status_hubungan' => 'required|string',
        ]);

        // Menggunakan relasi untuk membuat/update kontak.
        // Sangat praktis untuk memastikan satu siswa hanya punya satu kontak wali.
        $kontak = $siswa->kontakWali()->updateOrCreate(
            ['siswa_id' => $siswa->id],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Kontak wali berhasil ditambahkan/diperbarui.',
            'data' => $kontak,
        ], 201);
    }
}