<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class MessageTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index(Request $request)
    {
        $title = 'Setting Message Template';

        if ($request->ajax()) {
            $data = MessageTemplate::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.settings.message-template.template.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.message-template.index', compact('title'));
    }

    public function edit($id)
    {
        $data = MessageTemplate::findOrFail($id);
        $title = 'Edit Message Template';

        return view('settings.message-template.edit', compact('title', 'data'));
    }

    public function update(Request $request, $id)
    {
        $data = MessageTemplate::findOrFail($id);
        $data->update($request->all());

        Alert::success('Success', 'Message Template Has Been Updated');
        return redirect()->route('admin.settings.message-template.template.index');
    }
}
