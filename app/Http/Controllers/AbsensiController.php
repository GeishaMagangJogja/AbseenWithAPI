<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Helpers\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\AbsensiNotifikasi;

class AbsensiController extends Controller
{
 public function index()
    {
        $absensis = Absensi::with('siswa')->orderBy('tanggal', 'desc')->get();
        return view('index', compact('absensis'));
    }

    public function create()
    {
        $siswas = Siswa::all();
        return view('absensi.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => [
                'required',
                'exists:siswas,id',
                Rule::unique('absensis')->where(function ($query) use ($request) {
                    return $query->where('tanggal', $request->tanggal);
                }),
            ],
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Simpan absensi
            $absensi = Absensi::create($request->all());
            $siswa = $absensi->siswa;

            // Validasi nomor WhatsApp
            if (empty($siswa->nomor_wa_ortu)) {
                throw new \Exception('Nomor WhatsApp orang tua tidak tersedia');
            }

            // Kirim notifikasi via Fonnte
            $siswa->notify(new AbsensiNotifikasi($absensi));

            DB::commit();

            return redirect()
                ->route('absensi.index')
                ->with('success', 'Absensi berhasil ditambahkan & notifikasi WhatsApp terkirim.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Absensi Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    private function formatPesanAbsensi($absensi)
    {
        try {
            $statusLabels = [
                'hadir' => 'Hadir',
                'izin' => 'Izin',
                'sakit' => 'Sakit',
                'alpha' => 'Alpha'
            ];

            $tanggal = \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y');
            $keterangan = $absensi->keterangan ?: '-';

            return <<<MSG
📢 *INFORMASI ABSENSI*

Nama: *{$absensi->siswa->nama}*
Kelas: *{$absensi->siswa->kelas}*
Tanggal: *{$tanggal}*
Status: *{$statusLabels[$absensi->status]}*
Keterangan: {$keterangan}

_Pesan otomatis dari Sistem Absensi_
MSG;

        } catch (\Exception $e) {
            Log::error("Gagal memformat pesan absensi: " . $e->getMessage());
            return "Informasi absensi untuk {$absensi->siswa->nama} pada {$absensi->tanggal}";
        }
    }

    public function show(string $id)
    {
        $absensi = Absensi::with('siswa')->findOrFail($id);
        return view('absensi.show', compact('absensi'));
    }

    public function edit(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $siswas = Siswa::all();
        return view('absensi.edit', compact('absensi', 'siswas'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update($request->all());

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    // Method untuk testing WhatsApp
    public function testWhatsApp()
    {
        $result = WhatsApp::testConnection();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Koneksi WhatsApp API berhasil',
                'data' => $result['data']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal koneksi ke WhatsApp API: ' . ($result['message'] ?? 'Unknown error')
            ], 500);
        }
    }
}
