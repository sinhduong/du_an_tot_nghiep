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
                <div class="lh-page-title">
                    <div class="lh-breadcrumb">
                        <h5 class="text-primary fw-bold">{{ $title }}</h5>
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li>Chi tiết phòng</li>
                        </ul>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Thông tin phòng -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Thông tin phòng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border p-3 rounded bg-light">
                                    <h6 class="text-primary mb-2">Thông tin phòng</h6>
                                    <p class="mb-1"><strong class="text-dark">Số phòng:</strong> <span class="text-secondary">{{ $room->room_number ?? 'N/A' }}</span></p>
                                    <p class="mb-1"><strong class="text-dark">Người quản lý:</strong> <span class="text-secondary">{{ $room->manager ?? 'N/A' }}</span></p>
                                    <p class="mb-0"><strong class="text-dark">Trạng thái đặt phòng:</strong> <span class="text-success">{{ $room->filtered_status == 'booked' ? 'Đã đặt' : 'Còn trống' }}</span></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border p-3 rounded bg-light">
                                    <h6 class="text-primary mb-2">Thông tin người đặt </h6>
                                    @if ($room->bookings->isNotEmpty())
                                        @php $latestBooking = $room->bookings->first(); @endphp
                                        <p class="mb-1"><strong class="text-dark">Ảnh:</strong> <span class="text-secondary">{{ $latestBooking->user->avatar ?? 'N/A' }}</span></p>
                                        <p class="mb-1"><strong class="text-dark">Tên:</strong> <span class="text-secondary">{{ $latestBooking->user->name ?? 'N/A' }}</span></p>
                                        <p class="mb-1"><strong class="text-dark">SĐT:</strong> <span class="text-secondary">{{ $latestBooking->user->phone ?? 'N/A' }}</span></p>
                                        <p class="mb-1"><strong class="text-dark">Email:</strong> <span class="text-secondary">{{ $latestBooking->user->email ?? 'N/A' }}</span></p>
                                        <p class="mb-1"><strong class="text-dark">Địa chỉ:</strong> <span class="text-secondary">{{ $latestBooking->user->address ?? 'N/A' }}</span></p>
                                        <p class="mb-1"><strong class="text-dark">Giới tính:</strong> <span class="text-secondary">{{ $latestBooking->user->gender ?? 'N/A' }}</span></p>
                                    @else
                                        <p class="text-muted">Chưa có thông tin người đặt.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border p-3 rounded bg-light">
                                    <h6 class="text-primary mb-2">Thông tin người ở</h6>
                                    @if ($room->bookings->isNotEmpty())
                                        @php $latestBooking = $room->bookings->first(); @endphp
                                        @if ($latestBooking->guests->isNotEmpty())
                                            @php $mainGuest = $latestBooking->guests->first(); @endphp
                                            <p class="mb-1"><strong class="text-dark">Tên:</strong> <span class="text-secondary">{{ $mainGuest->name ?? 'N/A' }}</span></p>
                                            <p class="mb-1"><strong class="text-dark">Ảnh căn cước:</strong> <span class="text-secondary">{{ $mainGuest->id_photo ?? 'N/A' }}</span></p>                                            <p class="mb-1"><strong class="text-dark">Căn cước:</strong> <span class="text-secondary">{{ $mainGuest->id_number ?? 'N/A' }}</span></p>
                                            <p class="mb-1"><strong class="text-dark">Ngày sinh:</strong> <span class="text-secondary">{{ $mainGuest->birth_date ?? 'N/A' }}</span></p>
                                            <p class="mb-1"><strong class="text-dark">Giới tính:</strong> <span class="text-secondary">{{ $mainGuest->gender ?? 'N/A' }}</span></p>
                                            <p class="mb-1"><strong class="text-dark">SĐT:</strong> <span class="text-secondary">{{ $mainGuest->phone ?? 'N/A' }}</span></p>
                                            <p class="mb-1"><strong class="text-dark">Email:</strong> <span class="text-secondary">{{ $mainGuest->email ?? 'N/A' }}</span></p>
                                            <p class="mb-0"><strong class="text-dark">Quan hệ:</strong> <span class="text-secondary">{{ $mainGuest->relationship ?? 'N/A' }}</span></p>
                                        @else
                                            <p class="text-muted">Chưa có thông tin người ở.</p>
                                        @endif
                                    @else
                                        <p class="text-muted">Chưa có thông tin người ở.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="border p-3 rounded bg-light">
                                    <h6 class="text-primary mb-2">Thông tin loại phòng</h6>
                                    <p class="mb-1"><strong class="text-dark">Tên loại phòng:</strong> <span class="text-secondary">{{ $room->roomType->name ?? 'N/A' }}</span></p>
                                    <p class="mb-1"><strong class="text-dark">Giá:</strong> <span class="text-secondary">{{ \App\Helpers\FormatHelper::formatPrice($room->roomType->price) }} VNĐ</span></p>
                                    <p class="mb-1"><strong class="text-dark">Số người tối đa:</strong> <span class="text-secondary">{{ $room->roomType->max_capacity }} người</span></p>
                                    <p class="mb-1"><strong class="text-dark">Loại giường:</strong>
                                        @php
                                            $bedTypeMapping = [
                                                'single' => 'Giường đơn',
                                                'double' => 'Giường đôi',
                                                'queen' => 'Giường Queen',
                                                'king' => 'Giường King',
                                                'bunk' => 'Giường tầng',
                                                'sofa' => 'Giường sofa',
                                            ];
                                        @endphp
                                        <span class="text-secondary">{{ $bedTypeMapping[$room->roomType->bed_type] ?? 'Không xác định' }}</span>
                                    </p>
                                    <p class="mb-1"><strong class="text-dark">Kích thước:</strong> <span class="text-secondary">{{ $room->roomType->size }} m²</span></p>
                                    <p class="mb-1"><strong class="text-dark">Số người lớn:</strong> <span class="text-secondary">{{ $room->roomType->max_capacity }} người</span></p>
                                    <p class="mb-1"><strong class="text-dark">Số trẻ em miễn phí:</strong> <span class="text-secondary">{{ $room->roomType->children_free_limit }} trẻ</span></p>
                                    <p class="mb-1"><strong class="text-dark">Trạng thái:</strong> <span class="text-success">{{ $room->roomType->is_active ? 'Hoạt động' : 'Không hoạt động' }}</span></p>
                                    <p class="mb-0"><strong class="text-dark">Mô tả:</strong> <span class="text-secondary">{{ $room->roomType->description ?? 'Không có mô tả' }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi, Dịch vụ, Quy tắc -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Tiện nghi</h5>
                            </div>
                            <div class="card-body">
                                @if ($room->roomType->amenities)
                                    <ul class="list-group list-group-flush">
                                        @foreach ($room->roomType->amenities as $amenity)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $amenity->name }}
                                                <span class="badge bg-success rounded-pill">Có</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">Không có tiện nghi nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Dịch vụ</h5>
                            </div>
                            <div class="card-body">
                                @if ($room->roomType->services)
                                    <ul class="list-group list-group-flush">
                                        @foreach ($room->roomType->services as $service)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $service->name }}
                                                <span class="badge bg-success rounded-pill">Có</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">Không có dịch vụ nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Quy tắc & Quy định</h5>
                            </div>
                            <div class="card-body">
                                @if ($room->roomType->rulesAndRegulations)
                                    <ul class="list-group list-group-flush">
                                        @foreach ($room->roomType->rulesAndRegulations as $rule)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $rule->name }}
                                                <span class="badge bg-info rounded-pill">Áp dụng</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center">Không có quy tắc nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách đặt phòng -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Danh sách đặt phòng</h5>
                    </div>
                    <div class="card-body">
                        @if ($room->bookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Người đặt</th>
                                            <th>Email</th>
                                            <th>Số điện thoại</th>
                                            <th>Người ở (Số khách)</th>
                                            <th>Check in</th>
                                            <th>Check out</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($room->bookings as $booking)
                                            <tr class="align-middle">
                                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                                <td>{{ $booking->user->email ?? 'N/A' }}</td>
                                                <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                                                <td>{{ $booking->total_guests ?? 'N/A' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}</td>
                                                <td>
                                                    @php
                                                        $statusTranslations = [
                                                            'pending_confirmation' => 'Đang chờ xác nhận',
                                                            'confirmed' => 'Đã xác nhận',
                                                            'paid' => 'Đã thanh toán',
                                                            'check_in' => 'Đã nhận phòng',
                                                            'check_out' => 'Đã trả phòng',
                                                            'cancelled' => 'Đã hủy',
                                                            'refunded' => 'Đã hoàn tiền',
                                                        ];
                                                    @endphp
                                                    <span class="badge {{ in_array($booking->status, ['confirmed', 'paid', 'check_in', 'check_out', 'refunded']) ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $statusTranslations[$booking->status] ?? $booking->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-3">Không có đặt phòng nào trong khoảng thời gian đã chọn.</p>
                        @endif
                    </div>
                </div>

                <div class="section-title mt-4">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                </div>
            </div>
        </div>
    </main>

    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }
        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.2s;
        }
        .badge {
            font-size: 0.9em;
            padding: 0.25em 0.5em;
        }
    </style>
@endsection
