<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'required|url',
            'is_use' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên banner',
            'name.max' => 'Tên banner không được vượt quá 255 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh banner',
            'image.file' => 'File không hợp lệ',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
            'link.required' => 'Link không được để trống',
            'link.url' => 'Link không đúng định dạng URL',
            'is_use.required' => 'Vui lòng chọn trạng thái sử dụng',
        ];
    }
}
