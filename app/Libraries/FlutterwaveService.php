<?php

namespace App\Libraries;

class FlutterwaveService
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = env('FLW_SECRET_KEY', '');
        $this->publicKey = env('FLW_PUBLIC_KEY', '');
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Vérifie une transaction via l'API Flutterwave.
     * Retourne les données de transaction ou null si invalide.
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        if (empty($this->secretKey)) {
            log_message('error', 'Flutterwave: FLW_SECRET_KEY non configuré');
            return null;
        }

        $ch = curl_init("{$this->baseUrl}/transactions/{$transactionId}/verify");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->secretKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            log_message('error', "Flutterwave verify error: HTTP {$httpCode}");
            return null;
        }

        $data = json_decode($response, true);

        if (($data['status'] ?? '') !== 'success') {
            return null;
        }

        return $data['data'] ?? null;
    }

    /**
     * Vérifie la signature du webhook Flutterwave.
     */
    public function validateWebhook(string $signature): bool
    {
        $webhookSecret = env('FLW_WEBHOOK_SECRET', '');
        if (empty($webhookSecret)) {
            return false;
        }
        return hash_equals($webhookSecret, $signature);
    }

    /**
     * Génère une référence de transaction unique.
     */
    public static function generateTxRef(int $tenantId): string
    {
        return 'IKM-' . $tenantId . '-' . strtoupper(bin2hex(random_bytes(6))) . '-' . time();
    }
}
