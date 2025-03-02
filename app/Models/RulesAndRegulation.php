<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RulesAndRegulation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_rars', 'rules_and_regulation_id', 'room_type_id');
    }

}
