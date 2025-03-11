@extends('layouts.admin')

@section('content')
<style>
    /* Giữ nguyên CSS như trong mã gốc của bạn */
    .booking-details-container {
        background: #f5f6f8;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .booking-section {
        margin-bottom: 15px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        overflow: hidden;
    }

    .booking-section-header {
        padding: 12px 15px;
        background: #f0f1f3;
        color: #333;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e0e0e0;
    }

    .booking-section-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .booking-section-header .toggle-icon {
        font-size: 18px;
    }

    .booking-section-header .toggle-icon.open {
        transform: rotate(180deg);
    }

    .booking-section-content {
        padding: 15px;
        display: none;
    }

    .booking-section-content.open {
        display: block;
    }

    .booking-info {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .info-item {
        padding: 10px;
        background: #fafafa;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
    }

    .info-item label {
        font-weight: 500;
        color: #555;
        margin-bottom: 4px;
        display: block;
        font-size: 14px;
    }

    .info-item span {
        color: #333;
        font-size: 14px;
        word-break: break-word;
    }

    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 2px solid #ccc;
    }

    .room-image {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 4px;
        margin-top: 10px;
        border: 1px solid #ddd;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
    }

    .status-pending_confirmation { background: #f1c40f; }
    .status-confirmed { background: #2ecc71; }
    .status-paid { background: #3498db; }
    .status-check_in { background: #1abc9c; }
    .status-check_out { background: #7f8c8d; }
    .status-cancelled { background: #e74c3c; }
    .status-refunded { background: #e67e22; }

    .room-list, .payment-list {
        list-style: none;
        padding: 0;
    }

    .room-list li, .payment-list li {
        padding: 10px;
        background: #fafafa;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .amenities-list, .rules-list {
        list-style: none;
        padding-left: 10px;
        margin: 0;
    }

    .amenities-list li, .rules-list li {
        margin-bottom: 6px;
        color: #666;
        font-size: 13px;
    }

    .amenities-list li:before, .rules-list li:before {
        content: "✓";
        color: #2ecc71;
        margin-right: 6px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        background: #3498db;
        color: #fff;
        border: none;
        font-size: 14px;
    }

    .btn-secondary {
        background: #7f8c8d;
    }

    .lh-page-title {
        padding: 15px 0;
        background: transparent;
    }

    .lh-breadcrumb h5 {
        font-size: 18px;
        color: #333;
    }

    .lh-breadcrumb ul {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 8px;
        color: #666;
    }

    .lh-breadcrumb ul li a {
        color: #666;
        text-decoration: none;
    }

    .lh-breadcrumb ul li a:hover {
        color: #3498db;
    }

    .lh-tools {
        display: flex;
        gap: 10px;
    }

    .status-badge {
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        display: inline-block;
    }

    .status-pending { background: #ffc107; color: #000; }
    .status-completed { background: #28a745; color: #fff; }
    .status-failed { background: #dc3545; color: #fff; }
    .status-unknown { background: #6c757d; color: #fff; }

    .method-momo { background: #ff5e3a; color: #fff; }
    .method-vnpay { background: #007bff; color: #fff; }
    .method-cash { background: #6c757d; color: #fff; }
    .method-unknown { background: #6c757d; color: #fff; }
</style>

<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title d-flex justify-content-between align-items-center">
            <div class="lh-breadcrumb">
                <h5>{{ $title }}</h5>
                <ul>
                    <li><a href="{{ route('admin.bookings.index') }}">Trang chủ</a></li>
                    <li><a href="{{ route('admin.bookings.index') }}">Đơn đặt phòng</a></li>
                    <li>Chi tiết</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh"><i class="ri-refresh-line"></i></a>
                <div class="lh-date-range" title="Date"><span></span></div>
                <div class="filter">
                    <div class="dropdown" title="Filter">
                        <button class="btn btn-link dropdown-toggle p-0" data-bs-toggle="dropdown" aria-expanded="false">
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

        <div class="booking-details-container">
            <div class="row">
                <!-- Thông tin người đặt & người ở -->
                <div class="col-md-6">
                    <div class="booking-section">
                        <div class="booking-section-header">
                            <h5>Thông tin người đặt</h5>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="booking-section-content">
                            <div class="text-center">
                                <img src="{{ $booking->user && $booking->user->avatar ? asset('upload/avatars/' . $booking->user->avatar) : asset('assets/admin/assets/img/user/1.jpg') }}" alt="{{ $booking->user ? $booking->user->name : 'Người dùng không xác định' }}" class="user-avatar">
                                <h6 class="mt-2">{{ $booking->user->name }}</h6>
                            </div>
                            <div class="booking-info">
                                <div class="info-item">
                                    <label>Số điện thoại:</label>
                                    <span>{{ $booking->user->phone ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Email:</label>
                                    <span>{{ $booking->user->email ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Địa chỉ:</label>
                                    <span>{{ $booking->user->address ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Giới tính:</label>
                                    <span>{{ $booking->user->gender ?? 'Không xác định' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-section">
                        <div class="booking-section-header">
                            <h5>Thông tin người ở</h5>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="booking-section-content">
                            <div class="booking-info">
                                @foreach ($booking->guests as $guest)
                                <div class="info-item">
                                    <label>Tên:</label>
                                    <span>{{ $guest->name ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Ảnh cước công dân:</label>
                                    <span>{{ $guest->id_photo ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Căn cước công dân:</label>
                                    <span>{{ $guest->id_number ?? 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Ngày sinh:</label>
                                    <span>{{ $guest->birth_date ? \App\Helpers\FormatHelper::formatDate($guest->birth_date) : 'Không có' }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Giới tính:</label>
                                    <span>{{ $guest->gender ?? 'Không xác định' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin phòng & đơn đặt phòng -->
                <div class="col-md-6">
                    <div class="booking-section">
                        <div class="booking-section-header">
                            <h5>Thông tin phòng</h5>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="booking-section-content">
                            <ul class="room-list">
                                @foreach ($booking->rooms as $room)
                                    <li>
                                        <div class="booking-info">
                                            <div class="info-item">
                                                <label>Số phòng:</label><span>{{ $room->room_number }}</span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Loại phòng:</label><span>{{ $room->roomType->name ?? 'Chưa xác định' }}</span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Loại giường:</label>
                                                <span>
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
                                                    {{ $bedTypeMapping[$room->roomType->bed_type] ?? 'Chưa xác định' }}
                                                </span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Kích thước:</label><span>{{ $room->roomType->size ?? 'Chưa xác định' }}</span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Số người lớn:</label><span>{{ $room->roomType->max_capacity ?? 'Chưa xác định' }}</span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Số trẻ em:</label><span>{{ $room->roomType->children_free_limit ?? 'Chưa xác định' }}</span><br>
                                            </div>
                                            <div class="info-item">
                                                <label>Giá:</label><span>{{ \App\Helpers\FormatHelper::formatPrice($room->roomType->price ?? 0) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <label>Mô tả:</label><span>{{ $room->roomType->description ?? 'Chưa xác định' }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <div class="booking-info">
                                        <div class="info-item">
                                            <label>Tiện nghi:</label>
                                            <ul class="amenities-list">
                                                @forelse ($room->roomType->amenities as $amenity)
                                                    <li>{{ $amenity->name }}</li>
                                                @empty
                                                    <li>Không có tiện nghi.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="info-item">
                                            <label>Quy tắc & quy định:</label>
                                            <ul class="amenities-list">
                                                @forelse ($room->roomType->rulesAndRegulations as $rule)
                                                    <li>{{ $rule->name }}</li>
                                                @empty
                                                    <li>Không có Quy tắc & quy định.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="info-item">
                                            <label>Dịch vụ khách sạn:</label>
                                            <ul class="amenities-list">
                                                @forelse ($room->roomType->services as $service)
                                                    <li>{{ $service->name }}</li>
                                                @empty
                                                    <li>Không có dịch vụ.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Dịch vụ bổ sung đã chọn (ServicePlus) -->
                    <div class="booking-section">
                        <div class="booking-section-header">
                            <h5>Dịch vụ bổ sung</h5>
                            <span class="toggle-icon">▼</span>
                            <!-- Nút thêm dịch vụ bổ sung -->
                        </div>
                        <div class="booking-section-content">
                            <button class="btn btn-primary ms-2 text-end" data-bs-toggle="modal" data-bs-target="#addServicePlusModal">
                                Thêm dịch vụ bổ sung
                            </button>
                            @if ($booking->servicePlus->isEmpty())
                                <p>Chưa có dịch vụ bổ sung nào được chọn.</p>
                            @else
                                <table class="table table-striped" id="servicePlusTable">
                                    <thead>
                                        <tr>
                                            <th>Tên dịch vụ</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking->servicePlus as $servicePlus)
                                        <tr>
                                            <td>{{ $servicePlus->name }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatPrice($servicePlus->price) }}</td>
                                            <td>{{ $servicePlus->pivot->quantity }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-service-plus" data-service-plus-id="{{ $servicePlus->id }}" data-quantity="{{ $servicePlus->pivot->quantity }}">Sửa</button>
                                                <button class="btn btn-sm btn-danger remove-service-plus" data-service-plus-id="{{ $servicePlus->id }}">Xóa</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>

                    <div class="booking-section">
                        <div class="booking-section-header">
                            <h5>Thông tin đơn đặt phòng</h5>
                            <span class="toggle-icon">▼</span>
                        </div>
                        <div class="booking-section-content">
                            <div class="booking-info">
                                <div class="info-item">
                                    <label>Mã đặt phòng:</label><span>{{ $booking->booking_code }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Ngày check-in:</label><span>{{ \App\Helpers\FormatHelper::formatDate($booking->check_in) }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Ngày check-out:</label><span>{{ \App\Helpers\FormatHelper::formatDate($booking->check_out) }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Tổng tiền:</label><span>{{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Số người:</label><span>Người lớn: {{ $booking->total_guests }} | Trẻ em: {{ $booking->children_count }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Trạng thái:</label>
                                    <span class="status-badge {{ 'status-' . $booking->status }}">
                                        @php
                                            $statusMapping = [
                                                'pending_confirmation' => 'Chưa xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'paid' => 'Đã thanh toán',
                                                'check_in' => 'Đã check in',
                                                'check_out' => 'Đã checkout',
                                                'cancelled' => 'Đã hủy',
                                                'refunded' => 'Đã hoàn tiền',
                                            ];
                                        @endphp
                                        {{ $statusMapping[$booking->status] ?? 'Không xác định' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- Thông tin thanh toán -->
            <div class="booking-section">
                <div class="booking-section-header">
                    <h5>Thông tin thanh toán</h5>
                    <span class="toggle-icon">▼</span>
                </div>
                <div class="booking-section-content">
                    <div class="row">
                        <!-- Cột 1: Thanh toán -->
                        <div class="col-md-6 text-center">
                            <h6>Thanh toán</h6>
                            <div class="booking-info">
                                @forelse ($booking->payments as $payment)
                                    <div class="info-item">
                                        <label>Phương thức:</label>
                                        <span class="status-badge {{ 'method-' . strtolower($payment->method ?? 'unknown') }}">
                                            @php
                                                $methodMapping = [
                                                    'momo' => 'MOMO',
                                                    'vnpay' => 'VNPAY',
                                                    'cash' => 'Tiền mặt',
                                                ];
                                            @endphp
                                            {{ $methodMapping[strtolower($payment->method ?? 'unknown')] ?? 'Không xác định' }}
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <label>Số tiền:</label>
                                        <span>{{ \App\Helpers\FormatHelper::formatPrice($payment->amount ?? 0) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Ngày thanh toán:</label>
                                        <span>{{ \App\Helpers\FormatHelper::formatDate($payment->created_at) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <label>Trạng thái:</label>
                                        <span class="status-badge {{ 'status-' . strtolower($payment->status ?? 'unknown') }}">
                                            @php
                                                $statusMapping = [
                                                    'pending' => 'Đang chờ',
                                                    'completed' => 'Đã hoàn thành',
                                                    'failed' => 'Không hoàn thành',
                                                ];
                                            @endphp
                                            {{ $statusMapping[strtolower($payment->status ?? 'unknown')] ?? 'Không xác định' }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="info-item">
                                        <span>Chưa có thông tin thanh toán.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                <!-- Cột 2: Dịch vụ phát sinh -->
                <div class="col-md-6 text-center">
                    <h6>Dịch vụ phát sinh</h6>
                    <div class="booking-info">
                        @if ($booking->servicePlus->isEmpty())
                            <div class="info-item">
                                <span>Chưa có dịch vụ phát sinh.</span>
                            </div>
                        @else
                            @php
                                $totalServicePlusPrice = $booking->servicePlus->sum(function ($service) {
                                    return $service->price * $service->pivot->quantity;
                                });
                            @endphp
                            <div class="info-item">
                                <label>Danh sách dịch vụ phát sinh:</label>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tên dịch vụ</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking->servicePlus as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ \App\Helpers\FormatHelper::formatPrice($service->price) }}</td>
                                                <td>{{ $service->pivot->quantity }}</td>
                                                <td>{{ \App\Helpers\FormatHelper::formatPrice($service->price * $service->pivot->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                            <td><strong>{{ \App\Helpers\FormatHelper::formatPrice($totalServicePlusPrice) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="info-item">
                                <label>Trạng thái:</label>
                                <select class="form-control service-plus-status" data-booking-id="{{ $booking->id }}" {{ $booking->service_plus_status === 'paid' ? 'disabled' : '' }}>
                                    <option value="not_yet_paid" {{ $booking->service_plus_status === 'not_yet_paid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="paid" {{ $booking->service_plus_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                    </div>
                </div>
            </div>
            </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm dịch vụ bổ sung (ServicePlus) -->
<div class="modal fade" id="addServicePlusModal" tabindex="-1" aria-labelledby="addServicePlusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicePlusModalLabel">Chọn dịch vụ bổ sung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addServicePlusForm">
                    @csrf
                    <input type="hidden" name="action" value="addServicePlus">
                    <div class="form-group">
                        <label for="service_plus_id">Dịch vụ bổ sung:</label>
                        <select name="service_plus_id" class="form-control" required>
                            <option value="">Chọn dịch vụ</option>
                            @foreach ($availableServicePlus as $servicePlus)
                                <option value="{{ $servicePlus->id }}">{{ $servicePlus->name }} ({{ \App\Helpers\FormatHelper::formatPrice($servicePlus->price) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Thêm</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa số lượng dịch vụ bổ sung -->
<div class="modal fade" id="editServicePlusModal" tabindex="-1" aria-labelledby="editServicePlusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServicePlusModalLabel">Sửa số lượng dịch vụ bổ sung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editServicePlusForm">
                    @csrf
                    <input type="hidden" name="action" value="updateServicePlus">
                    <input type="hidden" name="service_plus_id" id="editServicePlusId">
                    <div class="form-group">
                        <label for="editQuantity">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" id="editQuantity" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật trạng thái -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Cập nhật trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái mới</label>
                        <select name="status" id="status" class="form-control" required>
                            @php
                                $statusOptions = [
                                    'pending_confirmation' => 'Chưa xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'paid' => 'Đã thanh toán',
                                    'check_in' => 'Đã check in',
                                    'check_out' => 'Đã checkout',
                                    'cancelled' => 'Đã hủy',
                                    'refunded' => 'Đã hoàn tiền',
                                ];
                            @endphp
                            @foreach ($statusOptions as $key => $value)
                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sectionHeaders = document.querySelectorAll('.booking-section-header');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Toggle sections
    sectionHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon');

            if (content.classList.contains('open')) {
                content.classList.remove('open');
                toggleIcon.classList.remove('open');
                toggleIcon.textContent = '▼';
            } else {
                content.classList.add('open');
                toggleIcon.classList.add('open');
                toggleIcon.textContent = '▲';
            }
        });
    });

    // Thêm dịch vụ bổ sung
    document.getElementById('addServicePlusForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Kiểm tra dữ liệu trước khi gửi
        const servicePlusId = formData.get('service_plus_id');
        const quantity = formData.get('quantity');
        const action = formData.get('action');

        if (!servicePlusId || !quantity || !action) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng điền đầy đủ thông tin dịch vụ và số lượng!',
                confirmButtonText: 'OK'
            });
            return;
        }

        console.log('Sending AJAX request to:', `{{ route('admin.bookings.service_plus.update', $booking->id) }}`);
        console.log('Form data:', { service_plus_id: servicePlusId, quantity: quantity, action: action });

        fetch(`{{ route('admin.bookings.service_plus.update', $booking->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Cập nhật bảng dịch vụ bổ sung
                    const tableBody = document.querySelector('#servicePlusTable tbody');
                    const noServiceMsg = document.querySelector('.booking-section-content p');
                    if (noServiceMsg) noServiceMsg.remove();

                    if (!tableBody) {
                        const table = `
                            <table class="table table-striped" id="servicePlusTable">
                                <thead>
                                    <tr>
                                        <th>Tên dịch vụ</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`;
                        document.querySelector('.booking-section-content').insertAdjacentHTML('beforeend', table);
                    }

                    const newRow = `
                        <tr>
                            <td>${data.data.name}</td>
                            <td>${formatPrice(data.data.price)}</td>
                            <td>${data.data.quantity}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-service-plus" data-service-plus-id="${data.data.id}" data-quantity="${data.data.quantity}">Sửa</button>
                                <button class="btn btn-sm btn-danger remove-service-plus" data-service-plus-id="${data.data.id}">Xóa</button>
                            </td>
                        </tr>`;
                    document.querySelector('#servicePlusTable tbody').insertAdjacentHTML('beforeend', newRow);
                    bootstrap.Modal.getInstance(document.getElementById('addServicePlusModal')).hide();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Có lỗi xảy ra khi thêm dịch vụ bổ sung: ' + error.message,
                confirmButtonText: 'OK'
            });
        });
    });

    // Sửa số lượng dịch vụ bổ sung
    document.querySelectorAll('.edit-service-plus').forEach(button => {
        button.addEventListener('click', function () {
            const servicePlusId = this.getAttribute('data-service-plus-id');
            const quantity = this.getAttribute('data-quantity');
            document.getElementById('editServicePlusId').value = servicePlusId;
            document.getElementById('editQuantity').value = quantity;
            new bootstrap.Modal(document.getElementById('editServicePlusModal')).show();
        });
    });

    document.getElementById('editServicePlusForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`{{ route('admin.bookings.service_plus.update', $booking->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    const row = document.querySelector(`button[data-service-plus-id="${data.data.id}"]`).closest('tr');
                    row.querySelector('td:nth-child(3)').textContent = data.data.quantity;
                    bootstrap.Modal.getInstance(document.getElementById('editServicePlusModal')).hide();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Có lỗi xảy ra khi cập nhật dịch vụ bổ sung.',
                confirmButtonText: 'OK'
            });
        });
    });

    // Xóa dịch vụ bổ sung
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-service-plus')) {
            if (confirm('Bạn có muốn xóa dịch vụ bổ sung này không?')) {
                const servicePlusId = e.target.getAttribute('data-service-plus-id');
                const formData = new FormData();
                formData.append('action', 'removeServicePlus');
                formData.append('service_plus_id', servicePlusId);

                fetch(`{{ route('admin.bookings.service_plus.update', $booking->id) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            e.target.closest('tr').remove();
                            if (!document.querySelector('#servicePlusTable tbody tr')) {
                                document.querySelector('#servicePlusTable').outerHTML = '<p>Chưa có dịch vụ bổ sung nào được chọn.</p>';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi xóa dịch vụ bổ sung.',
                        confirmButtonText: 'OK'
                    });
                });
            }
        }
    });

    // Cập nhật trạng thái dịch vụ phát sinh
    document.querySelectorAll('.service-plus-status').forEach(select => {
        select.addEventListener('change', function () {
            const bookingId = this.getAttribute('data-booking-id');
            const newStatus = this.value;

            fetch(`{{ route('admin.bookings.service_plus.update', $booking->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'updateServicePlusStatus',
                    service_plus_status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (newStatus === 'paid') {
                            select.disabled = true;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        select.value = '{{ $booking->service_plus_status }}';
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi cập nhật trạng thái.',
                    confirmButtonText: 'OK'
                });
            });
        });
    });

    // Hàm định dạng giá
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
    }
});
</script>

<!-- SweetAlert2 và Bootstrap -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
@endsection
