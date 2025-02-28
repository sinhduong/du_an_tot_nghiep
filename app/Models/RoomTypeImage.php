<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomTypeImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'room_type_id',
        'image',
        'is_main',
    ];
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
