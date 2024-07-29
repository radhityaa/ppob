<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LicenseCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $licenseKey = config('app.license_key');
        $licenseServer = config('app.license_server_url');

        $response = Http::post($licenseServer, [
            'license_key' => $licenseKey,
            'domain' => $request->getHost(),
        ]);

        if ($response->failed() || $response->json('status') !== 'valid') {
            abort(403, 'License Invalid');
        }

        return $next($request);
    }
}
