<?php

namespace App\Http\Controllers;

use App\Helpers\VipaymentHelper;
use App\Models\SettingMargin;
use App\Models\VipGameStreaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PremiumAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin'])->only(['syncData']);
    }

    public function index(Request $request)
    {
        $title = 'Premium Account';
        $role = Auth::user()->roles[0]->name;

        $serviceNames = [
            'ChatGPT',
            'Alight Motion',
            'Amazon Prime Video',
            'Bstation Premium',
            'Canva Pro',
            'CapCut Pro',
            'Disney Hotstar',
            'Getcontact Premium',
            'iQIYI Premium',
            'Netflix Premium',
            'RCTI Plus',
            'Spotify Premium',
            'Vidio Premier',
            'Vision Plus',
            'Viu Premium',
            'WeTV Premium',
            'Youtube Premium',
        ];

        $data = VipGameStreaming::whereIn('game', $serviceNames)
            ->orderByDesc('status')
            ->orderBy('price', 'asc')
            ->get();

        return view('premium-account.index', compact('title', 'role', 'data', 'serviceNames'));
    }

    public function syncData()
    {
        // Nanti di bedakan margin nya khusus vipayment
        $settings = SettingMargin::whereIn('slug', [
            'vip-premium-member',
            'vip-premium-reseller',
            'vip-premium-agen'
        ])->get()->keyBy('slug');

        $marginMember = $settings['vip-premium-member']['margin'] ?? 0;
        $marginReseller = $settings['vip-premium-reseller']['margin'] ?? 0;
        $marginAgen = $settings['vip-premium-agen']['margin'] ?? 0;

        $services = VipaymentHelper::getServices('game');

        if (!$services['result']) {
            Log::error($services['message']);

            return response()->json([
                'success' => false,
                'message' => $services['message'],
                'data' => null
            ], 500);
        }

        $data = $services['data'];
        if (isset($data) && is_array($data)) {
            $gameFeatureCodes = [];

            foreach ($data as $item) {
                $gameFeatureCodes[] = $item['code'];

                VipGameStreaming::updateOrCreate(
                    ['code' => $item['code']],
                    [
                        'code' => $item['code'],
                        'game' => $item['game'],
                        'name' => $item['name'],
                        'price' => $item['price']['basic'],
                        'price_member' => $item['price']['basic'] + $marginMember,
                        'price_agen' => $item['price']['basic'] + $marginAgen,
                        'price_reseller' => $item['price']['basic'] + $marginReseller,
                        'server' => $item['server'],
                        'status' => $item['status'] == 'empty' ? false : true,
                    ]
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Vipayment game feature synced successfully',
            'data' => $data
        ]);
    }
}
