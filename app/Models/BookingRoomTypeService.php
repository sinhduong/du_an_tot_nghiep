<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingRoomTypeService extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'booking_id',
        'room_type_service_id',
        'quantity',
        'price',
    ];
    // Quan hệ với bảng room_type_service
    public function roomTypeService()
    {
        return $this->belongsTo(RoomTypeService::class, 'room_type_service_id');
    }

    // Quan hệ với Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
