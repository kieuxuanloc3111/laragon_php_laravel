
<?php
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ChapterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', function () {
    return view('admin.dashboard.index');
})->name('dashboard');

Route::prefix('admin')->group(function () {

    Route::resource('subjects', SubjectController::class);
    Route::resource('chapters', ChapterController::class);
});