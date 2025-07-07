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
        $whatsappSent = $this->kirimNotifikasiWhatsApp($absensi);

        if ($whatsappSent) {
            return redirect()->route('absensi.index')
                ->with('success', 'Absensi berhasil dicatat dan notifikasi terkirim');
        } else {
            return redirect()->route('absensi.index')
                ->with('warning', 'Absensi berhasil dicatat tetapi notifikasi WhatsApp gagal terkirim');
        }
    }

    private function kirimNotifikasiWhatsApp(Absensi $absensi)
    {
        $siswa = $absensi->siswa;
        $kontakWali = KontakWali::where('siswa_id', $siswa->id)->first();

        if (!$kontakWali || !$kontakWali->no_hp) {
            Log::error('Data kontak wali tidak ditemukan untuk siswa: ' . $siswa->id);
            return false;
        }

        $nomorTujuan = $this->formatPhoneNumber($kontakWali->no_hp);
        if (!$nomorTujuan) {
            Log::error('Format nomor HP tidak valid: ' . $kontakWali->no_hp);
            return false;
        }



        $pesan = $this->formatPesanNotifikasi($absensi, $siswa, $kontakWali);

    try {
        $response = Http::withoutVerifying()
            ->timeout(15)
            ->withHeaders([
                'Authorization' => env('WHATSAPP_API_KEY'),
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.fonnte.com/send', [
                'target' => $nomorTujuan,
                'message' => $pesan,
                'delay' => '2-5',
            ]);

            if ($response->successful()) {
                Log::info('Notifikasi WhatsApp berhasil dikirim ke: ' . $nomorTujuan);
                return true;
            } else {
                Log::error('Gagal mengirim WhatsApp. Status: ' . $response->status());
                Log::error('Response body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception saat mengirim WhatsApp: ' . $e->getMessage());
            return false;
        }
    }

    private function formatPhoneNumber($phone)
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan 0, ganti dengan 62 (kode negara Indonesia)
        if (strlen($phone) > 0 && $phone[0] === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Pastikan nomor minimal 10 digit (62+8 digit)
        return (strlen($phone) >= 10) ? $phone : null;
    }

    private function formatPesanNotifikasi($absensi, $siswa, $kontakWali)
    {
        $statusMap = [
            'Hadir' => 'Hadir',
            'Sakit' => 'Sakit',
            'Izin' => 'Izin',
            'Alpa' => 'Tidak Hadir (Alpa)'
        ];

        $status = $statusMap[$absensi->status] ?? $absensi->status;

        return "Yth. Bapak/Ibu {$kontakWali->nama_wali}\n\n" .
               "*INFORMASI ABSENSI SISWA*\n\n" .
               "Nama Siswa: *{$siswa->nama_lengkap}*\n" .
               "NIS: *{$siswa->nis}*\n" .
               "Kelas: *{$siswa->kelas}*\n" .
               "Tanggal: *{$absensi->tanggal}*\n" .
               "Status: *{$status}*\n" .
               "Jam Masuk: *" . ($absensi->jam_masuk ?? '-') . "*\n\n" .
               "_Pesan ini dikirim otomatis, mohon tidak membalas._";
    }

    // Method untuk testing notifikasi
    public function testNotifikasi()
    {
        $testPhone = '6282241279326';
        $testMessage = 'Test notifikasi dari sistem absensi';

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => env('WHATSAPP_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => $testPhone,
                    'message' => $testMessage,
                    'delay' => '2-5',
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
    }
}
