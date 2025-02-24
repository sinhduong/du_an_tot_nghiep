<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoom_typeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'name' => 'required|string|min:3|max:100|regex:/^[\pL\s\d]+$/u|unique:room_types,name,' . $this->route('id'),
            'is_active' => 'required|boolean',
        ];
    }
    public function messages():array
    {
        return [
            'name.required' => 'Tên loại phòng không được để trống.',
            'name.string' => 'Tên loại phòng phải là chuỗi ký tự.',
            'name.min' => 'Tên loại phòng phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên loại phòng không được vượt quá 100 ký tự.',
            'name.regex' => 'Tên loại phòng chỉ được chứa chữ cái, số và khoảng trắng.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}
