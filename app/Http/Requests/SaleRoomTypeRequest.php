<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả người dùng (có thể thêm logic phân quyền nếu cần)
    }

    public function rules(): array
    {
        // Quy tắc chung
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'value' => 'required|numeric',
            'type' => 'required|string|in:percent,fixed',
            'room_type_ids' => 'required|array', // Validate mảng room_type_ids
            'room_type_ids.*' => 'exists:room_types,id', // Kiểm tra từng ID trong mảng
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ];

        // Thêm quy tắc unique cho name
        if ($this->route()->getName() === 'admin.sale-room-types.store') {
            // Khi tạo mới: không được trùng với bất kỳ bản ghi nào
            $rules['name'][] = 'unique:sale_room_types,name';
        } elseif ($this->route()->getName() === 'admin.sale-room-types.update') {
            // Khi cập nhật: không được trùng với các bản ghi khác, trừ bản ghi hiện tại
            $saleRoomType = $this->route('saleRoomType');
            if ($saleRoomType) {
                $saleRoomTypeId = $saleRoomType->id;
                $rules['name'][] = Rule::unique('sale_room_types', 'name')->ignore($saleRoomTypeId);
            }
        }

        // Nếu là toggleStatus, chỉ cần validate status
        if ($this->route()->getName() === 'admin.sale-room-types.toggle-status') {
            return [
                'status' => 'required|in:active,inactive',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên khuyến mãi là bắt buộc.',
            'name.string' => 'Tên khuyến mãi phải là chuỗi ký tự.',
            'name.max' => 'Tên khuyến mãi không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên khuyến mãi đã tồn tại, vui lòng chọn tên khác.',
            'value.required' => 'Giá trị khuyến mãi là bắt buộc.',
            'value.numeric' => 'Giá trị khuyến mãi phải là số.',
            'type.required' => 'Loại khuyến mãi là bắt buộc.',
            'type.in' => 'Loại khuyến mãi phải là "Phần trăm" hoặc "Số tiền cố định".',
            'room_type_ids.required' => 'Loại phòng là bắt buộc.',
            'room_type_ids.array' => 'Loại phòng phải là một mảng.',
            'room_type_ids.*.exists' => 'Loại phòng không tồn tại.',
            'start_date.required' => 'Ngày giờ bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày giờ bắt đầu không hợp lệ.',
            'end_date.required' => 'Ngày giờ kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày giờ kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày giờ kết thúc phải sau ngày giờ bắt đầu.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái phải là "Hoạt động" hoặc "Không hoạt động".',
        ];
    }
}
