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
            $rules['value'] = 'required|numeric|min:0|max:100';
        }

        return $rules;
    }
}
