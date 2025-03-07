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
                            <li>Phòng đã đặt</li>
                        </ul>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.rooms.booked') }}" method="GET" id="filterForm" class="row g-3 align-items-end">
                            <input type="hidden" name="room_id" value="{{ request('room_id') }}">
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
                                <label class="form-label">Số phòng</label>
                                <input type="text" name="room_number" class="form-control form-control-sm"
                                       value="{{ request('room_number') }}" placeholder="Nhập số phòng">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Khoảng thời gian</label>
                                <input type="text" name="date_range" class="form-control form-control-sm date-range-picker"
                                       value="{{ $checkIn && $checkOut ? $checkIn . ' - ' . $checkOut : '' }}" placeholder="Chọn khoảng thời gian">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm mt-3">Lọc</button>
                                <a href="{{ route('admin.rooms.booked') }}" class="btn btn-secondary btn-sm mt-3 ms-2">Xóa lọc</a>
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
                    @if ($roomType->rooms->count() > 0)
                        <div class="section-title">
                            <h4>{{ $roomType->name }} ({{ $roomType->rooms->count() }} phòng đã đặt)</h4>
                        </div>
                        <div class="row room-list" data-room-type-id="{{ $roomType->id }}">
                            @forelse ($roomType->rooms as $room)
                                <div class="col-12 mb-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Phòng {{ $room->room_number }} (ID: {{ $room->id }})</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($room->bookings->count() > 0)
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Khách hàng</th>
                                                            <th>Check in</th>
                                                            <th>Check out</th>
                                                            <th>Số khách</th>
                                                            <th>Trạng thái</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($room->bookings as $booking)
                                                            <tr>
                                                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                                                <td>{{ $booking->check_in ?? '' }}</td>
                                                                <td>{{ $booking->check_out ?? '' }}</td>
                                                                <td>{{ $booking->total_guests ?? '' }}</td>
                                                                <td>Đã đặt</td>
                                                                <td>
                                                                    <div class="action-buttons">

                                                                        <a href="{{ route('admin.rooms.show', $room->id) }}" class="btn btn-sm btn-success">
                                                                            <i class="ri-eye-line"></i>
                                                                        </a>

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-muted">Không có đặt phòng nào trong khoảng thời gian này.</p>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted text-center">Không có phòng nào đã đặt thuộc loại này.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif
                @endforeach

                @if ($roomTypes->every(function ($roomType) { return $roomType->rooms->count() == 0; }))
                    <div class="col-12">
                        <p class="text-muted text-center">Không có phòng nào đã đặt để hiển thị.</p>
                    </div>
                @endif

                <div class="section-title mt-4">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                </div>
            </div>
        </div>
    </main>

    <style>
        .hidden-room {
            display: none;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-sm {
            padding: 2px 5px;
            font-size: 12px;
        }
        .table th, .table td {
            vertical-align: middle;
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

            var dateRange = '{{ $checkIn && $checkOut ? $checkIn . " - " . $checkOut : "" }}';
            if (dateRange) {
                $('.date-range-picker').val(dateRange);
            }

            // Xử lý confirm cho form xóa
            $('.delete-form').on('submit', function(e) {
                if (!confirm($(this).data('confirm'))) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
