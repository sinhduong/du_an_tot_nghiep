<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
            'name',
            'room_number',
            'price',
            'capacity',
            'description',
            'status',
            'quantity',
        ];
        public function bookings(){
            return $this->hasMany(Booking::class,'room_id'); // 1 phòng có nhiều đơn đặt phòng
        }
        public function roomType()
        {
            return $this->belongsTo(RoomType::class, 'room_type_id');
        }

}
