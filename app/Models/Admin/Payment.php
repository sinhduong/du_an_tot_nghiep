<?php

namespace App\Models\Admin;

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
    ];
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
