<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view(
            'admin.auth.register'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' =>
                'required|email|unique:users',

            'password' =>
                'required|min:6|confirmed',

            'image' =>
                'nullable|image'

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD IMAGE
        |--------------------------------------------------------------------------
        */

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('users', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' =>
                $request->name,

            'email' =>
                $request->email,

            'password' =>
                Hash::make(
                    $request->password
                ),

            'role' => 'teacher',

            'image' => $imagePath,

        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect('/admin');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view(
            'admin.auth.login'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' =>
                'required|email',

            'password' =>
                'required'

        ]);

        if (
            Auth::attempt($credentials)
        ) {

            $request->session()
                ->regenerate();

            return redirect('/admin');
        }

        return back()
            ->withErrors([

                'email' =>
                    'Email hoặc mật khẩu không đúng'

            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()
            ->invalidate();

        $request->session()
            ->regenerateToken();

        return redirect('/admin/login');
    }
}