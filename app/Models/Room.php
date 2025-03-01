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
        return $this->hasMany(Booking::class, 'room_id'); // 1 phòng có nhiều đơn đặt phòng
    }
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities', 'room_id', 'amenity_id');
    }
    public function rulesAndRegulations()
    {
        return $this->belongsToMany(RulesAndRegulation::class, 'room_rars', 'room_id', 'rules_and_regulation_id');
    }

    public function rules()
    {
        return $this->belongsToMany(
            RulesAndRegulation::class,
            'room_rars',
            'room_id',
            'rules_and_regulation_id'
        )
            ->withPivot('id') // Lấy ID của bảng `room_rars`
            ->withTimestamps();
    }
    public function rars()
    {
        return $this->hasMany(Room_rar::class, 'room_id');
    }




    public function rameniti()
    {
        return $this->hasMany(Room_amenity::class, 'room_id');
    }
}
