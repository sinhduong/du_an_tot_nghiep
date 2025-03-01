<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreroomRequest;
use App\Http\Requests\UpdateroomRequest;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Danh sách phòng';

        // Lấy các tham số lọc
        $roomTypeId = $request->input('room_type_id');
        $status = $request->input('status'); // Không mặc định, để người dùng chọn
        $roomNumber = $request->input('room_number');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');

        // Xây dựng truy vấn cho room_types
        $query = RoomType::with(['rooms' => function ($query) use ($status, $roomNumber, $checkIn, $checkOut) {
            $query->with(['bookings' => function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in', 'check_out'])
                    ->when($checkIn, function ($query, $checkIn) {
                        return $query->whereDate('check_in', '>=', $checkIn);
                    })
                    ->when($checkOut, function ($query, $checkOut) {
                        return $query->whereDate('check_out', '<=', $checkOut);
                    })
                    ->orderBy('created_at', 'desc');
            }])
            ->whereNull('deleted_at')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($roomNumber, function ($query, $roomNumber) {
                return $query->where('room_number', 'like', '%' . $roomNumber . '%');
            })
            ->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut, $status) {
                if ($status === 'available') {
                    return $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                        $q->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in'])
                            ->where(function ($q) use ($checkIn, $checkOut) {
                                $checkInDate = Carbon::parse($checkIn);
                                $checkOutDate = Carbon::parse($checkOut);
                                $q->where(function ($q) use ($checkInDate, $checkOutDate) {
                                    $q->where('check_in', '>=', $checkOutDate)
                                        ->orWhere('check_out', '<=', $checkInDate);
                                })->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                                    $q->where('check_in', '<', $checkInDate)
                                        ->where('check_out', '>', $checkOutDate);
                                });
                            });
                    });
                } elseif ($status === 'booked') {
                    return $query->whereHas('bookings', function ($q) use ($checkIn, $checkOut) {
                        $q->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in'])
                            ->where(function ($q) use ($checkIn, $checkOut) {
                                $checkInDate = Carbon::parse($checkIn);
                                $checkOutDate = Carbon::parse($checkOut);
                                $q->where(function ($q) use ($checkInDate, $checkOutDate) {
                                    $q->whereBetween('check_in', [$checkInDate, $checkOutDate])
                                        ->orWhereBetween('check_out', [$checkInDate, $checkOutDate]);
                                })->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                                    $q->where('check_in', '<', $checkInDate)
                                        ->where('check_out', '>', $checkOutDate);
                                });
                            });
                    });
                }
            })
            ->orderBy('id', 'desc');
        }])
        ->whereNull('deleted_at')
        ->when($roomTypeId, function ($query, $roomTypeId) {
            return $query->where('id', $roomTypeId);
        })
        ->has('rooms');

        // Lấy dữ liệu
        $roomTypes = $query->get();

        // Tính số phòng còn trống hoặc đã đặt cho mỗi room_type, dựa trên trạng thái và booking trong khoảng thời gian
        $roomTypes->each(function ($roomType) use ($checkIn, $checkOut) {
            if ($checkIn && $checkOut) {
                $checkInDate = Carbon::parse($checkIn);
                $checkOutDate = Carbon::parse($checkOut);

                $roomType->available_rooms_count = $roomType->rooms
                    ->filter(function ($room) use ($checkInDate, $checkOutDate) {
                        return !$room->bookings->contains(function ($booking) use ($checkInDate, $checkOutDate) {
                            $bookingCheckIn = Carbon::parse($booking->check_in);
                            $bookingCheckOut = Carbon::parse($booking->check_out);

                            // Kiểm tra nếu booking không bị hủy hoặc hoàn tiền
                            if (in_array($booking->status, ['cancelled', 'refunded'])) {
                                return false;
                            }

                            // Kiểm tra chồng lấn
                            return !($bookingCheckOut->lte($checkInDate) || $bookingCheckIn->gte($checkOutDate));
                        });
                    })->count();

                $roomType->booked_rooms_count = $roomType->rooms
                    ->filter(function ($room) use ($checkInDate, $checkOutDate) {
                        return $room->bookings->contains(function ($booking) use ($checkInDate, $checkOutDate) {
                            $bookingCheckIn = Carbon::parse($booking->check_in);
                            $bookingCheckOut = Carbon::parse($booking->check_out);

                            // Kiểm tra nếu booking không bị hủy hoặc hoàn tiền
                            if (in_array($booking->status, ['cancelled', 'refunded'])) {
                                return false;
                            }

                            // Kiểm tra chồng lấn
                            return !($bookingCheckOut->lte($checkInDate) || $bookingCheckIn->gte($checkOutDate));
                        });
                    })->count();
            } else {
                $roomType->available_rooms_count = $roomType->rooms->where('status', 'available')->count();
                $roomType->booked_rooms_count = $roomType->rooms->where('status', 'booked')->count();
            }
        });

        // Lấy tất cả room_types để hiển thị trong dropdown lọc
        $allRoomTypes = RoomType::whereNull('deleted_at')->get();

        return view('admins.rooms.index', compact('roomTypes', 'title', 'allRoomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm phòng';
        $room_types_id = RoomType::all(); //lấy tất cả loại phòng
        $staffs_id = Staff::all(); //lấy tất cả loại phòng
        return  view('admins.rooms.create', compact(['title', 'room_types_id', 'staffs_id']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreroomRequest $request)
    {
        try {

            Room::create($request->validated());

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Phòng đã được thêm thành công!');
        } catch (\Throwable $th) {

            return back()
                ->with('success', true)
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(room $rooms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $title = 'Sửa thông tin phòng';
        $room_types_id = RoomType::all(); //lấy tất cả loại phòng
        $staffs_id = Staff::all(); //lấy tất cả loại phòng
        return  view('admins.rooms.edit', compact(['title', 'room', 'room_types_id', 'staffs_id']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateroomRequest $request, Room $room)
    {
        try {
            $room->update($request->validated());
            return back()->with('success', 'Phòng đã được cập nhật thành công!');
        } catch (\Throwable $th) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Bạn đã chuyển phòng vào thùng rác!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi xóa: ' . $e->getMessage());
        }
    }


    //  Hiển thị danh sách Phòng đã bị xóa mềm (trong thùng rác)
    public function trashed()
    {
        $room_types_id = RoomType::all();
        $staffs = Staff::all();
        $rooms = Room::onlyTrashed()->get();
        return view('admins.rooms.trashed', compact(['room_types_id', 'staffs', 'rooms']));
    }

    //  Khôi phục Phòng đã xóa mềm
    public function restore($id)
    {
        try {
            $rooms = Room::onlyTrashed()->findOrFail($id);
            $rooms->restore();

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Phòng đã được khôi phục!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Phòng không tồn tại trong thùng rác!');
        }
    }

    //  Xóa vĩnh viễn Phòng khỏi hệ thống
    public function forceDelete($id)
    {
        try {
            $rooms = Room::onlyTrashed()->findOrFail($id);
            $rooms->forceDelete();

            return redirect()
                ->route('admin.rooms.trashed')
                ->with('success', 'Phòng đã bị xóa vĩnh viễn!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Phòng không tồn tại trong thùng rác!');
        }
    }
}
