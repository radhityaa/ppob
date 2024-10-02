<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function updateNewsUser(Request $request)
    {
        $user = User::where('id', $request->userId)->first();
        $user->news_dismissed_at = now();
        $user->save();

        return response()->json(['success' => true]);
    }
}
