<?php

namespace App\Services;

use App\Models\RechargeItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RechargeItemService
{
    public function dataTable()
    {
        $data = RechargeItem::with('rechargeTitle')->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('Y-m-d H:i:s');
            })
            ->editColumn('src', function ($row) {
                return '<img src="' . asset('assets/img/services/' . $row->src) . '" width="50" height="50" class="img-fluid" />';
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update recharge/item')) {
                    $actionBtn = '<button type="button" id="edit-recharge" data-id="' . $row->id . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-pencil"></i></button>';
                }
                if (Gate::allows('delete recharge/item')) {
                    $actionBtn .= '<button type="button" id="delete-recharge" data-id="' . $row->id . '" class="deleteRecharge btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="d-flex">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action', 'src'])
            ->make(true);
    }

    public function store($data)
    {
        DB::beginTransaction();

        try {
            $imageName = time() . '.' . $data['image']->extension();
            $data['image']->move(public_path('assets/img/services'), $imageName);

            RechargeItem::create([
                'recharge_title_id' => $data['recharge_title_id'],
                'route' => $data['route'],
                'label' => $data['label'],
                'src' => $imageName
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        $data = RechargeItem::findOrFail($id);

        try {
            $oldImagePath = public_path('assets/img/services/' . $data['src']);
            unlink($oldImagePath);
            $data->delete();

            return [
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal hapus data: ' . $e->getMessage()
            ];
        }
    }
}
