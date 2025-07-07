<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException; // <-- Tambahkan ini untuk menangkap error

class WablasChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            // Ambil data dari kelas notifikasi
            $data = $notification->toWablas($notifiable);
            
            // Ambil kredensial dari config
            $apiKey = config('services.wablas.api_key');
            $serverUrl = config('services.wablas.server_url');

            // Hentikan jika API Key tidak ada
            if (!$apiKey) {
                Log::error('Wablas API Key tidak ditemukan di file .env.');
                return;
            }

            // Kirim request menggunakan sintaks yang benar dan aman
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post("{$serverUrl}/api/send-message", [
                'phone' => $data['phone'],
                'message' => $data['message'],
            ]);

            // Lemparkan exception jika request gagal (misal: error 4xx atau 5xx)
            $response->throw();

            // (Opsional) Catat ke log jika berhasil
            Log::info('Notifikasi Wablas berhasil masuk antrean: ' . $response->body());

        } catch (RequestException $e) {
            // --- INI BAGIAN PENTING UNTUK DEBUGGING ---
            // Jika terjadi error koneksi atau error dari server Wablas (4xx/5xx),
            // kita akan mencatatnya ke log dengan detail.
            Log::error('Gagal mengirim notifikasi Wablas: ' . $e->getMessage());
        }
    }
}