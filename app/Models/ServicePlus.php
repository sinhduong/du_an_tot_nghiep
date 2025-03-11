<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePlus extends Model
{
    protected $table = 'service_plus'; 
    protected $fillable = [
        'name',
        'price',
        'is_active',
    ];
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service_plus', 'service_plus_id', 'booking_id')
            ->withPivot('quantity');
    }
}
