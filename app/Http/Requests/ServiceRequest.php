<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
        $serviceId = $this->route('id'); // Lấy ID của dịch vụ từ route (dùng trong trường hợp update)

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Quy tắc unique: bỏ qua bản ghi hiện tại khi cập nhật
                Rule::unique('services', 'name')->ignore($serviceId),
            ],
            'price' => 'required|numeric|min:0',
            'roomTypes' => 'required|array|min:1',
            'roomTypes.*' => 'exists:room_types,id',
            'is_active' => 'required|in:0,1',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Thông báo lỗi cho trường name
            'name.required' => 'Tên dịch vụ không được bỏ trống.',
            'name.string' => 'Tên dịch vụ phải là một chuỗi ký tự.',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên dịch vụ đã tồn tại, vui lòng chọn tên khác.',

            // Thông báo lỗi cho trường price
            'price.required' => 'Giá dịch vụ không được bỏ trống.',
            'price.numeric' => 'Giá dịch vụ phải là một số.',
            'price.min' => 'Giá dịch vụ không được nhỏ hơn 0.',

            // Thông báo lỗi cho trường roomTypes
            'roomTypes.required' => 'Vui lòng chọn ít nhất một loại phòng.',
            'roomTypes.array' => 'Loại phòng phải là một mảng.',
            'roomTypes.min' => 'Vui lòng chọn ít nhất một loại phòng.',
            'roomTypes.*.exists' => 'Loại phòng được chọn không hợp lệ.',

            // Thông báo lỗi cho trường is_active
            'is_active.required' => 'Trạng thái không được bỏ trống.',
            'is_active.in' => 'Trạng thái không hợp lệ, chỉ được chọn Hoạt động hoặc Không hoạt động.',
        ];
    }
}
