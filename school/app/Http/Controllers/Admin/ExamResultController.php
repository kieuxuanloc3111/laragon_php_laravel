<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ExamResultController extends Controller
{
    /**
     * Hiển thị danh sách học sinh để xem kết quả thi.
     */
    public function index()
    {
        $students = User::where('role', 'student')
            ->withCount('studentExams')
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.exam-results.index',
            compact('students')
        );
    }

    /**
     * Hiển thị các lần thi của một học sinh.
     */
    public function show(User $student)
    {
        abort_unless(
            $student->role === 'student',
            404
        );

        $student->load([
            'studentExams' => function ($query) {
                $query
                    ->with('exam.subject')
                    ->orderByDesc('submitted_at')
                    ->orderByDesc('started_at');
            },
        ]);

        return view(
            'admin.exam-results.show',
            compact('student')
        );
    }
}
