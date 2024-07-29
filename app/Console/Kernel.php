<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $licenseKey = config('app.license_key');
            $licenseServer = config('app.license_server_url');

            $response = Http::post($licenseServer, [
                'license_key' => $licenseKey,
                'domain' => request()->getHost(),
            ]);

            if ($response->failed() || $response->json('status') !== 'valid') {
                abort(403, 'License Invalid');
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
