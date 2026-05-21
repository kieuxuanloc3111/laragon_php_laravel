<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),

            'role' => 'student',
        ]);

        $token = $user
            ->createToken('student-token')
            ->plainTextToken;

        return response()->json([

            'success' => true,

            'token' => $token,

            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where(
            'email',
            $request->email
        )->first();

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if ($user->role !== 'student') {

            return response()->json([
                'success' => false,
                'message' => 'Not a student account'
            ], 403);
        }

        $token = $user
            ->createToken('student-token')
            ->plainTextToken;

        return response()->json([

            'success' => true,

            'token' => $token,

            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out'
        ]);
    }
}