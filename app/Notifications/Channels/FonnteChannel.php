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
            $data = $notification->toFonnte($notifiable);
            $token = config('services.fonnte.token');
            $url = config('services.fonnte.url');

            if (!$token) {
                Log::error('Fonnte Token tidak ditemukan di file .env');
                return false;
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($url, [
                'target' => $data['target'],
                'message' => $data['message'],
                'delay' => $data['delay'] ?? 0,
            ]);

            $response->throw();

            Log::info('Notifikasi Fonnte berhasil dikirim: ' . $response->body());
            return true;

        } catch (RequestException $e) {
            Log::error('Gagal mengirim notifikasi Fonnte: ' . $e->getMessage());
            if ($e->response) {
                Log::error('Response error: ' . $e->response->body());
            }
            return false;
        }
    }
}
