<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'room_number',
        'price',
        'max_capacity',
        'bed_type',
        'children_free_limit',
        'room_type_id',
        'manager_id',
        'description',
        'status'
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
}
