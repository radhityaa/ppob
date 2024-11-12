<?php

namespace App\Http\Controllers;

use App\Models\SettingProvider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SettingProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function setting(Request $request)
    {
        if ($request->ajax()) {
            $data = SettingProvider::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button id="edit" data-slug="' . $row->slug . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-pencil"></i></button>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Setting Provider';
        return view('settings.provider.setting', compact('title'));
    }

    public function change()
    {
        $title = 'Change Provider';

        return view('settings.provider.change', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $data = SettingProvider::where('slug', $slug)->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $data = SettingProvider::where('slug', $slug)->first();

        try {
            $data->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => true,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
