<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoom_typeRequest;
use App\Http\Requests\UpdateRoom_typeRequest;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Models\SaleRoomType;
use App\Models\Booking; // Giả sử bạn có model Booking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoomTypeController extends Controller
{
    // public function __construct()
    // {
    //     // Áp dụng middleware phân quyền cho các phương thức
    //     $this->middleware('middleware:permission:room_types.list')->only(['index']);
    //     $this->middleware('middleware:permission:room_types.create')->only(['create', 'store']);
    //     $this->middleware('middleware:permission:room_types.detail')->only(['show']);
    //     $this->middleware('middleware:permission:room_types.update')->only(['edit', 'update']);
    //     $this->middleware('middleware:permission:room_types.delete')->only(['destroy']);
    //     $this->middleware('middleware:permission:room_types.trashed')->only(['trashed']);
    //     $this->middleware('middleware:permission:room_types.restore')->only(['restore']);
    //     $this->middleware('middleware:permission:room_types.force_delete')->only(['forceDelete']);
    // }
    public function index(Request $request)
    {
        $title = 'Danh sách loại phòng';
        $query = RoomType::orderBy('is_active', 'desc');

        $room_types = $query->get();
        return view('admins.room-type.index', compact('title', 'room_types'));
    }

    public function create()
    {
        $title = 'Thêm loại phòng';
        return view('admins.room-type.create', compact('title'));
    }

    public function store(StoreRoom_typeRequest $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->except('_token', 'images');
            $roomType = RoomType::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $imagePath = $image->store('room_type_images', 'public');
                    RoomTypeImage::create([
                        'room_type_id' => $roomType->id,
                        'image' => $imagePath,
                        'is_main' => ($key == 0) ? true : false,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Thêm loại phòng thành công',
                'redirect' => route('admin.room_types.index')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Yêu cầu không hợp lệ'
        ], 400);
    }

    public function show(string $id)
    {
        $title = 'Chi tiết loại phòng';
        $roomType = RoomType::with('roomTypeImages')->findOrFail($id);
        return view('admins.room-type.detail', compact('roomType', 'title'));
    }

    public function edit(string $id)
    {
        $title = 'Sửa loại phòng';
        $roomType = RoomType::with('roomTypeImages')->findOrFail($id);
        return view('admins.room-type.edit', compact('roomType', 'title'));
    }

    public function update(UpdateRoom_typeRequest $request, string $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            $data = $request->except('_token', '_method', 'images', 'deleted_images', 'updated_images');
            $roomType->update($data);

            if ($request->has('deleted_images')) {
                $deletedImages = json_decode($request->input('deleted_images'), true);
                if (!empty($deletedImages) && is_array($deletedImages)) {
                    $imagesToDelete = RoomTypeImage::whereIn('id', $deletedImages)->get();
                    foreach ($imagesToDelete as $image) {
                        $imagePath = $image->image;
                        if (Storage::disk('public')->exists($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                            Log::info("Deleted image: {$imagePath}");
                        } else {
                            Log::warning("Image not found: {$imagePath}");
                        }
                        $image->delete();
                    }
                }
            }

            if ($request->has('updated_images')) {
                $updatedImages = json_decode($request->input('updated_images'), true);
                foreach ($updatedImages as $imageId => $tempPath) {
                    $image = RoomTypeImage::find($imageId);
                    if ($image && $request->hasFile("updated_files.{$imageId}")) {
                        $oldImagePath = $image->image;
                        if (Storage::disk('public')->exists($oldImagePath)) {
                            Storage::disk('public')->delete($oldImagePath);
                            Log::info("Deleted old image: {$oldImagePath}");
                        }
                        $newImage = $request->file("updated_files.{$imageId}");
                        $imagePath = $newImage->store('room_type_images', 'public');
                        $image->image = $imagePath;
                        $image->save();
                        Log::info("Updated image ID {$imageId} with new path: {$imagePath}");
                    } else {
                        Log::warning("Image ID {$imageId} not found or no file uploaded");
                    }
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $imagePath = $image->store('room_type_images', 'public');
                    RoomTypeImage::create([
                        'room_type_id' => $roomType->id,
                        'image' => $imagePath,
                        'is_main' => $key == 0 && !$roomType->roomTypeImages()->where('is_main', true)->exists(),
                    ]);
                    Log::info("Added new image: {$imagePath}");
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật loại phòng thành công',
                'redirect' => route('admin.room_types.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Error in RoomTypeController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);

            // Kiểm tra xem loại phòng có đang được sử dụng trong đặt phòng không
            $bookingCount = Booking::where('room_type_id', $roomType->id)->count();
            if ($bookingCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa loại phòng này vì đang có ' . $bookingCount . ' đặt phòng liên quan.'
                ], 400);
            }

            // Xóa mềm các dữ liệu liên quan
            // 1. Xóa mềm các hình ảnh liên quan (RoomTypeImage)
            $roomType->roomTypeImages()->delete();

            // 2. Nếu có các khuyến mãi liên quan (SaleRoomType), xóa mềm chúng
            SaleRoomType::where('room_type_id', $roomType->id)->delete();

            // Xóa mềm RoomType
            $roomType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loại phòng đã được xóa mềm thành công',
                'redirect' => route('admin.room_types.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Error in RoomTypeController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function trashed()
    {
        $title = 'Thùng rác';
        $room_types = RoomType::onlyTrashed()->get();
        return view('admins.room-type.trashed', compact('title', 'room_types'));
    }

    public function restore(Request $request, $id)
    {
        try {
            $roomType = RoomType::onlyTrashed()->findOrFail($id);

            // Khôi phục các dữ liệu liên quan
            // 1. Khôi phục hình ảnh liên quan
            $roomType->roomTypeImages()->restore();

            // 2. Khôi phục các khuyến mãi liên quan
            SaleRoomType::where('room_type_id', $roomType->id)->restore();

            // Khôi phục RoomType
            $roomType->restore();

            return response()->json([
                'success' => true,
                'message' => 'Khôi phục loại phòng thành công',
                'redirect' => route('admin.room_types.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Error in RoomTypeController@restore: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            $roomType = RoomType::onlyTrashed()->findOrFail($id);

            // Kiểm tra xem loại phòng có đang được sử dụng trong đặt phòng không
            $bookingCount = Booking::where('room_type_id', $roomType->id)->count();
            if ($bookingCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa vĩnh viễn loại phòng này vì đang có ' . $bookingCount . ' đặt phòng liên quan.'
                ], 400);
            }

            // Xóa các dữ liệu liên quan
            // 1. Xóa hình ảnh liên quan
            $images = $roomType->roomTypeImages;
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                    Log::info("Deleted image before force delete: {$image->image}");
                }
                $image->forceDelete();
            }

            // 2. Xóa các khuyến mãi liên quan
            SaleRoomType::where('room_type_id', $roomType->id)->forceDelete();

            // Xóa vĩnh viễn RoomType
            $roomType->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa vĩnh viễn loại phòng thành công',
                'redirect' => route('admin.room_types.trashed')
            ]);
        } catch (\Exception $e) {
            Log::error('Error in RoomTypeController@forceDelete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
