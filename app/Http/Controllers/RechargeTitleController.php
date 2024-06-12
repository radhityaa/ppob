<?php

namespace App\Http\Controllers;

use App\Models\RechargeTitle;
use App\Services\RechargeTitleService;
use Illuminate\Http\Request;

class RechargeTitleController extends Controller
{
    protected $rechargeTitleService;

    public function __construct(RechargeTitleService $rechargeTitleService)
    {
        $this->middleware(['role:admin']);
        $this->rechargeTitleService = $rechargeTitleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Recharge Title';
        if (request()->ajax()) {
            return $this->rechargeTitleService->dataTable();
        }

        return view('recharge.title.index', compact('title'));
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
        $result = $this->rechargeTitleService->store($request->all());
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $result = $this->rechargeTitleService->show($id);
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RechargeTitle $rechargeTitle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $result = $this->rechargeTitleService->update($request->all(), $id);
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RechargeTitle $rechargeTitle, $id)
    {
        $result = $this->rechargeTitleService->destroy($id);
        return response()->json($result);
    }

    public function list()
    {
        return response()->json([
            'data' => RechargeTitle::all()
        ]);
    }
}
