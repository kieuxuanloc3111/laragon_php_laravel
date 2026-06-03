<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::post('/register', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'avatar' => 'required|image',
    ]);

    $avatarPath = null;

    if ($request->hasFile('avatar')) {

        $avatarPath = $request->file('avatar')
            ->store('avatars', 'public');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'avatar' => $avatarPath,
    ]);

    return response()->json([
        'message' => 'Register success',

        'user' => [
            ...$user->toArray(),

            'avatar_url' => asset('storage/' . $user->avatar),
        ]
    ]);
});

Route::post('/login', function (Request $request) {

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token,

        'user' => [
            ...$user->toArray(),

            'avatar_url' => asset('storage/' . $user->avatar),
        ]
    ]);
});