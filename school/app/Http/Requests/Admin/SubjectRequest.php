<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
        $subjectId = $this->subject?->id;

        return [
            'name' => 'required|unique:subjects,name,' . $subjectId,
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên môn học không được để trống',
            'name.unique'   => 'Tên môn học đã tồn tại',
        ];
    }
}