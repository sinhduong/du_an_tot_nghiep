<?php

namespace App\Models;

use Illuminate\Support\Str;
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
        'total_price',
        'total_guests',
        'children_count',
        'status',
        'user_id',
        'room_id',
    ];
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
}
