<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room_amenity extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['room_id', 'amenity_id'];
    
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id');
    }


}

