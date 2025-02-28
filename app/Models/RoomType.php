<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
    ];
    public function rooms()
    {
        return $this->hasMany(Room::class, 'room_type_id');
    }

    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }
    public function roomTypeImages()
    {
        return $this->hasMany(RoomTypeImage::class, 'room_type_id');
    }
}
