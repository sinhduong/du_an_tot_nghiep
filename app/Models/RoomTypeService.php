<?php

namespace App\Models;

use App\Models\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomTypeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'is_active'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_services', 'service_id', 'room_type_id');
    }
}
