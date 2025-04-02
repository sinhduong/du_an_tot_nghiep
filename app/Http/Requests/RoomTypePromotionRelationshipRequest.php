<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoomTypePromotionRelationshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $rules = [
            'room_type_id' => [
                'required',
                'exists:room_types,id',
            ],
            'promotion_id' => [
                'required',
                'exists:promotions,id',
            ],
        ];

        // Kiểm tra trùng lặp mối quan hệ
        $rules['room_type_id'][] = Rule::unique('promotion_room_type')
            ->where('promotion_id', $this->promotion_id)
            ->ignore($this->id); // Bỏ qua bản ghi hiện tại khi cập nhật

        return $rules;
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'room_type_id.required' => 'Vui lòng chọn loại phòng.',
            'room_type_id.exists' => 'Loại phòng không tồn tại.',
            'room_type_id.unique' => 'Mối quan hệ giữa loại phòng và khuyến mãi này đã tồn tại.',
            'promotion_id.required' => 'Vui lòng chọn khuyến mãi.',
            'promotion_id.exists' => 'Khuyến mãi không tồn tại.',
        ];
    }
}
