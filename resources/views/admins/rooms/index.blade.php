@extends('layouts.admin')
@section('content')
    <main class="wrapper sb-default">
        <!-- Loader -->
        <div class="lh-loader">
            <span class="loader"></span>
        </div>
        <div class="lh-sidebar-overlay"></div>
        <!-- Notify sidebar -->
        <div class="lh-notify-bar-overlay"></div>
        <!-- main content -->
        <div class="lh-main-content">
            <div class="container-fluid">
                <!-- Page title & breadcrumb -->
                <div class="lh-page-title d-flex justify-content-between align-items-center">
                    <div class="lh-breadcrumb">
                        <h5>{{ $title }}</h5>
                        <ul>
                            <li><a href="index.html">Home</a></li>
                            <li>Rooms</li>
                        </ul>
                    </div>
                    <div class="lh-tools d-flex gap-2">
                        <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                        <div id="pagedate">
                            <div class="lh-date-range" title="Date">
                                <span></span>
                            </div>
                        </div>
                        <div class="filter">
                            <div class="dropdown" title="Filter">
                                <button class="btn btn-link dropdown-toggle p-0" data-bs-toggle="dropdown">
                                    <i class="ri-sound-module-line"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Booking</a></li>
                                    <li><a class="dropdown-item" href="#">Revenue</a></li>
                                    <li><a class="dropdown-item" href="#">Expence</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.rooms.index') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Loại phòng</label>
                                <select name="room_type_id" class="form-control form-control-sm">
                                    <option value="">Tất cả</option>
                                    @foreach ($allRoomTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('room_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">Tất cả</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Còn trống</option>
                                    <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Đã đặt</option>
                                    {{-- <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option> --}}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số phòng</label>
                                <input type="text" name="room_number" class="form-control form-control-sm" value="{{ request('room_number') }}" placeholder="Nhập số phòng">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ngày check-in</label>
                                <input type="date" name="check_in" class="form-control form-control-sm" value="{{ request('check_in') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ngày check-out</label>
                                <input type="date" name="check_out" class="form-control form-control-sm" value="{{ request('check_out') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm mt-4">Lọc</button>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm mt-4 ms-2">Xóa lọc</a>
                            </div>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @foreach ($roomTypes as $roomType)
                    <div class="section-title">
                        <h4>{{ $roomType->name }} ({{ $roomType->rooms->count() }} phòng - {{ $roomType->available_rooms_count }} trống{{ $roomType->booked_rooms_count ? ', ' . $roomType->booked_rooms_count . ' đã đặt' : '' }})</h4>
                    </div>
                    <div class="row">
                        @forelse ($roomType->rooms as $room)
                            <div class="col-xl-3 col-md-6">
                                <div class="lh-card {{ $room->status === 'booked' ? 'booked room-card' : 'room-card' }}" id="bookingtbl">
                                    <div class="lh-card-header">
                                        <h4 class="lh-card-title">{{ $room->room_number }}</h4>
                                        <div class="header-tools">
                                            <div class="dropdown" data-bs-toggle="tooltip" data-bs-original-title="Settings">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.rooms.edit', $room->id) }}">
                                                            <i class="ri-edit-line"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.rooms.destroy', $room->id) }}"
                                                            method="POST" onsubmit="return confirm('Bạn có muốn xóa mềm không?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-line"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lh-card-content card-default">
                                        <div class="lh-room-details">
                                            <ul class="list">
                                                @if ($room->bookings->isNotEmpty())
                                                    @foreach ($room->bookings as $booking)
                                                        <li>Check in : {{ $booking->check_in ?? '' }}</li>
                                                        <li>Check out : {{ $booking->check_out ?? '' }}</li>
                                                        <li>Name : {{ $booking->user->name ?? '' }}</li>
                                                        <li>Member : {{ $booking->total_guests ?? '' }}</li>
                                                    @endforeach
                                                @else
                                                    <li>Check in : </li>
                                                    <li>Check out : </li>
                                                    <li>Name : </li>
                                                    <li>Member : </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted text-center">Không có phòng nào thuộc loại này.</p>
                            </div>
                        @endforelse
                    </div>
                @endforeach

                @if ($roomTypes->isEmpty())
                    <div class="col-12">
                        <p class="text-muted text-center">Không có loại phòng hoặc phòng nào để hiển thị.</p>
                    </div>
                @endif

                <!-- Nút tạo mới -->
                <div class="section-title mt-4">
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">Tạo mới</a>
                </div>
            </div>
        </div>
    </main>
@endsection
