<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AmenityRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần phân quyền
    }

    public function rules()
    {
        // Lấy ID của tiện ích từ route (dùng trong trường hợp update)
        $amenityId = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s0-9_-]+$/u', // Chỉ cho phép chữ cái, số, khoảng trắng, dấu gạch dưới và gạch ngang
                Rule::unique('amenities', 'name')->ignore($amenityId), // Đảm bảo tên là duy nhất, bỏ qua bản ghi hiện tại khi cập nhật
            ],
            'roomTypes' => 'nullable|array', // roomTypes có thể không bắt buộc, nhưng nếu có thì phải là mảng
            'roomTypes.*' => 'exists:room_types,id', // Kiểm tra các ID có tồn tại trong bảng room_types
        ];
    }

    public function messages()
    {
        return [
            // Thông báo lỗi cho trường name
            'name.required' => 'Tên tiện ích là bắt buộc.',
            'name.string' => 'Tên tiện ích phải là một chuỗi ký tự.',
            'name.max' => 'Tên tiện ích không được vượt quá 255 ký tự.',
            'name.regex' => 'Tên tiện ích chỉ được chứa chữ cái, số, khoảng trắng, dấu gạch dưới (_) và dấu gạch ngang (-).',
            'name.unique' => 'Tên tiện ích đã tồn tại, vui lòng chọn tên khác.',

            // Thông báo lỗi cho trường roomTypes
            'roomTypes.array' => 'Loại phòng phải là một mảng.',
            'roomTypes.*.exists' => 'Loại phòng được chọn không hợp lệ.',
        ];
    }
}
