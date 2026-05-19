<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChapterRequest extends FormRequest
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
            'subject_id' => 'required|exists:subjects,id',

            'name' => 'required|string|max:255',

            'order_index' => 'required|integer|min:1',
        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [

            'subject_id.required' => 'Vui lòng chọn môn học',
            'subject_id.exists'   => 'Môn học không tồn tại',

            'name.required' => 'Tên chương không được để trống',
            'name.max'      => 'Tên chương quá dài',

            'order_index.required' => 'Vui lòng nhập thứ tự',
            'order_index.integer'  => 'Thứ tự phải là số',
            'order_index.min'      => 'Thứ tự phải lớn hơn 0',
        ];
    }
}