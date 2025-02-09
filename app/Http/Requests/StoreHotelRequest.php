<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
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
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_min' => 'required|numeric',
            'price_max' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được bỏ trống',
            'address.string' => 'Địa chỉ phải là văn bản',
            'city.string' => 'Thành phố phải là văn bản',
            'description.string' => 'Mô tả phải là văn bản',
            'price_min.required' => 'Giá bắt đầu không được bỏ trống',
            'price_min.numeric' => 'Giá bắt đầu phải là số',
            'price_max.required' => 'Giá kết thúc không được bỏ trống',
            'price_max.numeric' => 'Giá kết thúc phải là số',
        ];
    }
}
