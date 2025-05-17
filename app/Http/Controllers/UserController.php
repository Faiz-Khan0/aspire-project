<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'user_group' => 'required|in:u,s,a',
            'password' => 'nullable|min:6',
            'pin' => 'nullable|digits:4',
        ]);

        $credential = match ($validated['user_group']) {
            'u' => bcrypt($validated['pin'] ?? str()->random(6)),
            default => bcrypt($validated['password'] ?? str()->random(32)),
        };

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'user_group' => $validated['user_group'],
            'password' => $credential,
        ]);

       return redirect()->route('userManagement')->with('success', 'Successfully created user.');
    }
}
