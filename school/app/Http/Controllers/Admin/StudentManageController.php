<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentManageController extends Controller
{
    /**
     * Display a listing of student accounts.
     */
    public function index()
    {
        $students = User::where('role', 'student')
            ->orderBy('id')
            ->paginate(10);

        return view(
            'admin.students.index',
            compact('students')
        );
    }
}
