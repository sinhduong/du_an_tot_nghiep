<?php

namespace App\Models\Admin;

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
}
