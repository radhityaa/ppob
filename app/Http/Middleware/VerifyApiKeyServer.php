<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiKeyServer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKeyServer = $request->header('api-key-server');

        if (!$apiKeyServer) {
            return response()->json([
                'success' => false,
                'message' => 'Missing API Key'
            ], 401);
        }

        $user = User::where('api_key_server', $apiKeyServer)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key'
            ], 401);
        }

        return $next($request);
    }
}
