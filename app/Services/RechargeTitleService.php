<?php

namespace App\Services;

use App\Models\RechargeTitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class RechargeTitleService
{
    public function dataTable()
    {
        $data = RechargeTitle::latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '';
                if (Gate::allows('update recharge/title')) {
                    $actionBtn = '<button type="button" id="edit-recharge" data-id="' . $row->id . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-pencil"></i></button>';
                }
                if (Gate::allows('delete recharge/title')) {
                    $actionBtn .= '<button type="button" id="delete-recharge" data-id="' . $row->id . '" class="deleteRecharge btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="d-flex">' . $actionBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show($id)
    {
        $data = RechargeTitle::find($id);
        return $data;
    }

    public function store($data)
    {
        DB::beginTransaction();

        try {
            RechargeTitle::create(['title' => $data['title']]);

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

    public function update($request, $id)
    {
        DB::beginTransaction();

        try {
            $data = RechargeTitle::find($id);
            $data->update(['title' => $request['title']]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Data berhasil diupdate.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $data = RechargeTitle::find($id);
            $data->delete();

            return [
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }
}
