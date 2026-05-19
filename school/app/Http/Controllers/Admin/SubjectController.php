<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\SubjectRequest;
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
    public function store(SubjectRequest $request)
    {


        /*
        |--------------------------------------------------------------------------
        | Random màu không trùng
        |--------------------------------------------------------------------------
        */

        do {

            $color = sprintf(
                '#%06X',
                mt_rand(0, 0xFFFFFF)
            );

        } while (
            Subject::where('color', $color)->exists()
        );

        Subject::create([
            'name' => $request->name,

            'slug' => Str::slug($request->name),

            'color' => $color,
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
    public function update(SubjectRequest $request, Subject $subject)
    {

        $subject->update([
            'name' => $request->name,

            'slug' => Str::slug($request->name),
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