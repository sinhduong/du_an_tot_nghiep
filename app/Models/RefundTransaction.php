<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_id',
        'transaction_type',
        'amount',
        'status',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }
}