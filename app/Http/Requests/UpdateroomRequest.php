<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateroomRequest extends FormRequest
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
        $roomId = $this->route('room');
        return [
            'manager_id' => 'nullable|integer|exists:users,id',
            'room_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('rooms', 'room_number')->ignore($roomId),
            ],
            'status' => 'required|in:available,booked,maintenance',
            'room_type_id' => 'required|integer|exists:room_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'room_number.required' => 'Số phòng là bắt buộc.',
            'room_number.unique' => 'Số phòng này đã tồn tại.',
            'status.in' => 'Trạng thái phòng không hợp lệ.',
            'room_type_id.exists' => 'Loại phòng không tồn tại.',
        ];
    }
}
