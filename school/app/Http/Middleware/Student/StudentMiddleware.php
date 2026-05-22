<?php

namespace App\Http\Middleware\Student;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // chưa login
        if (!auth()->check()) {

            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // không phải student
        if (
            auth()->user()->role != 'student'
        ) {

            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}