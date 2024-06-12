<?php

namespace App\Http\Controllers;

use App\Helpers\TripayHelper;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:reseller|admin']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Transfer::latest()->get();
            } else {
                $data = Transfer::where('user_id', auth()->user()->id)->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="detailTf" data-invoice="' . $row->invoice . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-eye"></i></button>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->rawColumns([])
                ->make(true);
        }

        $title = 'Transfer Saldo';

        return view('transfer.index', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'amount' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $target = User::where('slug', $request->username)->first();
        $request['user_id'] = Auth::user()->id;
        $request['invoice'] = invoice($request->user_id, 'TF', 'transfers');

        if (Auth::user()->saldo < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi'
            ], 400);
        }

        try {
            DB::beginTransaction();

            Auth::user()->update([
                'saldo' => DB::raw('saldo - ' . $request->amount)
            ]);

            $transfer = Transfer::create($request->all());

            $target->update([
                'saldo' => $target->saldo + $transfer->amount
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer Berhasil'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Transfer Gagal: ' . $th->getMessage()
            ]);
        }
    }

    public function show(Transfer $transfer)
    {
        return response()->json($transfer);
    }
}
