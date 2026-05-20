<?php

namespace App\Http\Middleware\Teacher;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | CHƯA LOGIN
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            return redirect('/admin/login');
        }

        /*
        |--------------------------------------------------------------------------
        | KHÔNG PHẢI TEACHER
        |--------------------------------------------------------------------------
        */

        if (
            auth()->user()->role != 'teacher'
        ) {

            abort(403);
        }

        return $next($request);
    }
}