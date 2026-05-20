
<?php
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExamController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', function () {
    return view('admin.dashboard.index');
})->name('dashboard');

Route::prefix('admin')->group(function () {

    Route::resource('subjects', SubjectController::class);
    Route::resource('chapters', ChapterController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('exams', ExamController::class);

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
});