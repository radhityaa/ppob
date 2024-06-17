<?php

namespace App\Http\Controllers;

use App\Models\WhatsappGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappGatewayController extends Controller
{
    public function index()
    {
        $title = 'Whatsapp Gateway';
        $device = WhatsappGateway::where('user_id', auth()->user()->id)->first();

        return view('whatsapp.index', compact('title', 'device'));
    }

    public function scan(Request $request, $number)
    {
        $title = 'Scan: ' . $number;
        return view('whatsapp.scan', compact('title', 'number'));
    }

    public function updateStatus(Request $request, $number)
    {
        $device = WhatsappGateway::where('phone', $number)->first();

        $device->update(['status' => $request->status]);
        return response()->json($device);
    }
}
