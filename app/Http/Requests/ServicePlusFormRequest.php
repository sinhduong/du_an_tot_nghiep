<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServicePlusFormRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Thay bằng logic kiểm tra quyền nếu cần
    }

    public function rules()
    {
        // Lấy ID từ route, tham số là 'id' (dựa trên route)
        $serviceId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}0-9\s_-]+$/u',
                Rule::unique('service_plus', 'name')->ignore($serviceId), // Sửa tên bảng thành 'service_plus'
            ],
            'price' => 'required|numeric|min:0|max:999999999',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên dịch vụ là bắt buộc.',
            'name.string' => 'Tên dịch vụ phải là chuỗi ký tự.',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên dịch vụ đã tồn tại, vui lòng chọn tên khác.',
            'name.regex' => 'Tên dịch vụ chỉ cho phép chữ cái, số, khoảng trắng, dấu gạch dưới (_) và dấu gạch ngang (-).',
            'price.required' => 'Giá dịch vụ là bắt buộc.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được nhỏ hơn 0.',
            'price.max' => 'Giá không được vượt quá :max.',
            'is_active.required' => 'Trạng thái là bắt buộc.',
            'is_active.boolean' => 'Trạng thái phải là đúng hoặc sai.',
        ];
    }
}
