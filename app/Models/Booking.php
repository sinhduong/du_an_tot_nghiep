<?php

namespace App\Models;

use App\Models\BookingRoomTypeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'booking_code',
        'check_in',
        'check_out',
        'actual_check_in',
        'actual_check_out',
        'total_price',
        'discount_amount',
        'base_price',
        'service_total',
        'tax_fee',
        'total_guests',
        'children_count',
        'room_quantity',
        'status',
        'user_id',
        // 'room_id',
        'guest_id',
        'special_request',
        'service_plus_status', // Thêm trường này
        'paid_amount',
        'service_plus_total'

    ];
    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    // Accessor để lấy thời gian nhận phòng
    public function getCheckInTimeAttribute()
    {
        return $this->check_in->format('H:i');
    }

    // Accessor để lấy thời gian trả phòng
    public function getCheckOutTimeAttribute()
    {
        return $this->check_out->format('H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_rooms', 'booking_id', 'room_id');
    }
    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'booking_guest', 'booking_id', 'guest_id');
    }
    public function ServicePlus()
    {
        return $this->belongsToMany(ServicePlus::class, 'booking_service_plus', 'booking_id', 'service_plus_id')
            ->withPivot('quantity');
    }

    public function Promotions() {
        return $this->belongsToMany(Promotion::class, 'booking_promotions', 'booking_id', 'promotion_id');
    }

    // Quan hệ với BookingRoomTypeService (dịch vụ liên quan đến loại phòng)
    public function bookingRoomTypeServices()
    {
        return $this->hasMany(BookingRoomTypeService::class, 'booking_id');
    }

    public function services()
    {
        return $this->belongsToMany(RoomTypeService::class, 'booking_room_type_services', 'booking_id', 'room_type_service_id')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function getServiceDetailsAttribute()
    {
        return $this->services->map(function($roomTypeService) {
            return [
                'name' => $roomTypeService->service->name,
                'quantity' => $roomTypeService->pivot->quantity,
                'price' => $roomTypeService->pivot->price
            ];
        });
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
