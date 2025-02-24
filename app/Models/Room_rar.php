<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room_rar extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['room_id', 'rules_and_regulation_id'];
   

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function rule()
    {
        return $this->belongsTo(Rules_and_regulation::class, 'rules_and_regulation_id');
    }
}
