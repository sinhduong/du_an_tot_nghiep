<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoom_typeRequest;
use App\Http\Requests\UpdateRoom_typeRequest;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Danh sách loại phòng';
        $room_types = RoomType::orderBy('is_active', 'desc')->get();
        return view('admins.room-type.index', compact('title', 'room_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm loại phòng';
        return view('admins.room-type.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Chi tiết loại phòng';
        $roomType = RoomType::with('roomTypeImages')->findOrFail($id);
        return view('admins.room-type.detail', compact('roomType', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Sửa loại phòng';
        $roomType = RoomType::with('roomTypeImages')->findOrFail($id);
        return view('admins.room-type.edit', compact('roomType', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Request $request, $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            $roomType->delete(); // Xóa mềm

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

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        $title = 'Loại phòng đã xóa';
        $room_types = RoomType::onlyTrashed()->get();
        return view('admins.room-type.trashed', compact('title', 'room_types'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(Request $request, $id)
    {
        try {
            $roomType = RoomType::onlyTrashed()->findOrFail($id);
            $roomType->restore(); // Khôi phục

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

    /**
     * Permanently delete the specified resource from storage (Force Delete).
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            $roomType = RoomType::onlyTrashed()->findOrFail($id);

            // Xóa tất cả ảnh liên quan trước khi xóa vĩnh viễn
            $images = $roomType->roomTypeImages;
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image->image)) {
                    Storage::disk('public')->delete($image->image);
                    Log::info("Deleted image before force delete: {$image->image}");
                }
                $image->delete();
            }

            $roomType->forceDelete(); // Xóa vĩnh viễn

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
