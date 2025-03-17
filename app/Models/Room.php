<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'room_number',
        'manager_id',
        'status',
        'room_type_id',
    ];
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_rooms', 'room_id', 'booking_id');
    }
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_id', 'id');
    }

}
