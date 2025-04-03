<?php

namespace App\Models;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleRoomType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value',
        'type',
        'room_type_id',
        'start_date',
        'end_date',
        'status',

    ];
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
