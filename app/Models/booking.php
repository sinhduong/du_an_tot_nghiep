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
        'check_in',
        'check_out',
        'total_price',
        'status',
        'user_id',
        'room_id',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function room(){
        return $this->belongsTo(Room::class,'room_id');
    }
   public function payments(){
    return $this->hasMany(Payment::class,'booking_id');
   }
}
