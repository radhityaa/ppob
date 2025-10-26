<?php

namespace App\Http\Controllers;

use App\Models\SettingMargin;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingMarginController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $title = 'Setting Margin';
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

    public function update(Request $request)
    {
        $fields = [
            'margin_member'            => 'member',
            'margin_reseller'          => 'reseller',
            'margin_agen'              => 'agen',
            'margin_premium_member'    => 'vip-premium-member',
            'margin_premium_reseller'  => 'vip-premium-reseller',
            'margin_premium_agen'      => 'vip-premium-agen',
            'margin_sosmed_member'     => 'vip-sosmed-member',
            'margin_sosmed_reseller'   => 'vip-sosmed-reseller',
            'margin_sosmed_agen'       => 'vip-sosmed-agen'
        ];

        // Validate required fields
        $this->validate($request, array_fill_keys(array_keys($fields), 'required'));

        // Fetch all margin records at once
        $slugs = array_values($fields);
        $margins = SettingMargin::whereIn('slug', $slugs)->get()->keyBy('slug');

        // Check essential margins existence
        if (
            !isset($margins['member']) ||
            !isset($margins['agen']) ||
            !isset($margins['reseller'])
        ) {
            Alert::error('Error', 'Tidak Dapat Ditemukan!');
            return redirect()->back();
        }

        // Update each margin
        foreach ($fields as $input => $slug) {
            if (isset($margins[$slug])) {
                $margins[$slug]->update([
                    'margin' => formatRupiahToNumber($request->input($input))
                ]);
            }
        }

        Alert::success('Success', 'Update Margin Success');
        return back();
    }
}
