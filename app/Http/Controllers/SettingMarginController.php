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
        $marginMember = SettingMargin::where('slug', 'member')->select('margin')->first();
        $marginReseller = SettingMargin::where('slug', 'reseller')->select('margin')->first();
        $marginAgen = SettingMargin::where('slug', 'Agen')->select('margin')->first();

        return view('settings.margin.index', compact('title', 'marginMember', 'marginReseller', 'marginAgen'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'margin_member' => 'required',
            'margin_agen' => 'required',
            'margin_reseller' => 'required',
        ]);

        $marginMember = SettingMargin::where('slug', 'member')->first();
        $marginAgen = SettingMargin::where('slug', 'agen')->first();
        $marginReseller = SettingMargin::where('slug', 'reseller')->first();

        if (!$marginMember || !$marginAgen || !$marginReseller) {
            return redirect()->back();
            Alert::error('Error', 'Tidak Dapat Ditemukan!');
        }

        $marginMemberRequest = formatRupiahToNumber($request->margin_member);
        $marginResellerRequest = formatRupiahToNumber($request->margin_reseller);
        $marginAgenRequest = formatRupiahToNumber($request->margin_agen);

        $marginMember->update(['margin' => $marginMemberRequest]);
        $marginReseller->update(['margin' => $marginResellerRequest]);
        $marginAgen->update(['margin' => $marginAgenRequest]);

        Alert::success('Success', 'Update Margin Success');
        return back();
    }
}
