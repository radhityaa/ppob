<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionGameFeatureResource;
use App\Models\TransactionGameFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class TransactionGameFeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function history(Request $request)
    {
        $filterStatus = $request->status;
        $filterInvoice = $request->invoice;

        $title = 'Riwayat Premium Order';
        $query = TransactionGameFeature::with('vipGameStreaming');

        if (Auth::user()->hasRole('admin')) {
            // Jika admin, ambil semua data
            $statusCounts = TransactionGameFeature::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = TransactionGameFeature::count();
        } else {
            // Jika bukan admin, filter berdasarkan user_id
            $query = $query->where('user_id', Auth::user()->id);

            $statusCounts = TransactionGameFeature::select('status', DB::raw('count(*) as count'))
                ->where('user_id', Auth::user()->id)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = TransactionGameFeature::where('user_id', Auth::user()->id)->count();
        }

        // Filter berdasarkan status
        if ($filterStatus) {
            $query = $query->where('status', $filterStatus);
        }

        // Filter berdasarkan invoice
        if ($filterInvoice) {
            $query = $query->where('invoice', $filterInvoice);
        }

        // Ambil data yang difilter dan urutkan berdasarkan waktu terbaru
        $data = $query->latest()->paginate(6);
        $totalWaiting = $statusCounts->get('waiting', 0);
        $totalProcessing = $statusCounts->get('processing', 0);
        $totalSuccess = $statusCounts->get('success', 0);
        $totalGagal = $statusCounts->get('error', 0);

        return view('history.premium-account', compact('title', 'totalWaiting', 'totalProcessing', 'totalSuccess', 'totalGagal', 'total', 'data', 'filterStatus', 'filterInvoice'));
    }

    public function historyDetail($invoice, Request $request)
    {
        $data = TransactionGameFeature::where('invoice', $invoice)->with('vipGameStreaming')->first();

        return response()->json(new TransactionGameFeatureResource($data));
    }
}
