<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'is_active'
    ];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_services', 'service_id', 'room_type_id');
    }
}
