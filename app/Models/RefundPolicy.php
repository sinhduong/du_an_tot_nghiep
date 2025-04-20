<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_before_checkin',
        'refund_percentage',
        'cancellation_fee_percentage',
        'description',
        'is_active'
    ];

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}