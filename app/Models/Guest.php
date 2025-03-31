<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'country',
        'relationship',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_guest', 'guest_id', 'booking_id');
    }
    // Xóa file ảnh khi xóa guest
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($guest) {
            if ($guest->id_photo) {
                Storage::disk('public')->delete($guest->id_photo);
            }
        });
    }
}
