<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Notifications\NotifAbsensiSiswa; // <-- Pastikan Notifikasi sudah dibuat
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'status' => 'required|in:Hadir,Sakit,Izin', // Status Alpa dihandle oleh scheduler
        ]);

        // Cek apakah siswa sudah diabsen hari ini
        $cekAbsen = Absensi::where('siswa_id', $validated['siswa_id'])
            ->whereDate('tanggal', Carbon::today())->first();

        if ($cekAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa sudah diabsen hari ini.',
            ], 409); // 409 Conflict
        }

        $absensi = Absensi::create([
            'siswa_id' => $validated['siswa_id'],
            'status' => $validated['status'],
            'tanggal' => Carbon::today(),
            'jam_masuk' => $validated['status'] == 'Hadir' ? Carbon::now()->toTimeString() : null,
        ]);

        // Kirim notifikasi jika ada kontak wali yang terdaftar
        $kontakWali = $absensi->siswa->kontakWali;
        if ($kontakWali && $kontakWali->no_hp) {
            $kontakWali->notify(new NotifAbsensiSiswa($absensi));
        }

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan.',
            'data' => $absensi,
        ], 201);
    }
}