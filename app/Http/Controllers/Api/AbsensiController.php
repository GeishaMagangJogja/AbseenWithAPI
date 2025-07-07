<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Notifications\NotifAbsensiSiswa;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'status' => 'required|in:Hadir,Sakit,Izin', // Status 'Alpa' ditentukan otomatis oleh sistem
        ]);

        // Cek apakah siswa sudah absen hari ini
        $sudahAbsen = Absensi::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah melakukan absensi hari ini.',
            ], 409); // HTTP 409: Conflict
        }

        // Simpan data absensi
        $absensi = Absensi::create([
            'siswa_id' => $validated['siswa_id'],
            'status' => $validated['status'],
            'tanggal' => Carbon::today(),
            'jam_masuk' => $validated['status'] === 'Hadir' ? now()->toTimeString() : null,
        ]);

        // Kirim notifikasi jika kontak wali tersedia
        $kontakWali = $absensi->siswa->kontakWali;

        if ($kontakWali && $kontakWali->no_hp) {
            try {
                $kontakWali->notify(new NotifAbsensiSiswa($absensi));
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim notifikasi WA: " . $e->getMessage());
                // opsional: kamu bisa balas response tetap sukses, atau masukkan warning di response
            }
        }

        // Balasan API berhasil
        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'data' => $absensi,
        ], 201);
    }
}
