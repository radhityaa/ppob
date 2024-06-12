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
        $margin = SettingMargin::first();
        return view('settings.margin.index', compact('title', 'margin'));
    }

    public function update(Request $request, $id)
    {
        $margin = SettingMargin::where('id', $id)->first();

        if (!$margin) {
            return redirect()->back();
            Alert::error('Error', 'Tidak Dapat Ditemukan!');
        }

        $marginRequest = formatRupiahToNumber($request->margin);

        $margin->update(['margin' => $marginRequest]);
        Alert::success('Success', 'Update Margin Success');
        return back();
    }
}
