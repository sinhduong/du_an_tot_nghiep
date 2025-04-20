<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'refund_policy_id',
        'amount',
        'cancellation_fee',
        'status',
        'reason',
        'admin_notes',
        'approved_by',
        'approved_at',
        'refund_method',
        'transaction_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function refundPolicy()
    {
        return $this->belongsTo(RefundPolicy::class, 'refund_policy_id');
    }

    public function transactions()
    {
        return $this->hasMany(RefundTransaction::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}