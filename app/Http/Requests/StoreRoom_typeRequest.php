<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoom_typeRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100|regex:/^[\pL\s\d]+$/u|unique:room_types,name',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'max_capacity' => 'required|integer|min:1',
            'size' => 'required|numeric|min:0',
            'bed_type' => 'required|in:single,double,queen,king,bunk,sofa',
            'children_free_limit' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Cho phép nhiều ảnh
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên loại phòng không được để trống.',
            'name.string' => 'Tên loại phòng phải là chuỗi ký tự.',
            'name.min' => 'Tên loại phòng phải có ít nhất 3 ký tự.',
            'name.max' => 'Tên loại phòng không được vượt quá 100 ký tự.',
            'name.regex' => 'Tên loại phòng chỉ được chứa chữ cái, số và khoảng trắng.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',
            'description.string' => 'Mô tả phải là chuỗi ký tự.',
            'description.max' => 'Mô tả không được vượt quá 500 ký tự.',
            'price.required' => 'Giá phòng không được để trống.',
            'price.numeric' => 'Giá phòng phải là số.',
            'price.min' => 'Giá phòng không được nhỏ hơn 0.',
            'max_capacity.required' => 'Số người tối đa không được để trống.',
            'max_capacity.integer' => 'Số người tối đa phải là số nguyên.',
            'max_capacity.min' => 'Số người tối đa phải lớn hơn 0.',
            'size.required' => 'Kích thước phòng không được để trống.',
            'size.numeric' => 'Kích thước phòng phải là số.',
            'size.min' => 'Kích thước phòng không được nhỏ hơn 0.',
            'bed_type.required' => 'Loại giường không được để trống.',
            'bed_type.in' => 'Loại giường không hợp lệ.',
            'children_free_limit.required' => 'Số trẻ em miễn phí không được để trống.',
            'children_free_limit.integer' => 'Số trẻ em miễn phí phải là số nguyên.',
            'children_free_limit.min' => 'Số trẻ em miễn phí không được nhỏ hơn 0.',
            'is_active.required' => 'Vui lòng chọn trạng thái.',
            'is_active.boolean' => 'Trạng thái không hợp lệ.',
            'images.*.image' => 'Ảnh phải là định dạng hình ảnh.',
            'images.*.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, hoặc gif.',
            'images.*.max' => 'Mỗi ảnh không được vượt quá 2MB.',
        ];
    }
}
