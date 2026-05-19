<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index()
    {
        $subjects = Subject::with([
            'chapters' => function ($query) {

                $query->orderBy('order_index');

            }
        ])->orderBy('id')->get();

        return view(
            'admin.chapters.index',
            compact('subjects')
        );
    }

    public function create()
    {
        $subjects = Subject::with('chapters')->get();

        return view(
            'admin.chapters.create',
            compact('subjects')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
            'name' => 'required',
            'order_index' => 'required|integer',
        ]);

        Chapter::create([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'order_index' => $request->order_index,
        ]);

        return redirect()
            ->route('chapters.index')
            ->with('success', 'Chapter created successfully');
    }

    public function edit(Chapter $chapter)
    {
        $subjects = Subject::all();

        return view(
            'admin.chapters.edit',
            compact('chapter', 'subjects')
        );
    }

    public function update(Request $request, Chapter $chapter)
    {
        $request->validate([
            'subject_id' => 'required',
            'name' => 'required',
            'order_index' => 'required|integer',
        ]);

        $chapter->update([
            'subject_id' => $request->subject_id,
            'name' => $request->name,
            'order_index' => $request->order_index,
        ]);

        return redirect()
            ->route('chapters.index')
            ->with('success', 'Chapter updated successfully');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        return redirect()
            ->route('chapters.index')
            ->with('success', 'Chapter deleted successfully');
    }
}