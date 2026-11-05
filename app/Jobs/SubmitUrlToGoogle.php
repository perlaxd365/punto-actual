<?php

namespace App\Jobs;

use App\Services\GoogleIndexingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitUrlToGoogle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 120, 600];

    public function __construct(
        public string $url,
        public string $type = 'URL_UPDATED',
    ) {}

    public function handle(GoogleIndexingService $indexer): void
    {
        $indexer->publish($this->url, $this->type);
    }
}
