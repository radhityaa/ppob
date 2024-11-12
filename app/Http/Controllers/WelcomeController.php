<?php

namespace App\Http\Controllers;

use App\Models\Landingpage\Hero;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $hero = Hero::first();
        return view('welcome', compact('hero'));
    }
}
