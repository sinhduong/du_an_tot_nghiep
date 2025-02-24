<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rules_and_regulation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'name',
        
    ];
   
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_rars', 'rules_and_regulation_id', 'room_id');
    }
}
