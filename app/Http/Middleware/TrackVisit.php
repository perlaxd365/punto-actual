<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    private const BOTS = [
        'bot', 'crawl', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit',
        'whatsapp', 'telegram', 'headless', 'lighthouse', 'pagespeed', 'monitor',
        'uptime', 'curl', 'wget', 'python-requests', 'axios', 'httpclient',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() >= 400 || ! $this->shouldTrack($request)) {
            return $response;
        }

        try {
            Visit::create([
                'visited_on' => now()->toDateString(),
                'visited_at' => now(),
                'path' => substr('/'.ltrim($request->path(), '/'), 0, 2048),
                'visitor_hash' => hash('sha256', $request->ip().'|'.$request->userAgent().'|'.config('app.key')),
            ]);
        } catch (\Throwable) {
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->expectsJson() || $request->ajax()) {
            return false;
        }

        if ($request->user()) {
            return false;
        }

        if ($request->header('DNT') === '1' || $request->header('Sec-GPC') === '1') {
            return false;
        }

        $path = trim($request->path(), '/');

        foreach (['admin', 'login', 'logout', 'api', 'og', 'sitemap.xml', 'feed.xml', 'ads.txt', 'up', 'recuperar-contrasena', 'restablecer-contrasena'] as $excluded) {
            if ($path === $excluded || str_starts_with($path, $excluded.'/')) {
                return false;
            }
        }

        $agent = strtolower((string) $request->userAgent());

        if ($agent === '') {
            return false;
        }

        foreach (self::BOTS as $bot) {
            if (str_contains($agent, $bot)) {
                return false;
            }
        }

        return true;
    }
}
