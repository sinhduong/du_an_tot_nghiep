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
        'max_capacity',
        'size',
        'bed_type',
        'children_free_limit',
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

    public function services()
    {
        return $this->belongsToMany(Service::class, 'room_type_services', 'room_type_id', 'service_id');
    }

    public function rulesAndRegulations()
    {
        return $this->belongsToMany(RulesAndRegulation::class, 'room_type_rars', 'room_type_id', 'rules_and_regulation_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_type_amenities', 'room_type_id', 'amenity_id');
    }
    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_room_type', 'room_type_id', 'promotion_id')
            ->withTimestamps();
    }
}
