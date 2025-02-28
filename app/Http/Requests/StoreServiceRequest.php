<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            // 'price' => 'required|decimal|min:0|max:20'
        ];
    }

    public function messages():array
    {
        return [
            'name.required'=>'Tên dịch vụ phòng không được bỏ trống',
            'name.max'=>'Tên dịch vụ phòng không được vượt quá 255 ký tự',
            'price.required'=>'Giá dịch vụ phòng không được bỏ trống',
            'price.min'=>'Giá dịch vụ phòng phải lớn hơn 2',
            'price.max'=>'Giá dịch vụ phòng không được vượt quá 20',
        ];
    }
}
