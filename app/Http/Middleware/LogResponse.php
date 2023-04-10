<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class LogResponse
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        Log::info('Response', [
            'status' => $response->getStatusCode(),
            'content' => $response->getContent(),
        ]);

        return $response;
    }
}
