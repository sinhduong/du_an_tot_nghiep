<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'name' => [
                "required","string","max:255",
                "unique:services,name," . $this->service
            ],
            'price' => 'required|numeric|min:0',
            'roomTypes' => 'required|array|min:1',
            'roomTypes.*' => 'exists:room_types,id',
        ];
    }

    public function messages():array
    {
        return [
            'name.required'=>'Tên dịch vụ phòng không được bỏ trống',
            'name.max'=>'Tên dịch vụ phòng không được vượt quá 255 ký tự',
            'price.required' => 'Giá không được bỏ trống'
        ];
    }
}
