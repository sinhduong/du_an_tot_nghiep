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
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li>Phòng</li>
                        </ul>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.rooms.index') }}" method="GET" id="filterForm" class="row g-3 align-items-end">
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
                            <div class="col-md-2">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">Tất cả</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Còn trống</option>
                                    <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Đã đặt</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Số phòng</label>
                                <input type="text" name="room_number" class="form-control form-control-sm"
                                       value="{{ request('room_number') }}" placeholder="Nhập số phòng">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Khoảng thời gian</label>
                                <input type="text" name="date_range" class="form-control form-control-sm date-range-picker"
                                       value="{{ request('date_range') ?: '' }}" placeholder="Chọn khoảng thời gian">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Lọc</button>
                                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm mt-3 ms-2">Xóa lọc</a>
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
                    <div class="row room-list" data-room-type-id="{{ $roomType->id }}">
                        @forelse ($roomType->rooms as $index => $room)
                            <div class="col-xl-3 col-md-6 room-item {{ $index >= 4 ? 'hidden-room' : '' }}">
                                <div class="lh-card {{ $room->filtered_status === 'booked' ? 'booked room-card' : 'room-card' }}" id="bookingtbl">
                                    <div class="lh-card-header">
                                        <h4 class="lh-card-title">{{ $room->room_number }}</h4>
                                        <div class="header-tools">
                                            @if($room->booking_count > 0)
                                                <a href="{{ route('admin.rooms.booked') }}?room_id={{ $room->id }}&date_range={{ request('date_range') }}" class="booking-count">
                                                    {{ $room->booking_count }} <i class="ri-list-check"></i>
                                                </a>
                                            @endif
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                @if(isset($checkIn) && isset($checkOut))
                                                    <a href="{{ route('admin.rooms.show', ['id' => $room->id, 'checkIn' => $checkIn, 'checkOut' => $checkOut]) }}" class="btn btn-sm btn-success">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-success">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="delete-form d-inline-block" data-confirm="Bạn có muốn xóa mềm không?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lh-card-content card-default">
                                        <div class="lh-room-details">
                                            <ul class="list">
                                                @if (isset($room->latest_booking))
                                                    <li>Check in: {{ $room->latest_booking->check_in ?? '' }}</li>
                                                    <li>Check out: {{ $room->latest_booking->check_out ?? '' }}</li>
                                                    <li>Khách hàng: {{ $room->latest_booking->user->name ?? '' }}</li>
                                                    <li>Member: {{ $room->latest_booking->total_guests ?? '' }}</li>
                                                @else
                                                    <li>Check in: </li>
                                                    <li>Check out: </li>
                                                    <li>Khách hàng: </li>
                                                    <li>Member: </li>
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
                        @if ($roomType->rooms->count() > 4)
                            <div class="col-12 text-end mt-3 room-actions">
                                <button class="btn btn-primary btn-sm show-more" data-room-type-id="{{ $roomType->id }}">Xem thêm</button>
                                <button class="btn btn-secondary btn-sm hide-less" data-room-type-id="{{ $roomType->id }}" style="display: none;">Ẩn bớt</button>
                            </div>
                        @endif
                    </div>
                @endforeach

                @if ($roomTypes->isEmpty())
                    <div class="col-12">
                        <p class="text-muted text-center">Không có loại phòng hoặc phòng nào để hiển thị.</p>
                    </div>
                @endif

                <div class="section-title mt-4">
                    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">Tạo mới</a>
                </div>
            </div>
        </div>
    </main>

    <style>
        .hidden-room {
            display: none;
        }
        .booking-count {
            cursor: pointer;
            color: #007bff;
            margin-right: 10px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .booking-count:hover {
            text-decoration: underline;
        }
        .header-tools {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-sm {
            padding: 2px 5px;
            font-size: 12px;
        }
    </style>

    <!-- Thêm thư viện date range picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo date range picker
            $('.date-range-picker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chọn',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    firstDay: 1
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                    '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                $('.date-range-picker').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            });

            var dateRange = '{{ request('date_range') }}';
            if (dateRange) {
                $('.date-range-picker').val(dateRange);
            }

            // Xử lý nút "Xem thêm" và "Ẩn bớt"
            $('.show-more').on('click', function() {
                const roomTypeId = $(this).data('room-type-id');
                const $roomList = $(`.room-list[data-room-type-id="${roomTypeId}"]`);
                const $hiddenRooms = $roomList.find('.hidden-room');
                const $showMoreBtn = $(this);
                const $hideLessBtn = $roomList.find('.hide-less');

                $hiddenRooms.fadeIn(300, function() {
                    $(this).removeClass('hidden-room');
                });

                $showMoreBtn.hide();
                $hideLessBtn.show();
            });

            $('.hide-less').on('click', function() {
                const roomTypeId = $(this).data('room-type-id');
                const $roomList = $(`.room-list[data-room-type-id="${roomTypeId}"]`);
                const $rooms = $roomList.find('.room-item');
                const $showMoreBtn = $roomList.find('.show-more');
                const $hideLessBtn = $(this);

                $rooms.each(function(index) {
                    if (index >= 4) {
                        $(this).fadeOut(0, function() {
                            $(this).addClass('hidden-room');
                        });
                    }
                });

                $showMoreBtn.show();
                $hideLessBtn.hide();
            });

            // Xử lý confirm cho form xóa
            $('.delete-form').on('submit', function(e) {
                if (!confirm($(this).data('confirm'))) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
