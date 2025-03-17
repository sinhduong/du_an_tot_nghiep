<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
