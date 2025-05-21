<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\SubService;
use App\Models\User;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
public function store(Request $request)
{
    $validated = $request->validate([
        'pin' => 'required|digits:4|integer|min:1000|max:9999',
        'service_id' => 'required|exists:services,id',
        'subservice_id' => 'required|exists:sub_services,id',
    ]);

    // Find user by PIN
    $user = User::where('pin', $validated['pin'])->first();

    if (!$user) {
        return redirect()->back()->withErrors(['pin' => 'Invalid PIN. User not found.'])->withInput();
    }

    // Add user_id and remove pin from validated data
    $validated['user_id'] = $user->id;
    unset($validated['pin']);

    // Create the check-in
    CheckIn::create($validated);

    $subservice = SubService::with('service')->findOrFail($request->subservice_id);

       return back()->with([
        'confirm_user_id' => $user->id,
        'confirm_subservice_id' => $subservice->id,
        'confirm_service_id' => $subservice->service->id,
        'confirm_name' => strtoupper(substr($user->name, 0, 1)) . '.' . explode(' ', $user->name)[1],
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
