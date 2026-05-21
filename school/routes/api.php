<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentExamController;
use App\Http\Controllers\Api\AuthController;
Route::prefix('auth')->group(function () {

    Route::post('/register', [
        AuthController::class,
        'register'
    ]);

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);
});
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->middleware('auth:sanctum');
Route::prefix('student')
    ->middleware([
        'auth:sanctum',
        'student'
    ])
    ->group(function () {

        Route::get(
            '/exams',
            [StudentExamController::class, 'index']
        );

        Route::post(
            '/exams/{exam}/start',
            [StudentExamController::class, 'start']
        );

        Route::post(
            '/student-exams/{studentExam}/submit',
            [StudentExamController::class, 'submit']
        );

        Route::get(
            '/student-exams/{studentExam}/review',
            [StudentExamController::class, 'review']
        );

        Route::get(
            '/history',
            [StudentExamController::class, 'history']
        );
});