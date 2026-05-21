<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')
            ->withCount('questions')
            ->latest()
            ->get();
        Log::info($exams->toArray());
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

        $subjects = Subject::with('chapters')
            ->orderBy('name')
            ->get();

        $questions = Question::with([
                'chapter.subject',
                'answers'
            ])
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
    public function autoGenerate(
        Request $request,
        Exam $exam
    )
    {
        $request->validate([

            'easy_percent' =>
                'required|integer|min:0|max:100',

            'medium_percent' =>
                'required|integer|min:0|max:100',

            'hard_percent' =>
                'required|integer|min:0|max:100',

            'chapter_id' => 'nullable',

            'question_count' =>
                'required|integer|min:1',

        ]);
        $totalPercent =$request->easy_percent +$request->medium_percent +$request->hard_percent;

        if ($totalPercent != 100) {

            return back()->with(
                'error',
                'Tổng phần trăm phải bằng 100'
            );
        }

        $query = Question::query()

            ->whereHas(
                'chapter',
                function ($q) use ($exam) {

                    $q->where(
                        'subject_id',
                        $exam->subject_id
                    );

                }
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER CHAPTER
        |--------------------------------------------------------------------------
        */

        if ($request->chapter_id) {

            $query->where(
                'chapter_id',
                $request->chapter_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER DIFFICULTY
        |--------------------------------------------------------------------------
        */
        $totalQuestions =
            (int) $request->question_count;

        $easyCount = floor(
            $totalQuestions *
            $request->easy_percent / 100
        );

        $mediumCount = floor(
            $totalQuestions *
            $request->medium_percent / 100
        );

        $hardCount =

            $totalQuestions -

            $easyCount -

            $mediumCount;

        /*
        |--------------------------------------------------------------------------
        | GET QUESTIONS
        |--------------------------------------------------------------------------
        */

        $easyQuestions = (clone $query)

            ->where('difficulty', 'easy')

            ->inRandomOrder()

            ->limit($easyCount)

            ->pluck('id');

        $mediumQuestions = (clone $query)

            ->where('difficulty', 'medium')

            ->inRandomOrder()

            ->limit($mediumCount)

            ->pluck('id');

        $hardQuestions = (clone $query)

            ->where('difficulty', 'hard')

            ->inRandomOrder()

            ->limit($hardCount)

            ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | MERGE
        |--------------------------------------------------------------------------
        */

        $questions = collect()

            ->merge($easyQuestions)

            ->merge($mediumQuestions)

            ->merge($hardQuestions)

            ->unique();

        /*
        |--------------------------------------------------------------------------
        | FILL MISSING QUESTIONS
        |--------------------------------------------------------------------------
        */

        $currentCount =
            $questions->count();

        $missing =
            $totalQuestions - $currentCount;

        if ($missing > 0) {

            $extraQuestions = (clone $query)

                ->whereNotIn(
                    'id',
                    $questions->toArray()
                )

                ->inRandomOrder()

                ->limit($missing)

                ->pluck('id');

            $questions = $questions
                ->merge($extraQuestions)
                ->unique();
        }

        /*
        |--------------------------------------------------------------------------
        | SYNC
        |--------------------------------------------------------------------------
        */

        $exam->questions()
            ->sync($questions->toArray());

        /*
        |--------------------------------------------------------------------------
        | UPDATE TOTAL
        |--------------------------------------------------------------------------
        */

        $exam->update([

            'total_questions' =>

                $exam->questions()
                    ->count()

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Tạo đề tự động thành công'
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