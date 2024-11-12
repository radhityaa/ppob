<?php

namespace App\Http\Controllers;

use App\Models\SettingProfit;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingProfitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        $title = 'Profit Settings';

        $data = SettingProfit::first();

        return view('settings.profit.index', compact('title', 'data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'persentase' => 'required',
            'minimal_withdrawal' => 'required',
        ]);

        $data = SettingProfit::first();

        $withdrawalRequest = formatRupiahToNumber($request->minimal_withdrawal);

        $data->update([
            'persentase' => $request->persentase,
            'minimal_withdrawal' => $withdrawalRequest,
        ]);

        Alert::success('Success', 'Update Profit Berhasil');
        return back();
    }
}
