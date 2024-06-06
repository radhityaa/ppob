<?php

namespace App\Http\Controllers\Setting\Landingpage;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroRequest;
use App\Models\Landingpage\Hero;
use App\Services\HeroService;
use Illuminate\Http\Request;
use Flasher\Notyf\Prime\NotyfInterface;

class HeroController extends Controller
{
    protected $heroService;

    public function __construct(HeroService $heroService)
    {
        $this->middleware('can:admin');
        $this->heroService = $heroService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Hero';
        $data = Hero::first();

        return view('settings.landingpage.hero', compact('title', 'data'));
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
    public function store(HeroRequest $request)
    {
        //
        // $result = $this->heroService->create($request->all());
        // return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hero $hero)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hero $hero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hero $hero)
    {
        $result = $this->heroService->update($request->all(), $hero);
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hero $hero)
    {
        //
    }
}
