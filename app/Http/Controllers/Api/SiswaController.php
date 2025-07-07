<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswas,nis',
        ]);

        $siswa = Siswa::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil ditambahkan.',
            'data' => $siswa,
        ], 201);
    }
}