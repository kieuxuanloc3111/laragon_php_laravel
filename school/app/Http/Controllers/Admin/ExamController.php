<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')
            ->withCount('questions')
            ->latest()
            ->get();

        return view(
            'admin.exams.index',
            compact('exams')
        );
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')
            ->get();

        return view(
            'admin.exams.create',
            compact('subjects')
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'subject_id' => 'required',

            'title' => 'required',

            'description' => 'nullable',

            'duration_minutes' =>
                'required|integer|min:1',

            'status' => 'required',

        ]);

        $exam = Exam::create([

            'subject_id' =>
                $request->subject_id,

            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'duration_minutes' =>
                $request->duration_minutes,

            'status' =>
                $request->status,

            'created_by' => 1,

            'total_questions' => 0,

        ]);

        return redirect()
            ->route(
                'exams.edit',
                $exam->id
            )
            ->with(
                'success',
                'Tạo đề thi thành công'
            );
    }

    public function edit(Exam $exam)
    {
        $exam->load([
            'questions.answers',
            'questions.chapter.subject',
            'subject'
        ]);

        $subjects = Subject::orderBy('name')
            ->get();

        $questions = Question::with([
                'chapter.subject',
                'answers'
            ])
            ->whereHas(
                'chapter',
                function ($query) use ($exam) {

                    $query->where(
                        'subject_id',
                        $exam->subject_id
                    );

                }
            )
            ->latest()
            ->get();

        return view(
            'admin.exams.edit',
            compact(
                'exam',
                'subjects',
                'questions'
            )
        );
    }

    public function update(
        Request $request,
        Exam $exam
    )
    {
        $request->validate([
            'subject_id' => 'required',
            'title' => 'required',

            'description' => 'nullable',

            'duration_minutes' =>
                'required|integer|min:1',

            'status' => 'required',

        ]);

        $exam->update([

            'subject_id' =>
                $request->subject_id,

            'title' =>
                $request->title,

            'description' =>
                $request->description,

            'duration_minutes' =>
                $request->duration_minutes,

            'status' =>
                $request->status,

        ]);

        /*
        |--------------------------------------------------------------------------
        | SYNC QUESTIONS
        |--------------------------------------------------------------------------
        */

        $questionIds =
            $request->question_ids ?? [];

        $exam->questions()
            ->sync($questionIds);

        /*
        |--------------------------------------------------------------------------
        | UPDATE TOTAL QUESTIONS
        |--------------------------------------------------------------------------
        */

        $exam->update([

            'total_questions' =>
                count($questionIds)

        ]);

        return redirect()
            ->route(
                'exams.edit',
                $exam->id
            )
            ->with(
                'success',
                'Cập nhật đề thi thành công'
            );
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()
            ->route('exams.index')
            ->with(
                'success',
                'Xóa đề thi thành công'
            );
    }
}