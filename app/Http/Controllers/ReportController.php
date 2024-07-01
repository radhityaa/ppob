<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transactions()
    {
        $title = 'Laporan Transaksi';
        return view('report.transactions', compact('title'));
    }
}
