<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class FonnteChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            // Panggil metode toFonnte() dari kelas Notifikasi untuk mendapatkan data
            $data = $notification->toFonnte($notifiable);

            // Ambil token dari config
            $token = config('services.fonnte.token');

            // Hentikan jika token tidak ada
            if (!$token) {
                Log::error('Fonnte Token tidak ditemukan di file .env.');
                return;
            }

            // Kirim request ke API Fonnte
            $response = Http::withHeaders([
                'Authorization' => $token, // Fonnte menggunakan 'Authorization' header
            ])->post('https://api.fonnte.com/send', [
                'target' => $data['target'],
                'message' => $data['message'],
                // Bisa ditambahkan parameter lain seperti 'countryCode' jika perlu
            ]);

            // Lemparkan exception jika request gagal
            $response->throw();

            Log::info('Notifikasi Fonnte berhasil masuk antrean: ' . $response->body());

        } catch (RequestException $e) {
            Log::error('Gagal mengirim notifikasi Fonnte: ' . $e->getMessage());
        }
    }
}