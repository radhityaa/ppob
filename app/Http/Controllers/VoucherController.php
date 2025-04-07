<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Voucher::with('user')->latest()->get();
            } else {
                $data = Voucher::where('user_id', auth()->user()->id)->with('user')->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button class="btn btn-info btn-sm me-1"><i class="ti ti-eye"></i></button>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->editColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('voucher.index');
    }
}
