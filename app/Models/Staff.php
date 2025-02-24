<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Staff extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'staffs';

    protected $fillable = [
        'name',
        'avatar',
        'birthday',
        'phone',
        'address',
        'email',
        'status',
        'salary',
        'role',
        'date_hired',
        'insurance_number',
        'contract_type',
        'contract_start',
        'contract_end',
        'notes',
    ];
    public function rooms()
    {
        return $this->hasMany(Room::class, 'manager_id');
    }
}
