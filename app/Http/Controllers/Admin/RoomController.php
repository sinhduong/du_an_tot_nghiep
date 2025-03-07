<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Staff;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreroomRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateroomRequest;
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
        $status = $request->input('status');
        $roomNumber = $request->input('room_number');
        $dateRange = $request->input('date_range');

        // Phân tích date_range nếu có
        $checkIn = null;
        $checkOut = null;
        if ($dateRange && strpos($dateRange, '-') !== false) {
            [$start, $end] = explode(' - ', $dateRange);
            $checkIn = Carbon::createFromFormat('d/m/Y', trim($start))->toDateString();
            $checkOut = Carbon::createFromFormat('d/m/Y', trim($end))->toDateString();
        }

        // Xây dựng truy vấn cho room_types
        $query = RoomType::with(['rooms' => function ($query) use ($checkIn, $checkOut) {
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
                ->withCount(['bookings as booking_count' => function ($query) {
                    $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in', 'check_out']);
                }])
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc');
        }])
            ->whereNull('deleted_at')
            ->when($roomTypeId, function ($query, $roomTypeId) {
                return $query->where('id', $roomTypeId);
            })
            ->has('rooms');

        // Lấy dữ liệu
        $roomTypes = $query->get();

        // Tính trạng thái và chọn booking mới nhất
        $roomTypes->each(function ($roomType) use ($checkIn, $checkOut) {
            $roomType->rooms->each(function ($room) use ($checkIn, $checkOut) {
                // Mặc định filtered_status là available
                $room->filtered_status = 'available';

                // Kiểm tra nếu phòng có booking hợp lệ
                $hasActiveBooking = $room->bookings->contains(function ($booking) {
                    return !in_array($booking->status, ['cancelled', 'refunded']);
                });

                if ($hasActiveBooking) {
                    $room->filtered_status = 'booked';
                    // Lấy booking mới nhất để hiển thị
                    $room->latest_booking = $room->bookings->first();
                }

                // Nếu có khoảng thời gian lọc, áp dụng thêm điều kiện
                if ($checkIn && $checkOut) {
                    $checkInDate = Carbon::parse($checkIn);
                    $checkOutDate = Carbon::parse($checkOut);

                    $hasBookingInRange = $room->bookings->contains(function ($booking) use ($checkInDate, $checkOutDate) {
                        $bookingCheckIn = Carbon::parse($booking->check_in);
                        $bookingCheckOut = Carbon::parse($booking->check_out);

                        return !in_array($booking->status, ['cancelled', 'refunded']) &&
                            $bookingCheckIn->lte($checkOutDate) &&
                            $bookingCheckOut->gte($checkInDate);
                    });

                    $room->filtered_status = $hasBookingInRange ? 'booked' : 'available';

                    // Nếu có booking trong khoảng thời gian, lấy booking mới nhất trong khoảng đó
                    if ($hasBookingInRange) {
                        $room->latest_booking = $room->bookings
                            ->filter(function ($booking) use ($checkInDate, $checkOutDate) {
                                $bookingCheckIn = Carbon::parse($booking->check_in);
                                $bookingCheckOut = Carbon::parse($booking->check_out);
                                return $bookingCheckIn->lte($checkOutDate) && $bookingCheckOut->gte($checkInDate);
                            })
                            ->first();
                    }
                }

                // Debug
                Log::info("Room {$room->room_number}: booking_count = {$room->booking_count}, filtered_status = {$room->filtered_status}");
            });

            // Tính số phòng trống và đã đặt dựa trên filtered_status
            $roomType->available_rooms_count = $roomType->rooms->where('filtered_status', 'available')->count();
            $roomType->booked_rooms_count = $roomType->rooms->where('filtered_status', 'booked')->count();
        });

        // Lấy tất cả room_types để hiển thị trong dropdown lọc
        $allRoomTypes = RoomType::whereNull('deleted_at')->get();

        return view('admins.rooms.index', compact('roomTypes', 'title', 'allRoomTypes', 'checkIn', 'checkOut'));
    }

    public function bookedRooms(Request $request)
    {
        $title = 'Danh sách phòng đã đặt';

        // Lấy các tham số lọc
        $roomId = $request->input('room_id'); // Lấy room_id từ URL (nếu có)
        $dateRange = $request->input('date_range');

        // Phân tích date_range nếu có
        $checkIn = null;
        $checkOut = null;
        if ($dateRange && strpos($dateRange, '-') !== false) {
            [$start, $end] = explode(' - ', $dateRange);
            $checkIn = Carbon::createFromFormat('d/m/Y', trim($start))->toDateString();
            $checkOut = Carbon::createFromFormat('d/m/Y', trim($end))->toDateString();
        }

        // Xây dựng truy vấn cho room_types và rooms
        $query = RoomType::with(['rooms' => function ($query) use ($checkIn, $checkOut, $roomId) {
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
                ->withCount(['bookings as booking_count' => function ($query) {
                    $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in', 'check_out']);
                }])
                ->whereNull('deleted_at')
                ->when($roomId, function ($query, $roomId) {
                    return $query->where('id', $roomId); // Lọc theo room_id nếu có
                })
                ->whereHas('bookings', function ($query) use ($checkIn, $checkOut) {
                    $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in', 'check_out'])
                        ->when($checkIn, function ($query, $checkIn) {
                            return $query->whereDate('check_in', '>=', $checkIn);
                        })
                        ->when($checkOut, function ($query, $checkOut) {
                            return $query->whereDate('check_out', '<=', $checkOut);
                        });
                })
                ->orderBy('id', 'desc');
        }])
            ->whereNull('deleted_at')
            ->has('rooms');

        // Lấy dữ liệu
        $roomTypes = $query->get();

        // Lọc các phòng có booking trong khoảng thời gian (nếu có)
        $roomTypes->each(function ($roomType) use ($checkIn, $checkOut) {
            $roomType->rooms = $roomType->rooms->filter(function ($room) use ($checkIn, $checkOut) {
                if ($checkIn && $checkOut) {
                    $checkInDate = Carbon::parse($checkIn);
                    $checkOutDate = Carbon::parse($checkOut);
                    $hasBookingInRange = $room->bookings->contains(function ($booking) use ($checkInDate, $checkOutDate) {
                        $bookingCheckIn = Carbon::parse($booking->check_in);
                        $bookingCheckOut = Carbon::parse($booking->check_out);
                        return !in_array($booking->status, ['cancelled', 'refunded']) &&
                            $bookingCheckIn->lte($checkOutDate) &&
                            $bookingCheckOut->gte($checkInDate);
                    });
                    $room->filtered_status = $hasBookingInRange ? 'booked' : 'available';
                    $room->latest_booking = $hasBookingInRange ? $room->bookings->first() : null;
                    return $hasBookingInRange;
                } else {
                    $hasActiveBooking = $room->bookings->contains(function ($booking) {
                        return !in_array($booking->status, ['cancelled', 'refunded']);
                    });
                    $room->filtered_status = $hasActiveBooking ? 'booked' : 'available';
                    $room->latest_booking = $hasActiveBooking ? $room->bookings->first() : null;
                    return $hasActiveBooking;
                }
            });
        });

        // Lấy tất cả room_types để hiển thị trong dropdown lọc
        $allRoomTypes = RoomType::whereNull('deleted_at')->get();

        return view('admins.rooms.booked', compact('roomTypes', 'title', 'allRoomTypes', 'checkIn', 'checkOut'));
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
    public function show(Request $request, $id)
    {
        // Lấy checkIn và checkOut từ request
        $checkIn = $request->input('checkIn');
        $checkOut = $request->input('checkOut');

        // Xây dựng truy vấn cho phòng và bookings
        $room = Room::with([
            'roomType',
            'bookings' => function ($query) use ($checkIn, $checkOut) {
                $query->with(['user', 'guests'])
                    ->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in', 'check_out']);
                // Chỉ áp dụng lọc nếu checkIn và checkOut tồn tại
                if ($checkIn && $checkOut) {
                    $query->where(function ($q) use ($checkIn, $checkOut) {
                        $q->whereBetween('check_in', [$checkIn, $checkOut])
                          ->orWhereBetween('check_out', [$checkIn, $checkOut])
                          ->orWhere(function ($q) use ($checkIn, $checkOut) {
                              $q->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                          });
                    });
                }
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        // Xác định trạng thái đặt phòng (dựa trên booking mới nhất)
        $room->filtered_status = $room->bookings->isNotEmpty() ? 'booked' : 'available';

        $title = 'Chi tiết phòng #' . $room->room_number;

        return view('admins.rooms.show', compact('room', 'title'));
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

    // Hiển thị danh sách Phòng đã bị xóa mềm (trong thùng rác)
    public function trashed()
    {
        $room_types_id = RoomType::all();
        $staffs = Staff::all();
        $rooms = Room::onlyTrashed()->get();
        return view('admins.rooms.trashed', compact(['room_types_id', 'staffs', 'rooms']));
    }

    // Khôi phục Phòng đã xóa mềm
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

    // Xóa vĩnh viễn Phòng khỏi hệ thống
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
