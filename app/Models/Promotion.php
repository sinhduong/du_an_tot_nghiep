<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'value',
        'start_date',
        'end_date',
        'max_discount_value',
        'min_booking_amount',
        'quantity',
        'type',
        'status',
    ];


    public function bookings() {
        return $this->belongsToMany(Booking::class, 'booking_promotions', 'promotion_id', 'booking_id');
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
