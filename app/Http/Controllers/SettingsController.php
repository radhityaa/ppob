<?php

namespace App\Http\Controllers;

use App\Models\SettingMargin;
use App\Models\Settings;
use App\Models\WhatsappGateway;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $title = 'Setting App';
        $marginMember = SettingMargin::where('slug', 'member')->select('margin')->first();
        $marginReseller = SettingMargin::where('slug', 'reseller')->select('margin')->first();
        $marginAgen = SettingMargin::where('slug', 'Agen')->select('margin')->first();

        return view('settings.margin.index', compact('title', 'marginMember', 'marginReseller', 'marginAgen'));
    }

    public function informationDeposit()
    {
        $title = "Setting informasi Deposit";

        $data = Settings::where('slug', 'settings-information-deposit')->first();

        return view('settings.information-deposit.edit', compact('title', 'data'));
    }

    public function informationDepositUpdate(Request $request)
    {
        $data = Settings::where('slug', 'settings-information-deposit')->first();

        $data->val1 = $request->val1;
        $data->val2 = $request->val2;

        $data->save();

        return redirect()->route('admin.settings.informationDeposit')->with('success', [
            'message' => 'Informasi deposit berhasil diupdate'
        ]);
    }

    public function notification()
    {
        $title = "Setting Notifikasi";

        $data = Settings::where('slug', 'settings-notification')->first();
        $whatsapp = WhatsappGateway::first();

        return view('settings.notification.index', compact('title', 'data', 'whatsapp'));
    }

    public function notificationUpdate(Request $request)
    {
        $data = Settings::where('slug', 'settings-notification')->first();
        $whatsapp = WhatsappGateway::first();

        if ($request->phone) {
            $whatsapp->phone = $request->phone;
            $whatsapp->save();

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil diupdate',
                'data' => $data
            ]);
        }

        if ($request->val1) {
            $data->val1 = $request->val1;
        } else if ($request->val2) {
            $data->val2 = $request->val2;
        }

        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diupdate',
            'data' => $data
        ]);
    }
}
