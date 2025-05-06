<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'code' => [
                "required", "string", "max:255",
                request()->isMethod("POST") ? "unique:promotions,code" : "unique:promotions,code," . $this->promotion
            ],
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'min_booking_amount' => 'required|integer|min:0',
            'max_discount_value' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:percent,fixed',
        ];

        if ($this->input('type') === 'percent') {
            $rules['value'] = 'required|numeric|min:0|max:50';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên mã giảm giá không được để trống',
            'code.required' => 'Mã giảm giá không được để trống',
            'code.unique' => 'Mã giảm giá đã tồn tại',
            'value.required' => 'Giá trị giảm giá không được để trống',
            'value.numeric' => 'Giá trị giảm giá phải là số',
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'start_date.date' => 'Ngày bắt đầu phải là ngày',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.date' => 'Ngày kết thúc phải là ngày',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
            'min_booking_amount.required' => 'Số tiền đặt cọc tối thiểu không được để trống',
            'min_booking_amount.integer' => 'Số tiền đặt cọc tối thiểu phải là số',
            'min_booking_amount.min' => 'Số tiền đặt cọc tối thiểu phải lớn hơn 0',
            'max_discount_value.required' => 'Giá trị giảm giá tối đa không được để trống',
            'max_discount_value.integer' => 'Giá trị giảm giá tối đa phải là số',
            'max_discount_value.min' => 'Giá trị giảm giá tối đa phải lớn hơn 0',
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
            'quantity.required' => 'Số lượng không được để trống',
            'quantity.integer' => 'Số lượng phải là số',
            'quantity.min' => 'Số lượng phải lớn hơn 0',
            'type.required' => 'Loại giảm giá không được để trống',
            'type.in' => 'Loại giảm giá không hợp lệ',
            
        ];
    }  
    
}
