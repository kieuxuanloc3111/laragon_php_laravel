<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | CHAPTER
            |--------------------------------------------------------------------------
            */

            'chapter_id' => 'required|exists:chapters,id',

            /*
            |--------------------------------------------------------------------------
            | QUESTION CONTENT
            |--------------------------------------------------------------------------
            |
            | CKEditor + Math + HTML + Image
            |
            */

            'content' => 'required',

            /*
            |--------------------------------------------------------------------------
            | DIFFICULTY
            |--------------------------------------------------------------------------
            */

            'difficulty' => 'required|in:easy,medium,hard',

            /*
            |--------------------------------------------------------------------------
            | ANSWERS
            |--------------------------------------------------------------------------
            |
            | Cho phép HTML + image
            |
            */

            'answers' => 'required|array|size:4',

            'answers.*' => 'required',

            /*
            |--------------------------------------------------------------------------
            | CORRECT ANSWER
            |--------------------------------------------------------------------------
            */

            'correct_answer' => 'required|integer|between:0,3',

            /*
            |--------------------------------------------------------------------------
            | EXPLANATION
            |--------------------------------------------------------------------------
            |
            | nullable vì có thể không giải thích
            |
            */

            'explanation' => 'nullable',
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | CHAPTER
            |--------------------------------------------------------------------------
            */

            'chapter_id.required' => 'Vui lòng chọn chương',

            'chapter_id.exists' => 'Chương không tồn tại',

            /*
            |--------------------------------------------------------------------------
            | CONTENT
            |--------------------------------------------------------------------------
            */

            'content.required' => 'Vui lòng nhập nội dung câu hỏi',

            /*
            |--------------------------------------------------------------------------
            | DIFFICULTY
            |--------------------------------------------------------------------------
            */

            'difficulty.required' => 'Vui lòng chọn độ khó',

            'difficulty.in' => 'Độ khó không hợp lệ',

            /*
            |--------------------------------------------------------------------------
            | ANSWERS
            |--------------------------------------------------------------------------
            */

            'answers.required' => 'Vui lòng nhập đáp án',

            'answers.array' => 'Định dạng đáp án không hợp lệ',

            'answers.size' => 'Phải có đúng 4 đáp án',

            'answers.*.required' => 'Không được để trống đáp án',

            /*
            |--------------------------------------------------------------------------
            | CORRECT ANSWER
            |--------------------------------------------------------------------------
            */

            'correct_answer.required' => 'Vui lòng chọn đáp án đúng',

            'correct_answer.integer' => 'Đáp án đúng không hợp lệ',

            'correct_answer.between' => 'Đáp án đúng không hợp lệ',
        ];
    }
}