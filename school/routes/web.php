<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StudentManageController;
use App\Http\Controllers\Admin\SubjectController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('welcome');

});

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );

    Route::get(
        '/register',
        [AuthController::class, 'showRegister']
    );

    Route::post(
        '/register',
        [AuthController::class, 'register']
    );

});

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware('teacher')
    ->group(function () {

        Route::get('/', function () {

            return view(
                'admin.dashboard.index'
            );

        })->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | SUBJECTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'subjects',
            SubjectController::class
        );

        /*
        |--------------------------------------------------------------------------
        | CHAPTERS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'chapters',
            ChapterController::class
        );

        /*
        |--------------------------------------------------------------------------
        | QUESTIONS
        |--------------------------------------------------------------------------
        */

        Route::post(
            'questions/upload-image',
            [QuestionController::class, 'uploadImage']
        )->name('questions.uploadImage');

        Route::resource(
            'questions',
            QuestionController::class
        );

        /*
        |--------------------------------------------------------------------------
        | EXAMS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'exams',
            ExamController::class
        );

        Route::post(
            'exams/{exam}/add-question',
            [ExamController::class, 'addQuestion']
        )->name('exams.addQuestion');

        Route::delete(
            'exams/{exam}/remove-question/{question}',
            [ExamController::class, 'removeQuestion']
        )->name('exams.removeQuestion');

        Route::post(
            'exams/{exam}/auto-generate',
            [ExamController::class, 'autoGenerate']
        )->name('exams.autoGenerate');

        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            'students',
            [StudentManageController::class, 'index']
        )->name('students.index');

        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/logout',
            [AuthController::class, 'logout']
        )->name('logout');

    });
