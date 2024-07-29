<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->validateLicense();
    }

    private function validateLicense()
    {
        $licenseKey = config('app.license_key');
        $licenseServer = config('app.license_server_url');

        $response = Http::post($licenseServer, [
            'license_key' => $licenseKey,
            'domain' => request()->getHost(),
        ]);
        Log::info(request()->getHost());
        if ($response->failed() || $response->json('status') !== 'valid') {
            abort(403, 'License Invalid');
        }
    }
}
