<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Information;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CategoryInformation;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\InformationRequest;
use App\Http\Resources\InformationResource;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

class InformationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Information::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    if ($row->type === 'Informasi') {
                        return '<span class="badge bg-info">Informasi</span>';
                    } else if ($row->type === 'Peringatan') {
                        return '<span class="badge bg-warning">Peringatan</span>';
                    } else {
                        return '<span class="badge bg-danger">Penting</span>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $delete = '<button class="btn btn-danger btn-sm" id="delete-information" data-slug="' . $row->slug . '"><i class="ti ti-trash"></i></button>';
                    $edit = '<a href="' . route('information.edit', $row->slug) . '" class="btn btn-warning btn-sm"><i class="ti ti-pencil"></i></a>';
                    $show = '<a href="' . route('information.show', $row->slug) . '" class="btn btn-info btn-sm"><i class="ti ti-eye"></i></a>';

                    return '<div class="btn-group">' . $show . $delete . $edit . '</div>';
                })
                ->rawColumns(['action', 'type'])
                ->make(true);
        }

        $title = 'Kelola Informasi';

        return view('informations.index', compact('title'));
    }

    public function updateInformationUser(Request $request)
    {
        $user = User::where('id', $request->userId)->first();
        $user->information_dismissed_at = now();
        $user->save();

        return response()->json(['success' => true]);
    }

    public function show($slug)
    {
        $information = Information::where('slug', $slug)->first();

        if (!$information) {
            return redirect()->route('home')->with('error', [
                'message' => 'Informasi yang anda cari tidak ditemukan.'
            ]);
        }

        $title = $information->title;

        return view('informations.show', compact('title', 'information'));
    }

    public function create()
    {
        $title = 'Tambah Informasi Baru';

        $categories = CategoryInformation::latest()->get();

        return view('informations.create', compact('title', 'categories'));
    }

    public function store(InformationRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->title . '-' . Str::random(4));
        $data['user_id'] = Auth::id();
        $data['category_information_id'] = $request->category;
        $data['description'] = Purifier::clean($request->description);

        Information::create($data);

        return redirect()->route('information.index')->with('success', [
            'message' => 'Informasi berhasil ditambahkan.'
        ]);
    }

    public function edit($slug, Request $request)
    {
        $title = 'Edit Informasi';
        $information = Information::where('slug', $slug)->first();

        if (!$information) {
            return redirect()->route('information.index')->with('error', [
                'message' => 'Informasi yang anda cari tidak ditemukan.'
            ]);
        }

        $categories = CategoryInformation::latest()->get();


        return view('informations.edit', compact('title', 'information', 'categories'));
    }

    public function update($slug, InformationRequest $request)
    {
        $information = Information::where('slug', $slug)->first();

        if (!$information) {
            return redirect()->route('information.index')->with('error', [
                'message' => 'Informasi tidak ditemukan.'
            ]);
        }

        $data = $request->all();
        $data['category_information_id'] = $request->category;
        $data['description'] = Purifier::clean($request->description);

        $information->update($data);

        return redirect()->route('information.index')->with('success', [
            'message' => 'Informasi berhasil diubah.'
        ]);
    }

    public function listInformation()
    {
        $oneMonthAgo = now()->subMonth()->toDateString();  // Mengambil hanya tanggal
        $today = now()->toDateString();  // Mengambil hanya tanggal

        $information = Information::whereBetween(DB::raw('DATE(created_at)'), [$oneMonthAgo, $today])
            ->latest()
            ->get();

        return response()->json(InformationResource::collection($information));
    }

    public function destroy($slug)
    {
        $information = Information::where('slug', $slug)->first();

        if (!$information) {
            return response()->json(['message' => 'Informasi tidak ditemukan.']);
        }

        $information->delete();
        return response()->json(['message' => 'Informasi berhasil dihapus.']);
    }
}