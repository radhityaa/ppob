<?php

namespace App\Http\Controllers;

use App\Http\Requests\RechargeItemRequest;
use App\Models\RechargeItem;
use App\Services\RechargeItemService;
use Illuminate\Http\Request;

class RechargeItemController extends Controller
{
    protected $rechargeItemService;

    public function __construct(RechargeItemService $rechargeItemService)
    {
        $this->middleware(['role:admin']);
        $this->rechargeItemService = $rechargeItemService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Recharge Item';
        if (request()->ajax()) {
            return $this->rechargeItemService->dataTable();
        }

        return view('recharge.item.index', compact('title'));
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
    public function store(RechargeItemRequest $request)
    {
        $result = $this->rechargeItemService->store($request->all());
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(RechargeItem $rechargeItem, $id)
    {
        $data = RechargeItem::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RechargeItem $rechargeItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RechargeItem $rechargeItem, $id)
    {
        $data = RechargeItem::findOrFail($id);

        try {
            if ($request->hasFile('image')) {
                if ($data->src) {
                    $oldImagePath = public_path('assets/img/services/' . $data->src);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('assets/img/services'), $imageName);
                $data->update([
                    'recharge_title_id' => $data['recharge_title_id'],
                    'route' => $data['route'],
                    'label' => $data['label'],
                    'src' => $imageName
                ]);
            } else {
                $data->update([
                    'recharge_title_id' => $request->recharge_title_id,
                    'route' => $request->route,
                    'label' => $request->label,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Data berhasil diubah.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal ubah data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RechargeItem $rechargeItem, $id)
    {
        $result = $this->rechargeItemService->destroy($id);
        return response()->json($result);
    }
}
