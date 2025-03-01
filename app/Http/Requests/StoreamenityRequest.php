<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmenityRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Điều chỉnh nếu cần phân quyền
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'room_type_id' => 'nullable|array',
            'room_type_id.*' => 'exists:room_types,id', // Kiểm tra các ID có tồn tại trong bảng room_types
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên tiện ích là bắt buộc.',
            'room_type_id.*.exists' => 'Loại phòng được chọn không hợp lệ.',
        ];
    }
}
