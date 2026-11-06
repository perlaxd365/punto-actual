<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    public const ENDPOINT = 'https://api.indexnow.org/indexnow';

    public function enabled(): bool
    {
        return filled(config('services.indexnow.key'));
    }

    public function submit(array $urls): bool
    {
        $key = (string) config('services.indexnow.key');

        $urls = array_values(array_unique(array_filter($urls)));

        if (! $key || empty($urls)) {
            return false;
        }

        $host = parse_url($urls[0], PHP_URL_HOST);

        if (! $host) {
            return false;
        }

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(20)
            ->post(self::ENDPOINT, [
                'host' => $host,
                'key' => $key,
                'keyLocation' => 'https://'.$host.'/'.$key.'.txt',
                'urlList' => $urls,
            ]);

        if (! $response->successful()) {
            Log::warning('IndexNow: error al enviar URLs.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }

    public function submitOne(string $url): bool
    {
        return $this->submit([$url]);
    }
}
