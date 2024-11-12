<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MutationController extends Controller
{
    public function index(Request $request)
    {
        $title = "Mutasi Saldo";

        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Mutation::latest()->get();
            } else {
                $data = Mutation::where('user_id', Auth::user()->id)->latest()->get();
            }

            return DataTables::make($data)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    if ($row->type === 'Kredit') {
                        return '<span class="badge bg-success">Kredit</span>';
                    } else {
                        return '<span class="badge bg-danger">Debet</span>';
                    }
                })
                ->editColumn('amount', function ($row) {
                    return number_format($row->amount, 0, ',', '.');
                })
                ->editColumn('latest_balance', function ($row) {
                    return number_format($row->latest_balance, 0, ',', '.');
                })
                ->editColumn('current_balance', function ($row) {
                    return number_format($row->current_balance, 0, ',', '.');
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->rawColumns(['type'])
                ->make(true);
        }

        return view('mutation.index', compact('title'));
    }
}
