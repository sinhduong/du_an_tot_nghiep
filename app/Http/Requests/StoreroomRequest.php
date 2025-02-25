<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreroomRequest extends FormRequest
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
            'manager_id' => 'nullable|integer|exists:users,id',
            'room_number' => 'required|string|unique:rooms,room_number|max:20',
            'price' => 'nullable|numeric|min:0|max:999999999999999999.99',
            'max_capacity' => 'required|integer|min:1|max:50',
            'bed_type' => 'required|in:single,double,queen,king,bunk,sofa',
            'children_free_limit' => 'nullable|integer|min:0|max:10',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:available,booked,maintenance',
            'room_type_id' => 'required|integer|exists:room_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên phòng không được để trống.',
            'room_number.required' => 'Số phòng là bắt buộc.',
            'room_number.unique' => 'Số phòng này đã tồn tại.',
            'price.numeric' => 'Giá phòng phải là số.',
            'max_capacity.required' => 'Vui lòng nhập số lượng khách tối đa.',
            'bed_type.in' => 'Loại giường không hợp lệ.',
            'status.in' => 'Trạng thái phòng không hợp lệ.',
            'room_type_id.exists' => 'Loại phòng không tồn tại.',
        ];
    }
}
