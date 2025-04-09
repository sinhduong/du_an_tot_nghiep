<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'is_active'
    ];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'room_type_services', 'service_id', 'room_type_id');
    }
    // Xử lý xóa mềm hoặc xóa cứng
    protected static function boot()
    {
        parent::boot();

        // Trước khi xóa (cả xóa mềm và xóa cứng)
        static::deleting(function ($service) {
            // Xóa các bản ghi liên quan trong room_type_services
            $service->roomTypeServices()->delete();
            // Lưu ý: Các bản ghi trong booking_room_type_service sẽ được xóa tự động thông qua RoomTypeService
        });
    }
}
