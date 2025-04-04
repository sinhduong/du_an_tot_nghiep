<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'booking_id', 'rating', 'comment', 'response'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \InvalidArgumentException('Xếp hạng phải nằm trong khoảng từ 1 đến 5.');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Booking (thay vì Room)
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
