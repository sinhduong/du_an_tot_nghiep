<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreroomRequest;
use App\Http\Requests\UpdateroomRequest;
use App\Models\Amenity;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Thêm use này vào đầu file

class RoomController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('vi');
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $title = 'Danh sách phòng';

        // Lấy các tham số lọc
        $roomTypeId = $request->input('room_type_id');
        $status = $request->input('status');
        $roomNumber = $request->input('room_number');
        $dateRange = $request->input('date_range');
        $totalGuests = (int) $request->input('total_guests', 0); // Mặc định 0 nếu không có
        $childrenCount = (int) $request->input('children_count', 0); // Mặc định 0 nếu không có
        $roomCount = (int) $request->input('room_count', 0); // Mặc định 0 nếu không có
        $minPrice = $request->input('min_price'); // Bộ lọc giá tối thiểu
        $maxPrice = $request->input('max_price'); // Bộ lọc giá tối đa
        $amenities = $request->input('amenities', []); // Bộ lọc tiện nghi

        // Xử lý date_range
        $checkIn = null;
        $checkOut = null;
        if ($dateRange && strpos($dateRange, '-') !== false) {
            [$start, $end] = explode(' - ', $dateRange);
            $checkIn = Carbon::createFromFormat('d/m/Y', trim($start))->toDateString();
            $checkOut = Carbon::createFromFormat('d/m/Y', trim($end))->toDateString();
        } else {
            // Mặc định là ngày hôm nay
            $checkIn = Carbon::today()->toDateString();
            $checkOut = Carbon::today()->toDateString();
            $dateRange = Carbon::today()->format('d/m/Y') . ' - ' . Carbon::today()->format('d/m/Y');
        }

        // Đảm bảo ngày trả phòng hợp lệ
        if ($checkIn && $checkOut && Carbon::parse($checkIn)->gte(Carbon::parse($checkOut))) {
            $checkOut = Carbon::parse($checkIn)->addDay()->toDateString();
            $dateRange = Carbon::parse($checkIn)->format('d/m/Y') . ' - ' . Carbon::parse($checkOut)->format('d/m/Y');
        }

        // Xây dựng truy vấn cho room_types
        $query = RoomType::with(['rooms' => function ($query) use ($checkIn, $checkOut, $status, $roomNumber, $totalGuests, $childrenCount, $roomCount) {
            $query->with(['bookings' => function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in'])
                    ->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut) {
                        return $query->where(function ($q) use ($checkIn, $checkOut) {
                            $q->whereDate('check_in', '<', $checkOut)
                                ->whereDate('check_out', '>', $checkIn);
                        });
                    })
                    ->orderBy('created_at', 'desc');
            }])

                ->withCount(['bookings as booking_count' => function ($query) use ($checkIn, $checkOut) {
                    $query->whereIn('status', ['pending_confirmation', 'confirmed', 'paid', 'check_in'])
                        ->when($checkIn && $checkOut, function ($query) use ($checkIn, $checkOut) {
                            return $query->where(function ($q) use ($checkIn, $checkOut) {
                                $q->whereDate('check_in', '<', $checkOut)
                                    ->whereDate('check_out', '>', $checkIn);
                            });
                        });
                }])

                ->whereNull('deleted_at')
                ->when($roomNumber, function ($query, $roomNumber) {
                    return $query->where('room_number', 'like', "%{$roomNumber}%");
                })
                ->when($totalGuests > 0, function ($query) use ($totalGuests) {
                    return $query->whereHas('roomType', function ($q) use ($totalGuests) {
                        $q->where('max_capacity', '>=', $totalGuests);
                    });
                })
                ->when($childrenCount > 0, function ($query) use ($childrenCount) {
                    return $query->whereHas('roomType', function ($q) use ($childrenCount) {
                        $q->where('children_free_limit', '>=', $childrenCount);
                    });
                })
                ->orderBy('id', 'desc');
        }])
            ->whereNull('deleted_at')
            ->when($roomTypeId, function ($query, $roomTypeId) {
                return $query->where('id', $roomTypeId);
            })
            ->when($minPrice, function ($query, $minPrice) {
                return $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($query, $maxPrice) {
                return $query->where('price', '<=', $maxPrice);
            })
            ->when(!empty($amenities), function ($query) use ($amenities) {
                return $query->whereHas('amenities', function ($q) use ($amenities) {
                    $q->whereIn('amenities.id', $amenities);
                });
            })
            ->has('rooms');

        // Lấy dữ liệu
        $roomTypes = $query->get();

        // Tính trạng thái và chọn booking mới nhất
        $roomTypes->each(function ($roomType) use ($checkIn, $checkOut, $status, $roomCount) {
            $roomType->rooms->each(function ($room) use ($checkIn, $checkOut) {
                $room->filtered_status = 'available';

                $hasActiveBooking = $room->bookings->contains(function ($booking) {
                    $now = Carbon::now();
                    $bookingCheckOut = Carbon::parse($booking->check_out);
                    return in_array($booking->status, ['confirmed', 'paid', 'check_in'])
                        && $bookingCheckOut->gt($now)
                    ;
                });

                if ($hasActiveBooking) {
                    $room->filtered_status = 'booked';
                    $room->latest_booking = $room->bookings->first();
                }

                if ($checkIn && $checkOut) {
                    $checkInDate = Carbon::parse($checkIn);
                    $checkOutDate = Carbon::parse($checkOut);
                    $now = Carbon::now();

                    $hasBookingInRange = $room->bookings->contains(function ($booking) use ($checkInDate, $checkOutDate, $now) {
                        $bookingCheckIn = Carbon::parse($booking->check_in);
                        $bookingCheckOut = Carbon::parse($booking->check_out);
                        return in_array($booking->status, ['pending_confirmation', 'confirmed', 'paid', 'check_in']) &&
//                            $bookingCheckOut->gt($now) &&
                            $bookingCheckIn->lte($checkOutDate) &&
                            $bookingCheckOut->gte($checkInDate);
                    });

                    $room->filtered_status = $hasBookingInRange ? 'booked' : 'available';

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

                Log::info("Room {$room->room_number}: booking_count = {$room->booking_count}, filtered_status = {$room->filtered_status}");
            });

            if ($status) {
                $roomType->rooms = $roomType->rooms->filter(function ($room) use ($status) {
                    return $room->filtered_status === $status;
                });
            }

            // Lọc theo số lượng phòng yêu cầu
            if ($roomCount > 0) {
                $availableRooms = $roomType->rooms->where('filtered_status', 'available')->count();
                if ($availableRooms < $roomCount) {
                    $roomType->rooms = collect([]); // Không hiển thị loại phòng nếu không đủ số phòng yêu cầu
                }
            }

            $roomType->available_rooms_count = $roomType->rooms->where('filtered_status', 'available')->count();
            $roomType->booked_rooms_count = $roomType->rooms->where('filtered_status', 'booked')->count();
        });

        // Lấy tất cả room_types và amenities để hiển thị trong dropdown lọc
        $allRoomTypes = RoomType::whereNull('deleted_at')->get();
        $allAmenities = Amenity::all(); // Giả sử bạn có model Amenity

        return view('admins.rooms.index', compact('roomTypes', 'title', 'allRoomTypes', 'allAmenities', 'checkIn', 'checkOut', 'dateRange', 'totalGuests', 'childrenCount', 'roomCount', 'minPrice', 'maxPrice', 'amenities'));
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
        return view('admins.rooms.create', compact(['title', 'room_types_id', 'staffs_id']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreroomRequest $request)
    {
        try {
            Room::create($request->validated());
            alert()->success('Thành công', 'Phòng đã được thêm thành công!');
            return redirect()->route('admin.rooms.index');
        } catch (\Throwable $th) {
            alert()->error('Lỗi', $th->getMessage());
            return back();
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
        return view('admins.rooms.edit', compact(['title', 'room', 'room_types_id', 'staffs_id']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateroomRequest $request, Room $room)
    {
        try {
            $room->update($request->validated());
            alert()->success('Thành công', 'Phòng đã được cập nhật thành công!');
            return redirect()->route('admin.rooms.index');
        } catch (\Throwable $th) {
            alert()->error('Lỗi', 'Có lỗi xảy ra: ' . $th->getMessage());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            $room->delete();
            alert()->success('Thành công', 'Bạn đã chuyển phòng vào thùng rác!');
            return redirect()->route('admin.rooms.index');
        } catch (\Exception $e) {
            alert()->error('Lỗi', 'Lỗi khi xóa: ' . $e->getMessage());
            return back();
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
            alert()->success('Thành công', 'Phòng đã được khôi phục!');
            return redirect()->route('admin.rooms.index');
        } catch (ModelNotFoundException $e) {
            alert()->error('Lỗi', 'Phòng không tồn tại trong thùng rác!');
            return back();
        }
    }

    // Xóa vĩnh viễn Phòng khỏi hệ thống
    public function forceDelete($id)
    {
        try {
            $rooms = Room::onlyTrashed()->findOrFail($id);
            $rooms->forceDelete();
            alert()->success('Thành công', 'Phòng đã bị xóa vĩnh viễn!');
            return redirect()->route('admin.rooms.trashed');
        } catch (ModelNotFoundException $e) {
            alert()->error('Lỗi', 'Phòng không tồn tại trong thùng rác!');
            return back();
        }
    }
}
