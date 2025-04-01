<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true); // Measure execution time

        $response = $next($request);

        $executionTime = round((microtime(true) - $start) * 1000, 2); // In milliseconds

        // Check if response was cached
        $cached = app('cache.store')->has($request->fullUrl());

        Log::channel('graylog')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'request_headers' => $request->headers->all(),
            'request_body' => $request->all(),
            'response_code' => $response->getStatusCode(),
            'response_body' => $response->getContent(),
            'execution_time_ms' => $executionTime,
            'cached' => $cached ? 'yes' : 'no',
        ]);

        return $response;
    }
}
