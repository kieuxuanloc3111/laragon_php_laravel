<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::orderBy('id')->get();

        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subjects,name',
            'color' => 'nullable',
        ]);

        Subject::create([
            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'color' => $request->color,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Thêm môn học thành công');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|unique:subjects,name,' . $subject->id,
            'color' => 'nullable',
        ]);

        $subject->update([
            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'color' => $request->color,
        ]);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Cập nhật môn học thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Xóa môn học thành công');
    }
}