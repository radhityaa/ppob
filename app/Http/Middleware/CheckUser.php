<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $phone = $request->phone;

        if (!$phone) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number is required'
            ], 400);
        }

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $message = 'Nomor *' . $phone . '* Tidak Terdaftar, Silahkan Daftar Melalui Link Berikut:
*https://ayasyatech.com/register*';

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        return $next($request);
    }
}
