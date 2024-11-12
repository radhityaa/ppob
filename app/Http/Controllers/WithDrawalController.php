<?php

namespace App\Http\Controllers;

use App\Models\SettingProfit;
use App\Models\User;
use App\Models\WithDrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class WithDrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:reseller|admin']);
    }

    public function index(Request $request)
    {
        $title = "Penarikan";

        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = WithDrawal::with('user')->latest()->get();
            } else {
                $data = WithDrawal::where('user_id', Auth::user()->id)->with('user')->latest()->get();
            }

            return DataTables::make($data)
                ->addIndexColumn()
                ->editColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('total', function ($row) {
                    return number_format($row->total, 0, '.', '.');
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        $settingProfit = SettingProfit::first();

        return view('profit.withdrawal', compact('title', 'settingProfit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required',
        ]);

        $minWd = SettingProfit::first();
        $user = User::where('id', Auth::user()->id)->first();

        $latestBalance = $user->saldo;

        if ($request->total < $minWd->minimal_withdrawal) {
            return response()->json([
                'success' => false,
                'message' => 'Total penarikan minimal ' . number_format($minWd->minimal_withdrawal, 0, '.', '.'),
                'data' => null,
            ], 400);
        }

        if ($user->profit_reseller < $request->total) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo Profit Anda tidak mencukupi',
                'data' => null,
            ], 400);
        }

        $invoice = invoice(Auth::user()->id, 'WD', 'with_drawals');

        $withdrawal = new Withdrawal();
        $withdrawal->invoice = $invoice;
        $withdrawal->user_id = Auth::user()->id;
        $withdrawal->total = $request->total;
        $withdrawal->save();

        $user->update([
            'profit_reseller' => $user->profit_reseller - $request->total,
            'saldo' => $user->saldo + $request->total,
        ]);

        createMutation($user->id, 'Kredit', 'Penarikan Profit Berhasil', $request->total, $latestBalance, $user->saldo, $invoice);

        return response()->json([
            'success' => true,
            'message' => 'Penarikan berhasil',
            'data' => null,
        ], 200);
    }
}
