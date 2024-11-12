<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryInformation;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\CategoryInformationRequest;

class CategoryInformationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CategoryInformation::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $delete = '<button class="btn btn-danger btn-sm" id="delete-category" data-slug="' . $row->slug . '"><i class="ti ti-trash"></i></button>';
                    $edit = '<button class="btn btn-warning btn-sm" id="edit-category" data-slug="' . $row->slug . '"><i class="ti ti-pencil"></i></button>';

                    return '<div class="btn-group">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action', 'type'])
                ->make(true);
        }

        $title = 'Kategori Informasi';

        return view('category-informations.index', compact('title'));
    }

    public function edit(CategoryInformation $categoryInformation)
    {
        return response()->json($categoryInformation);
    }

    public function update(CategoryInformation $categoryInformation, CategoryInformationRequest $request)
    {
        DB::beginTransaction();

        try {
            $request['slug'] = Str::slug($request->name . '-' . Str::random(6));
            $categoryInformation->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diubah.',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah data: ' . $th->getMessage()
            ], 400);
        }
    }

    public function store(CategoryInformationRequest $request)
    {
        DB::beginTransaction();

        try {
            CategoryInformation::create([
                'name' => $name = $request->name,
                'slug' => Str::slug($name . '-' . Str::random(6))
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $th->getMessage()
            ], 400);
        }
    }

    public function destroy(CategoryInformation $categoryInformation)
    {
        DB::beginTransaction();

        try {
            $categoryInformation->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus.',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $th->getMessage()
            ], 400);
        }
    }
}
