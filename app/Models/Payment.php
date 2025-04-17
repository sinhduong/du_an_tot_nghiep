<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'method',
        'amount',
        'status',
        'transaction_id',
        'booking_id',
        'user_id',
        'is_partial'
    ];
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
