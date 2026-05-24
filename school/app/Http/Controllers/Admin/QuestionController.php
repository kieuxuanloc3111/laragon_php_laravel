<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Http\Requests\Admin\QuestionRequest;
use Illuminate\Support\Facades\Validator;

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

    public function store(QuestionRequest  $request)
    {

        $question = Question::create([

            'chapter_id' => $request->chapter_id,

            'created_by' => auth()->id() ?? 1,

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
        $question->load([
            'chapter.subject.chapters',
            'answers',
        ]);

        $subjects = Subject::with('chapters')
            ->orderBy('name')
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
        QuestionRequest $request,
        Question $question
    )
    {

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
            $question->answers()->orderBy('id')->get()
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

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:4096',
            ],
        ]);

        if ($validator->fails()) {
            return $this->ckeditorUploadResponse(
                $request,
                null,
                'Anh tai len khong hop le. Vui long chon file jpg, png, webp hoac gif toi da 4MB.',
                422
            );
        }

        $path = $request
            ->file('upload')
            ->store('question-images', 'public');

        $url = asset(
            'storage/' . $path
        );

        return $this->ckeditorUploadResponse(
            $request,
            $url,
            'Tai anh len thanh cong.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN HTML
    |--------------------------------------------------------------------------
    */

    private function cleanEditorHtml($html)
    {
        if ($html === null || $html === '') {
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

    private function ckeditorUploadResponse(
        Request $request,
        ?string $url,
        string $message = '',
        int $status = 200
    ) {
        if ($request->has('CKEditorFuncNum')) {
            $funcNum = (int) $request->input('CKEditorFuncNum');

            return response(
                '<script>window.parent.CKEDITOR.tools.callFunction('
                    . $funcNum
                    . ', '
                    . json_encode($url ?? '')
                    . ', '
                    . json_encode($message)
                    . ');</script>',
                200
            )->header('Content-Type', 'text/html; charset=utf-8');
        }

        if (! $url) {
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => $message,
                ],
            ], $status);
        }

        return response()->json([
            'uploaded' => 1,
            'fileName' => basename($url),
            'url' => $url,
        ]);
    }
}
