<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    public const SCOPE = 'https://www.googleapis.com/auth/indexing';

    public const ENDPOINT = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

    public function enabled(): bool
    {
        return (bool) config('services.google.indexing.enabled');
    }

    public function publish(string $url, string $type = 'URL_UPDATED'): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $credentialsPath = config('services.google.indexing.credentials');

        if (! $credentialsPath || ! is_file($credentialsPath)) {
            Log::warning('Google Indexing API: falta GOOGLE_APPLICATION_CREDENTIALS.');
            return false;
        }

        $credentials = json_decode((string) file_get_contents($credentialsPath), true);

        if (! is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            Log::warning('Google Indexing API: credenciales inválidas.');
            return false;
        }

        $token = $this->accessToken($credentials);

        if (! $token) {
            return false;
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->post(self::ENDPOINT, ['url' => $url, 'type' => $type]);

        if (! $response->successful()) {
            Log::warning('Google Indexing API: error al publicar.', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * @param  array{client_email: string, private_key: string, token_uri?: string}  $credentials
     */
    protected function accessToken(array $credentials): ?string
    {
        $tokenUri = $credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token';
        $now = time();

        $header = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = $this->base64Url(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => self::SCOPE,
            'aud' => $tokenUri,
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $signature = '';
        $signed = openssl_sign($header.'.'.$payload, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256);

        if (! $signed) {
            Log::warning('Google Indexing API: no se pudo firmar el JWT.');
            return null;
        }

        $assertion = $header.'.'.$payload.'.'.$this->base64Url($signature);

        $response = Http::asForm()->timeout(20)->post($tokenUri, [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ]);

        if (! $response->successful()) {
            Log::warning('Google Indexing API: error de autenticación.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    protected function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
