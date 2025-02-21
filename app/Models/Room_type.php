<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room_type extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'room_types';
    protected $fillable = ['name', 'is_active', 'manager_id'];
    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }
}
