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
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Cho phép nhiều ảnh
            'updated_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra ảnh cập nhật
            'deleted_images' => 'nullable|json', // Đảm bảo deleted_images là JSON hợp lệ
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên loại phòng không được để trống.',
            'name.string' => 'Tên loại phòng phải là chuỗi ký tự.',
            'name.min' => 'Tên loại phòng phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên loại phòng không được vượt quá 100 ký tự.',
            'name.regex' => 'Tên loại phòng chỉ được chứa chữ, số và khoảng trắng.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',

            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',

            'price.required' => 'Giá không được để trống.',
            'price.numeric' => 'Giá phải là một số.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',

            'is_active.required' => 'Trạng thái không được để trống.',
            'is_active.boolean' => 'Trạng thái phải là true hoặc false.',

            'images.*.image' => 'Tệp tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'images.*.max' => 'Hình ảnh không được lớn hơn 2MB.',

            'updated_files.*.image' => 'Tệp cập nhật phải là hình ảnh.',
            'updated_files.*.mimes' => 'Hình ảnh cập nhật phải có định dạng: jpeg, png, jpg, gif.',
            'updated_files.*.max' => 'Hình ảnh cập nhật không được lớn hơn 2MB.',

            'deleted_images.json' => 'Danh sách ảnh bị xóa phải là JSON hợp lệ.',
        ];
    }
}
