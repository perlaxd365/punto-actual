<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('puntoactual.api_token');
        $token = (string) ($request->bearerToken() ?: $request->header('X-Api-Key'));

        if ($expected === '' || ! hash_equals($expected, $token)) {
            return response()->json([
                'message' => 'No autorizado. Envía el header Authorization: Bearer <API_TOKEN>.',
            ], 401);
        }

        return $next($request);
    }
}
