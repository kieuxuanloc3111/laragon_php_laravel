<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Subject;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with([
            'chapter.subject',
            'answers'
        ])
        ->latest()
        ->get();

        return view(
            'admin.questions.index',
            compact('questions')
        );
    }

    public function create()
    {
        $subjects = Subject::with('chapters')
            ->orderBy('name')
            ->get();

        return view(
            'admin.questions.create',
            compact('subjects')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required',

            'content' => 'required',

            'difficulty' => 'required',

            'answers' => 'required|array|size:4',

            'correct_answer' => 'required',
        ]);

        $question = Question::create([

            'chapter_id' => $request->chapter_id,

            'created_by' => 1,

            'content' =>
                $this->cleanEditorHtml(
                    $request->content
                ),

            'explanation' =>
                $this->cleanEditorHtml(
                    $request->explanation
                ),

            'difficulty' => $request->difficulty,
        ]);

        foreach ($request->answers as $index => $answer) {

            Answer::create([

                'question_id' => $question->id,

                'content' =>
                    $this->cleanEditorHtml($answer),

                'is_correct' =>
                    $index == $request->correct_answer,
            ]);
        }

        return redirect()
            ->route('questions.index')
            ->with(
                'success',
                'Thêm câu hỏi thành công'
            );
    }

    public function edit(Question $question)
    {
        $question->load('answers');

        $subjects = Subject::with('chapters')
            ->get();

        return view(
            'admin.questions.edit',
            compact(
                'question',
                'subjects'
            )
        );
    }

    public function update(
        Request $request,
        Question $question
    )
    {
        $request->validate([
            'chapter_id' => 'required',

            'content' => 'required',

            'difficulty' => 'required',

            'answers' => 'required|array|size:4',

            'correct_answer' => 'required',
        ]);

        $question->update([

            'chapter_id' => $request->chapter_id,

            'content' =>
                $this->cleanEditorHtml(
                    $request->content
                ),

            'explanation' =>
                $this->cleanEditorHtml(
                    $request->explanation
                ),

            'difficulty' => $request->difficulty,
        ]);

        foreach (
            $question->answers
            as $index => $answer
        ) {

            $answer->update([

                'content' =>
                    $this->cleanEditorHtml(
                        $request->answers[$index]
                    ),

                'is_correct' =>
                    $index == $request->correct_answer,
            ]);
        }

        return redirect()
            ->route('questions.index')
            ->with(
                'success',
                'Cập nhật câu hỏi thành công'
            );
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with(
                'success',
                'Xóa câu hỏi thành công'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN HTML
    |--------------------------------------------------------------------------
    */

    private function cleanEditorHtml($html)
    {
        if (!$html) {
            return null;
        }

        $html = trim($html);

        /*
        |--------------------------------------------------------------------------
        | XÓA <p> bao ngoài
        |--------------------------------------------------------------------------
        */

        $html = preg_replace(
            '/^<p>(.*?)<\/p>$/is',
            '$1',
            $html
        );

        /*
        |--------------------------------------------------------------------------
        | XÓA <p> quanh img
        |--------------------------------------------------------------------------
        */

        $html = preg_replace(
            '/<p>\s*(<img[^>]+>)\s*<\/p>/is',
            '$1',
            $html
        );

        /*
        |--------------------------------------------------------------------------
        | XÓA &nbsp;
        |--------------------------------------------------------------------------
        */

        $html = str_replace(
            '&nbsp;',
            ' ',
            $html
        );

        return $html;
    }
}