<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckInRequest extends FormRequest
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
        // Lấy booking để kiểm tra total_guests
        $booking = Booking::find($this->booking_id);

        return [
            'booking_id' => ['required', 'exists:bookings,id'],
            'guests' => ['required', 'array', 'max:' . ($booking->total_guests ?? 0)],
            'guests.*.name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'guests.*.id_number' => ['required', 'string', 'max:20', 'regex:/^[0-9]{9,12}$/'],
            'guests.*.id_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'guests.*.birth_date' => ['required', 'date', 'before:today'],
            'guests.*.gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'guests.*.phone' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]{10,15}$/'],
            'guests.*.email' => ['nullable', 'email', 'max:255'],
            'guests.*.country' => ['nullable', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
            'guests.*.relationship' => ['nullable', 'string', 'max:100', 'regex:/^[\p{L}\s]+$/u'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'booking_id.required' => 'ID đặt phòng là bắt buộc.',
            'booking_id.exists' => 'Đặt phòng không tồn tại.',
            'guests.required' => 'Thông tin người ở là bắt buộc.',
            'guests.max' => 'Số lượng người ở vượt quá giới hạn cho phép (:max người).',
            'guests.*.name.required' => 'Tên người ở là bắt buộc.',
            'guests.*.name.min' => 'Tên người ở phải có ít nhất 3 ký tự.',
            'guests.*.name.regex' => 'Tên người ở chỉ được chứa chữ cái và khoảng trắng.',
            'guests.*.id_number.required' => 'Số CCCD là bắt buộc.',
            'guests.*.id_number.regex' => 'Số CCCD phải là số và có từ 9 đến 12 chữ số.',
            'guests.*.id_photo.image' => 'Ảnh CCCD phải là file hình ảnh.',
            'guests.*.id_photo.mimes' => 'Ảnh CCCD chỉ hỗ trợ định dạng jpeg, png, jpg, gif.',
            'guests.*.id_photo.max' => 'Ảnh CCCD không được vượt quá 2MB.',
            'guests.*.birth_date.required' => 'Ngày sinh là bắt buộc.',
            'guests.*.birth_date.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'guests.*.gender.required' => 'Giới tính là bắt buộc.',
            'guests.*.gender.in' => 'Giới tính không hợp lệ.',
            'guests.*.phone.regex' => 'Số điện thoại phải là số và có từ 10 đến 15 chữ số.',
            'guests.*.email.email' => 'Email không đúng định dạng.',
            'guests.*.country.regex' => 'Quốc gia chỉ được chứa chữ cái và khoảng trắng.',
            'guests.*.relationship.regex' => 'Mối quan hệ chỉ được chứa chữ cái và khoảng trắng.',
        ];
    }
}
