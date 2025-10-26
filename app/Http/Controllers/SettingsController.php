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
        $slugs = [
            'marginMember'         => 'member',
            'marginReseller'       => 'reseller',
            'marginAgen'           => 'agen',
            'marginPremiumMember'  => 'vip-premium-member',
            'marginPremiumReseller' => 'vip-premium-reseller',
            'marginPremiumAgen'    => 'vip-premium-agen',
            'marginSosmedMember'   => 'vip-sosmed-member',
            'marginSosmedReseller' => 'vip-sosmed-reseller',
            'marginSosmedAgen'     => 'vip-sosmed-agen',
        ];

        $margins = SettingMargin::whereIn('slug', array_values($slugs))
            ->select('slug', 'margin')
            ->get()
            ->keyBy('slug')
            ->toArray();

        // Assign each variable expected by the view for backwards compatibility
        $data = compact('title');
        foreach ($slugs as $varName => $slug) {
            $data[$varName] = isset($margins[$slug]) ? (object)['margin' => $margins[$slug]['margin']] : (object)['margin' => 0];
        }

        return view('settings.margin.index', $data);
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
