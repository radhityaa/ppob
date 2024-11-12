<?php

namespace App\Http\Controllers;

use App\Models\WhatsappGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WhatsappGatewayController extends Controller
{
    public function updateStatus(Request $request)
    {
        $device = WhatsappGateway::first();

        $device->update(['status' => $request->status]);
        return response()->json($device);
    }

    public function destroy()
    {
        $device = WhatsappGateway::first();
        $device->delete();
        return response()->json(['message' => 'Device deleted successfully.']);
    }

    public function store(Request $request)
    {
        $device = WhatsappGateway::create([
            'user_id' => Auth::user()->id,
            'phone' => $request->phone,
        ]);

        return response()->json($device);
    }
}
