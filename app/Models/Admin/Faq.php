<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'question',
        'answer',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
