<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLicensedDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        // Always allow local development hosts.
        $localHosts = ['localhost', '127.0.0.1', '::1'];
        if (in_array($host, $localHosts, true)) {
            return $next($request);
        }

        $licensePath = base_path('bootstrap/liucen.text');
        if (!file_exists($licensePath)) {
            $licensePath = base_path('bootstrap/license.txt');
        }

        if (!file_exists($licensePath)) {
            abort(403, 'License file not found.');
        }

        $lines = file($licensePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        $allowedHosts = [];
        foreach ($lines as $line) {
            $line = trim(strtolower($line));

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $line = preg_replace('#^https?://#', '', $line);
            $line = preg_replace('#/.*$#', '', $line);
            $line = preg_replace('#:\\d+$#', '', $line);

            if ($line !== '') {
                $allowedHosts[] = $line;
            }
        }

        $normalizedHost = preg_replace('#^www\\.#', '', $host);
        $normalizedAllowed = array_map(
            static fn (string $allowed): string => preg_replace('#^www\\.#', '', $allowed),
            $allowedHosts
        );

        if (!in_array($normalizedHost, $normalizedAllowed, true)) {
            abort(403, 'This domain is not licensed for this project.');
        }

        return $next($request);
    }
}
