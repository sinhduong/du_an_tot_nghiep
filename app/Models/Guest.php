<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'id_number',
        'id_photo',
        'birth_date',
        'gender',
        'phone',
        'email',
        'relationship',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_guest', 'guest_id', 'booking_id');
    }
}
