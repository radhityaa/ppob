<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware(['role:admin'])->except('list');
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $title = 'Users';
        if ($request->ajax()) {
            return $this->userService->datatable();
        }

        return view('users.index', compact('title'));
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
    public function store(UserRequest $request)
    {
        $result = $this->userService->create($request->all());
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $result = $this->userService->userUpdate($request->all(), $user);

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $result = $this->userService->delete($user);

        return response()->json($result);
    }

    public function list()
    {
        $users = User::role('member')->get();

        return response()->json([
            'data' => $users
        ]);
    }
}
