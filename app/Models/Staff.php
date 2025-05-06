<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'role_id',
        'status',
        'notes',
    ];
    public function rooms()
    {
        return $this->hasMany(Room::class, 'manager_id');
    }
    public function role()
    {
        return $this->belongsTo(StaffRole::class, 'role_id');
    }

    public function shift()
    {
        return $this->belongsTo(StaffShift::class, 'shift_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
