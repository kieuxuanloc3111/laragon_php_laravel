<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentExamController;

Route::prefix('student')->group(function () {

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

