<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\KontakWali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with('siswa')->latest()->get();
        return view('index', compact('absensi'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('absensi.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        $absensi = Absensi::create($request->all());

        // Kirim notifikasi ke WhatsApp orang tua
        $this->kirimNotifikasiWhatsApp($absensi);

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dicatat dan notifikasi terkirim');
    }

    private function kirimNotifikasiWhatsApp(Absensi $absensi)
    {
        $siswa = $absensi->siswa;
        $kontakWali = KontakWali::where('siswa_id', $siswa->id)->first();

        if ($kontakWali) {
            $nomorTujuan = $kontakWali->no_hp;
            $pesan = $this->formatPesanNotifikasi($absensi, $siswa, $kontakWali);

            // Ganti dengan API WhatsApp yang Anda gunakan
            $response = Http::post('https://api.wa-gateway.com/send', [
                'phone' => $nomorTujuan,
                'message' => $pesan,
                'api_key' => env('WHATSAPP_API_KEY'),
            ]);

            // Log response jika diperlukan
            Log::info('WhatsApp API Response:', $response->json());
        }
    }

    private function formatPesanNotifikasi($absensi, $siswa, $kontakWali)
    {
        return "Yth. {$kontakWali->nama_wali}\n\n" .
               "Berikut informasi absensi untuk anak Anda:\n\n" .
               "Nama Siswa: {$siswa->nama_lengkap}\n" .
               "NIS: {$siswa->nis}\n" .
               "Tanggal: {$absensi->tanggal}\n" .
               "Status: {$absensi->status}\n" .
               "Jam Masuk: " . ($absensi->jam_masuk ?? '-') . "\n\n" .
               "Terima kasih.";
    }
}
