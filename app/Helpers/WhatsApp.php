<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsApp
{
    public static function sendMessage($to, $message)
    {
        $instanceId = env('WA_INSTANCE_ID');
        $token = env('WA_TOKEN');

        if (empty($instanceId) || empty($token)) {
            Log::error('WhatsApp API credentials not configured');
            throw new \Exception('WhatsApp API credentials not configured');
        }

        $formattedNumber = self::formatNumber($to);

        if (empty($formattedNumber)) {
            Log::error('Invalid WhatsApp number format');
            throw new \Exception('Invalid WhatsApp number format');
        }

        $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->post($url, [
                    'token' => $token,
                    'to' => $formattedNumber,
                    'body' => $message,
                ]);

            $responseData = $response->json();

            // Debug logging
            Log::debug('WhatsApp API Response', [
                'status' => $response->status(),
                'response' => $responseData,
                'to' => $formattedNumber,
                'message' => substr($message, 0, 100) // Log sebagian pesan
            ]);

            // Handle different response formats
            if ($response->successful()) {
                if (isset($responseData['sent']) && $responseData['sent']) {
                    return true;
                }
                if (isset($responseData['id'])) {
                    return true;
                }
                if (isset($responseData['success']) && $responseData['success']) {
                    return true;
                }
            }

            throw new \Exception('Failed to send message: ' . json_encode($responseData));

        } catch (\Exception $e) {
            Log::error('WhatsApp API Error: ' . $e->getMessage());
            throw $e; // Re-throw untuk penanganan di controller
        }
    }

    private static function formatNumber($number)
    {
        // Hapus semua karakter non-numerik
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika kosong, return false
        if (empty($number)) return false;

        // Format nomor Indonesia
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }

        return $number;
    }

    public static function testConnection()
    {
        $instanceId = env('WA_INSTANCE_ID');
        $token = env('WA_TOKEN');

        if (empty($instanceId) || empty($token)) {
            return [
                'success' => false,
                'message' => 'WhatsApp API credentials not configured'
            ];
        }

        try {
            $url = "https://api.ultramsg.com/{$instanceId}/instance/status";
            $response = Http::timeout(30)
                ->get($url, ['token' => $token]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'exception' => get_class($e)
            ];
        }
    }
}
