<?php

// app/Notifications/NotifAbsensiSiswa.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\FonnteChannel; 
use App\Models\Absensi;

class NotifAbsensiSiswa extends Notification
{
    use Queueable;

    protected $absensi;

    public function __construct(Absensi $absensi)
    {
        $this->absensi = $absensi;
    }

    public function via($notifiable)
    {
        // Gunakan channel yang baru kita buat
        return [FonnteChannel::class];
    }

    public function toFonnte($notifiable)
    {
        return [
            'target' => $notifiable->no_hp,
            'message' => $this->buildMessage(),
        ];
    }

    /**
     * Metode ini TIDAK PERLU DIUBAH, bisa digunakan kembali
     */
    protected function buildMessage()
    {
        $namaSiswa = $this->absensi->siswa->nama_lengkap;
        $status = $this->absensi->status;
        $jam = date('H:i', strtotime($this->absensi->jam_masuk));
        
        if ($status == 'Hadir') {
            return "Info Absensi: {$namaSiswa} telah HADIR di kantor pada jam {$jam}. Terima kasih.";
        }
        
        if ($status == 'Alpa') {
            return "Info Absensi: Kami informasikan {$namaSiswa} belum tercatat kehadirannya (ALPA). Mohon konfirmasinya. Terima kasih.";
        }
        
        return "Info Absensi: {$namaSiswa} hari ini tercatat {$status}.";
    }

    /*
    // Metode toWablas() bisa Anda hapus atau biarkan saja,
    // tidak akan digunakan lagi selama metode via() menunjuk ke FonnteChannel.
    public function toWablas($notifiable)
    {
        // ...
    }
    */
}