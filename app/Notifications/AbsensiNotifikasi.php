<?php

namespace App\Notifications;

use App\Models\Absensi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AbsensiNotifikasi extends Notification implements ShouldQueue
{
    use Queueable;

    protected $absensi;

    public function __construct(Absensi $absensi)
    {
        $this->absensi = $absensi;
    }

    public function via($notifiable)
    {
        return ['fonnte'];
    }

    public function toFonnte($notifiable)
    {
        $statusLabels = [
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha'
        ];

        $tanggal = $this->absensi->tanggal->format('d/m/Y');
        $keterangan = $this->absensi->keterangan ?: '-';

        $message = <<<MSG
📢 *INFORMASI ABSENSI*

Nama: *{$this->absensi->siswa->nama}*
Kelas: *{$this->absensi->siswa->kelas}*
Tanggal: *{$tanggal}*
Status: *{$statusLabels[$this->absensi->status]}*
Keterangan: {$keterangan}

_Pesan otomatis dari Sistem Absensi_
MSG;

        return [
            'target' => $this->formatNomorWhatsApp($notifiable->nomor_wa_ortu),
            'message' => $message,
            'delay' => 5 // delay pengiriman dalam detik (opsional)
        ];
    }

    protected function formatNomorWhatsApp($nomor)
    {
        // Format nomor WhatsApp (hapus karakter selain angka, pastikan diawali dengan 62)
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (substr($nomor, 0, 1) === '0') {
            return '62' . substr($nomor, 1);
        }

        if (substr($nomor, 0, 2) !== '62') {
            return '62' . $nomor;
        }

        return $nomor;
    }
}
