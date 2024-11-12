<?php

namespace App\Http\Controllers;

use App\Services\EnvFileService;
use Illuminate\Http\Request;

class EnvController extends Controller
{
    public function __construct(protected EnvFileService $envFileService)
    {
        $this->middleware(['role:admin']);
    }
    public function show()
    {
        $title = 'Setting Env';
        $envDetails = $this->envFileService->getAllEnv();

        return view('settings.env.index', compact('title', 'envDetails'));
    }

    public function update(Request $request)
    {
        $this->envFileService->updateEnv($request);
        return back()->with(['message' => 'Env Berhasil Diubah.', 'type' => 'success']);
    }
}
