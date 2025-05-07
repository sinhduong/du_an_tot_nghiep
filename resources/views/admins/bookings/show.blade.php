@extends('layouts.admin')

<style>
    /* CSS tổng thể */
    .lh-main-content {
        background: #f1f3f5;
        min-height: 100vh;
        padding: 20px 0;
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .lh-page-title {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .lh-breadcrumb h5 {
        font-size: 1.25rem;
        color: #0d6efd;
        font-weight: 600;
    }

    .lh-breadcrumb ul {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 10px;
        font-size: 0.9rem;
    }

    .lh-breadcrumb ul li a {
        color: #6c757d;
        text-decoration: none;
    }

    .lh-breadcrumb ul li a:hover {
        color: #0d6efd;
    }

    /* Booking Section */
    .booking-section {
        margin-bottom: 20px;
        background: #fff;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .booking-section-header {
        padding: 12px 15px;
        background: #f8f9fa;
        color: #333;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booking-section-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .booking-section-header .toggle-icon {
        font-size: 1.1rem;
        transition: transform 0.3s;
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

    /* Info Items */
    .booking-info {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .info-item {
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
    }

    .info-item label {
        font-weight: 500;
        color: #555;
        margin-bottom: 4px;
        display: block;
        font-size: 0.875rem;
    }

    .info-item span {
        color: #333;
        font-size: 0.875rem;
        word-break: break-word;
    }

    /* Avatar & Images */
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .room-image,
    .id-photo {
        max-width: 200px;
        height: auto;
        border-radius: 4px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .room-image:hover,
    .id-photo:hover {
        transform: scale(1.05);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #fff;
        display: inline-block;
    }

    .status-pending_confirmation {
        background: #f1c40f;
    }

    .status-confirmed {
        background: #17a2b8;
    }

    .status-paid {
        background: #28a745;
    }

    .status-check_in {
        background: #007bff;
    }

    .status-check_out {
        background: #2197ff;
    }

    .status-cancelled {
        background: #dc3545;
    }

    .status-refunded {
        background: #ff851b;
    }

    .status-unknown {
        background: #6c757d;
    }

    .status-pending {
        background: #f1c40f;
    }

    .status-completed {
        background: #28a745;
    }

    .status-failed {
        background: #dc3545;
    }

    .text-pending_confirmation {
        color: #f1c40f !important;
    }

    .text-confirmed {
        color: #17a2b8 !important;
    }

    .text-paid {
        color: #28a745 !important;
    }

    .text-check_in {
        color: #007bff !important;
    }

    .text-check_out {
        color: #52a1e6 !important;
    }

    .text-cancelled {
        color: #dc3545 !important;
    }

    .text-refunded {
        color: #ff851b !important;
    }

    .text-unknown {
        color: #6c757d !important;
    }

    .text-pending {
        color: #f1c40f !important;
    }

    .text-completed {
        color: #28a745 !important;
    }

    .text-failed {
        color: #dc3545 !important;
    }

    /* Method Badges */
    .text-momo {
        color: #a50064;
    }

    .text-vnpay {
        color: #0056b3;
    }

    .text-cash {
        color: #28a745;
    }

    /* Lists */
    .room-list,
    .payment-list,
    .service-list {
        list-style: none;
        padding: 0;
    }

    .room-list li,
    .payment-list li,
    .service-list li {
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .amenities-list,
    .rules-list {
        list-style: none;
        padding-left: 10px;
        margin: 0;
    }

    .amenities-list li,
    .rules-list li {
        margin-bottom: 6px;
        color: #666;
        font-size: 0.875rem;
    }

    .amenities-list li:before,
    .rules-list li:before {
        content: "✓";
        color: #28a745;
        margin-right: 6px;
    }

    /* Buttons */
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: #0d6efd;
        border: none;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
    }

    .btn-sm {
        padding: 5px 10px;
    }

    /* Table */
    .table {
        font-size: 0.875rem;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    /* Tooltip */
    .tooltip-inner {
        background: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .tooltip .tooltip-arrow::before {
        border-top-color: #333 !important;
    }
</style>
@section('content')

<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page Title & Breadcrumb -->
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-4">
            <div class="lh-breadcrumb">
                <h5>{{ $title }}</h5>
                <ul>
                    <li><a href="{{ route('admin.bookings.index') }}">Trang chủ</a></li>
                    <li><a href="{{ route('admin.bookings.index') }}">Đơn đặt phòng</a></li>
                    <li>Chi tiết</li>
                </ul>
            </div>
            <div>
                @php
                $stayStatus = '';
                $stayStatusClass = '';
                $tooltipInfo = '';
                if (!in_array($booking->status, ['cancelled', 'refunded'])) {
                if ($booking->actual_check_in && !$booking->actual_check_out) {
                $stayStatus = 'Đã ở';
                $stayStatusClass = 'status-check_in';
                $tooltipInfo = 'Check-in thực tế: ' . \App\Helpers\FormatHelper::formatDateTime($booking->actual_check_in);
                } elseif ($booking->actual_check_in && $booking->actual_check_out) {
                $stayStatus = 'Đã ra';
                $stayStatusClass = 'status-check_out';
                $tooltipInfo = 'Check-out thực tế: ' . \App\Helpers\FormatHelper::formatDateTime($booking->actual_check_out);
                } else {
                $stayStatus = 'N/A';
                $stayStatusClass = 'status-unknown';
                }
                } else {
                $stayStatus = 'N/A';
                $stayStatusClass = 'status-unknown';
                }
                @endphp
                @if ($stayStatus !== 'N/A')
                <span class="status-badge {{ $stayStatusClass }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $tooltipInfo }}">
                    {{ $stayStatus }}
                </span>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Thông tin người đặt & người ở -->
            <div class="col-md-12">
                <div class="row">
                    <!-- Thông tin người đặt -->
                    <div class="col-md-6">
                        <div class="booking-section">
                            <div class="booking-section-header">
                                <h5>Thông tin người đặt</h5>
                                <span class="toggle-icon">▼</span>
                            </div>
                            <div class="booking-section-content open">
                                <div class="text-center mb-3">
                                    <img src="{{ $booking->user && $booking->user->avatar ? Storage::url('avatars/' . $booking->user->avatar) : asset('assets/admin/assets/img/user/1.jpg') }}" alt="{{ $booking->user ? $booking->user->name : 'Người dùng không xác định' }}" class="user-avatar">
                                    <h6 class="mt-2">{{ $booking->user->name }}</h6>
                                </div>
                                <ul class="list-unstyled">
                                    <li><strong>Số điện thoại:</strong> {{ $booking->user->phone ?? 'Không có' }}</li>
                                    <li><strong>Email:</strong> {{ $booking->user->email ?? 'Không có' }}</li>
                                    <li><strong>Địa chỉ:</strong> {{ $booking->user->address ?? 'Không có' }}</li>
                                    <li><strong>Giới tính:</strong> {{ $booking->user->gender ?? 'Không xác định' }}</li>
                                    <li>
                                        <strong>Ảnh CCCD:</strong><br>
                                        @if ($booking->user->id_photo)
                                        @php
                                        $idPhotoPath = $booking->user->id_photo;
                                        $fileName = str_contains($idPhotoPath, 'id_photos/') ? basename($idPhotoPath) : $idPhotoPath;
                                        $fullPath = Storage::url('id_photos/' . $fileName);
                                        @endphp
                                        @if (Storage::disk('public')->exists('id_photos/' . $fileName))
                                        <img style="width:150;height:80px" src="{{ $fullPath }}" alt="Ảnh CCCD" class="id-photo" onclick="showImageModal(this.src)">
                                        @else
                                        Không tồn tại file ảnh
                                        @endif
                                        @else
                                        Không có
                                        @endif
                                    </li>
                                    <li><strong>CCCD:</strong> {{ $booking->user->id_number ?? 'Không có' }}</li>
                                    <li><strong>Ngày sinh:</strong> {{ $booking->user->birth_date ? \App\Helpers\FormatHelper::formatDate($booking->user->birth_date) : 'Không có' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin người ở -->
                    <div class="col-md-6">
                        <div class="booking-section">
                            <div class="booking-section-header">
                                <h5>Thông tin người ở</h5>
                                <span class="toggle-icon">▼</span>
                            </div>
                            <div class="booking-section-content open">
                                <ul class="list-unstyled">
                                    @if ($booking->guests->isEmpty())
                                    <li>Chưa có thông tin người ở.</li>
                                    @else
                                    @foreach ($booking->guests as $index => $guest)
                                    <li class="guest-item mb-3">
                                        <h6>Người ở {{ $index + 1 }}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Tên:</strong> {{ $guest->name ?? 'Không có' }}<br>
                                                <strong>CCCD:</strong> {{ $guest->id_number ?? 'Không có' }}<br>
                                                <strong>Ngày sinh:</strong> {{ $guest->birth_date ? \App\Helpers\FormatHelper::formatDate($guest->birth_date) : 'Không có' }}<br>
                                                <strong>Giới tính:</strong> {{ $guest->gender ?? 'Không xác định' }}<br>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Số điện thoại:</strong> {{ $guest->phone ?? 'Không có' }}<br>
                                                <strong>Email:</strong> {{ $guest->email ?? 'Không có' }}<br>
                                                <strong>Mối quan hệ:</strong> {{ $guest->relationship ?? 'Không có' }}<br>
                                                <strong>Ảnh CCCD:</strong><br>
                                                @if ($guest->id_photo)
                                                @php
                                                $idPhotoPath = $guest->id_photo;
                                                $fileName = str_contains($idPhotoPath, 'id_photos/') ? basename($idPhotoPath) : $idPhotoPath;
                                                $fullPath = Storage::url('id_photos/' . $fileName);
                                                @endphp
                                                @if (Storage::disk('public')->exists('id_photos/' . $fileName))
                                                <img src="{{ $fullPath }}" alt="Ảnh CCCD" class="id-photo" onclick="showImageModal(this.src)">
                                                @else
                                                Không tồn tại file ảnh
                                                @endif
                                                @else
                                                Không có
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin phòng & Dịch vụ phát sinh -->
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
                                <div class="row g-2">
                                    <div class="col-md-6"><strong>Số phòng:</strong> {{ $room->room_number }}</div>
                                    <div class="col-md-6"><strong>Loại phòng:</strong> {{ $room->roomType->name ?? 'Chưa xác định' }}</div>
                                    <div class="col-md-6"><strong>Loại giường:</strong> {{ ['single' => 'Giường đơn', 'double' => 'Giường đôi', 'queen' => 'Giường Queen', 'king' => 'Giường King', 'bunk' => 'Giường tầng', 'sofa' => 'Giường sofa'][$room->roomType->bed_type] ?? 'Chưa xác định' }}</div>
                                    <div class="col-md-6"><strong>Kích thước:</strong> {{ $room->roomType->size ?? 'Chưa xác định' }} m²</div>
                                    <div class="col-md-6"><strong>Số người lớn:</strong> {{ $room->roomType->max_capacity ?? 'Chưa xác định' }}</div>
                                    <div class="col-md-6"><strong>Số trẻ em:</strong> {{ $room->roomType->children_free_limit ?? 'Chưa xác định' }}</div>
                                    <div class="col-md-6"><strong>Giá:</strong> {{ \App\Helpers\FormatHelper::formatPrice($room->roomType->price ?? 0) }}</div>
                                    <div class="col-md-6"><strong>Mô tả:</strong> {{ $room->roomType->description ?? 'Chưa xác định' }}</div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-4"><strong>Tiện nghi:</strong>
                                        <ul class="amenities-list">@forelse ($room->roomType->amenities as $amenity)<li>{{ $amenity->name }}</li>@empty<li>Không có</li>@endforelse</ul>
                                    </div>
                                    <div class="col-md-4"><strong>Quy tắc:</strong>
                                        <ul class="rules-list">@forelse ($room->roomType->rulesAndRegulations as $rule)<li>{{ $rule->name }}</li>@empty<li>Không có</li>@endforelse</ul>
                                    </div>
                                    <div class="col-md-4"><strong>Dịch vụ:</strong>
                                        <ul class="amenities-list">@forelse ($room->roomType->services as $service)<li>{{ $service->name }} - {{ \App\Helpers\FormatHelper::formatPrice($service->price) }}</li>@empty<li>Không có</li>@endforelse</ul>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="booking-section">
                    <div class="booking-section-header">
                        <h5>Dịch vụ phát sinh</h5>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="booking-section-content">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServicePlusModal">Thêm dịch vụ phát sinh</button>
                        {{-- @if(!$booking->service_plus_status)
                        @endif
                        @if ($booking->servicePlus->isEmpty())
                        <p>Chưa có dịch vụ phát sinh nào được chọn.</p>
                        @else --}}
                        <table class="table table-striped" id="servicePlusTable">
                            <thead>
                                <tr>
                                    <th>Tên dịch vụ</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    @if(!$booking->service_plus_status)
                                    <th>Hành động</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($booking->servicePlus as $servicePlus)
                                <tr>
                                    <td>{{ $servicePlus->name }}</td>
                                    <td>{{ \App\Helpers\FormatHelper::formatPrice($servicePlus->price) }}</td>
                                    <td>{{ $servicePlus->pivot->quantity }}</td>
                                    @if(!$booking->service_plus_status)
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-service-plus" data-service-plus-id="{{ $servicePlus->id }}" data-quantity="{{ $servicePlus->pivot->quantity }}">Sửa</button>
                                        <button class="btn btn-sm btn-danger remove-service-plus" data-service-plus-id="{{ $servicePlus->id }}">Xóa</button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>

            <!-- Thông tin đơn đặt phòng & Thanh toán -->
            <div class="col-md-6">
                <div class="booking-section">
                    <div class="booking-section-header">
                        <h5>Thông tin đơn đặt phòng</h5>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="booking-section-content">
                        <ul class="list-unstyled">
                            <li><strong>Mã đặt phòng:</strong> {{ $booking->booking_code }}</li>
                            <li><strong>Ngày check-in:</strong> {{ \App\Helpers\FormatHelper::formatDate($booking->check_in) }}</li>
                            <li><strong>Ngày check-out:</strong> {{ \App\Helpers\FormatHelper::formatDate($booking->check_out) }}</li>
                            <li><strong>Giờ check-in thực tế:</strong> {{ $booking->actual_check_in ? \App\Helpers\FormatHelper::formatDateTime($booking->actual_check_in) : 'Chưa check-in' }}</li>
                            <li><strong>Giờ check-out thực tế:</strong> {{ $booking->actual_check_out ? \App\Helpers\FormatHelper::formatDateTime($booking->actual_check_out) : 'Chưa check-out' }}</li>
                            <li><strong>Giá gốc:</strong> {{ \App\Helpers\FormatHelper::formatPrice($booking->base_price) }}</li>
                            <li><strong>Số tiền giảm:</strong> {{ \App\Helpers\FormatHelper::formatPrice($booking->discount_amount) }}</li>
                            <li><strong>Phí thuế:</strong> {{ \App\Helpers\FormatHelper::formatPrice($booking->tax_fee) }}</li>
                            <li><strong>Số lượng phòng:</strong> {{ $booking->room_quantity }}</li>

                            <li><strong>Dịch vụ loại phòng đã đặt:</strong> {{ \App\Helpers\FormatHelper::formatPrice($booking->service_total) }}
                                @if($booking->serviceDetails->count() > 0)
                                    <ul>
                                        @foreach($booking->serviceDetails as $service)
                                            <li>{{ $service['name'] }} ({{ $service['quantity'] }} x {{ \App\Helpers\FormatHelper::formatPrice($service['price']) }})</li>
                                        @endforeach
                                    </ul>
                                @else
                                    Không có dịch vụ loại phòng
                                @endif
                            </li>

                            <li><strong>Tổng tiền:</strong> {{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</li>
                            <li><strong>Số người:</strong> Người lớn: {{ $booking->total_guests }} | Trẻ em: {{ $booking->children_count }}</li>
                            <li><strong>Yêu cầu đặc biệt:</strong> {{ $booking->special_request ?? 'Không có' }}</li>
                            <li><strong>Trạng thái:</strong>
                                <span class="{{ \App\Helpers\BookingStatusHelper::getStatusClass($booking->status) }}">
                                    {{ \App\Helpers\BookingStatusHelper::getStatusLabel($booking->status) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="booking-section">
                    <div class="booking-section-header">
                        <h5>Thông tin thanh toán</h5>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="booking-section-content">
                        @forelse ($booking->payments as $payment)
                        <ul class="payment-list">
                            <li>
                                <strong>Phương thức:</strong>
                                <span class="text-{{ strtolower($payment->method) }}">
                                    {{ ['momo' => 'MOMO', 'vnpay' => 'VNPAY', 'cash' => 'Tiền mặt'][$payment->method] ?? 'Không xác định' }}
                                </span><br>
                                <strong>Số tiền:</strong> {{ \App\Helpers\FormatHelper::formatPrice($payment->amount ?? 0) }}<br>
                                <strong>Ngày thanh toán:</strong> {{ \App\Helpers\FormatHelper::formatDate($payment->created_at) }}<br>
                                <strong>Trạng thái:</strong>
                                <span class="{{ \App\Helpers\PaymentStatusHelper::getStatusClass($payment->status) }}">
                                    {{ \App\Helpers\PaymentStatusHelper::getStatusLabel($payment->status) }}
                                </span>
                            </li>
                        </ul>
                        @empty
                        <p>Chưa có thông tin thanh toán.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Dịch vụ phát sinh & Tổng tiền -->
            <div class="col-md-6">
                <div class="booking-section">
                    <div class="booking-section-header">
                        <h5>Tổng dịch vụ phát sinh</h5>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="booking-section-content">
                        @php $totalServicePlusPrice = 0; @endphp <!-- Initialize the variable here -->
                        @if ($booking->servicePlus->isEmpty())
                        <p>Chưa có dịch vụ phát sinh.</p>
                        @else
                        <ul class="service-list">
                            @foreach ($booking->servicePlus as $service)
                            @php
                            $totalPrice = $service->price * $service->pivot->quantity;
                            $totalServicePlusPrice += $totalPrice;
                            @endphp
                            <li>{{ $service->name }} ({{ $service->pivot->quantity }} x {{ \App\Helpers\FormatHelper::formatPrice($service->price) }}) = {{ \App\Helpers\FormatHelper::formatPrice($totalPrice) }}</li>
                            @endforeach
                        </ul>
                        <p><strong>Tổng cộng:</strong> {{ \App\Helpers\FormatHelper::formatPrice($totalServicePlusPrice) }}</p>
                        <div class="mt-2">
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

            <div class="col-md-6">
                <div class="booking-section">
                    <div class="booking-section-header">
                        <h5>Tổng tiền</h5>
                        <span class="toggle-icon">▼</span>
                    </div>
                    <div class="booking-section-content">
                        @php
                        if ($booking->service_plus_status === 'paid') {
                            $totalServicePlusPrice = $totalServicePlusPrice;
                        } else {
                            $totalServicePlusPrice = 0;
                        }
                        $totalAmount = $booking->total_price + $totalServicePlusPrice;
                        @endphp
                        <p><strong>Tổng tiền đơn đặt phòng:</strong> <span>{{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</span></p>
                        <p><strong>Tổng tiền dịch vụ phát sinh:</strong> <span>{{ \App\Helpers\FormatHelper::formatPrice($totalServicePlusPrice) }}</span></p>
                        <p><strong>Tổng tiền:</strong> <span>{{ \App\Helpers\FormatHelper::formatPrice($totalAmount) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<!-- Modal thêm dịch vụ phát sinh -->
<div class="modal fade" id="addServicePlusModal" tabindex="-1" aria-labelledby="addServicePlusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addServicePlusModalLabel">Thêm dịch vụ phát sinh</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addServicePlusForm">
                    @csrf
                    <input type="hidden" name="action" value="addServicePlus">
                    <div class="mb-3">
                        <label for="service_plus_id" class="form-label">Dịch vụ phát sinh:</label>
                        <select name="service_plus_id" class="form-control" required>
                            <option value="">Chọn dịch vụ</option>
                            @foreach ($availableServicePlus as $servicePlus)
                            <option value="{{ $servicePlus->id }}">{{ $servicePlus->name }} ({{ \App\Helpers\FormatHelper::formatPrice($servicePlus->price) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa dịch vụ phát sinh -->
<div class="modal fade" id="editServicePlusModal" tabindex="-1" aria-labelledby="editServicePlusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editServicePlusModalLabel">Sửa số lượng dịch vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editServicePlusForm">
                    @csrf
                    <input type="hidden" name="action" value="updateServicePlus">
                    <input type="hidden" name="service_plus_id" id="editServicePlusId">
                    <div class="mb-3">
                        <label for="editQuantity" class="form-label">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" id="editQuantity" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật trạng thái -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateStatusModalLabel">Cập nhật trạng thái</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái mới:</label>
                        <select name="status" id="status" class="form-control" required>
                            @php $statusOptions = ['pending_confirmation' => 'Chưa xác nhận', 'confirmed' => 'Đã xác nhận', 'paid' => 'Đã thanh toán', 'check_in' => 'Đã check-in', 'check_out' => 'Đã check-out', 'cancelled' => 'Đã hủy', 'refunded' => 'Đã hoàn tiền']; @endphp
                            @foreach ($statusOptions as $key => $value)
                            <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal phóng to ảnh -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="imagePreviewModalLabel">Xem ảnh CCCD</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark">
                <img id="modalImage" src="" alt="Ảnh CCCD" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionHeaders = document.querySelectorAll('.booking-section-header');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Khởi tạo tooltip
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // Toggle sections
        sectionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');
                content.classList.toggle('open');
                toggleIcon.classList.toggle('open');
                toggleIcon.textContent = content.classList.contains('open') ? '▲' : '▼';
            });
        });

        // Thêm dịch vụ phát sinh
        document.getElementById('addServicePlusForm').addEventListener('submit', function(e) {
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
                            text: data.message
                        }).then(() => {
                            location.reload(); // Tải lại trang để cập nhật
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi thêm dịch vụ.'
                }));
        });

        // Sửa dịch vụ phát sinh
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-service-plus')) {
                const servicePlusId = e.target.getAttribute('data-service-plus-id');
                const quantity = e.target.getAttribute('data-quantity');
                document.getElementById('editServicePlusId').value = servicePlusId;
                document.getElementById('editQuantity').value = quantity;
                new bootstrap.Modal(document.getElementById('editServicePlusModal')).show();
            }

            if (e.target.classList.contains('remove-service-plus')) {
                if (confirm('Bạn có muốn xóa dịch vụ này không?')) {
                    const servicePlusId = e.target.getAttribute('data-service-plus-id');
                    const formData = new FormData();
                    processServicePlusRequest('removeServicePlus', servicePlusId, formData, e.target.closest('tr'));
                }
            }
        });

        document.getElementById('editServicePlusForm').addEventListener('submit', function(e) {
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
                            text: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi cập nhật.'
                }));
        });

        // Cập nhật trạng thái dịch vụ phát sinh
        document.querySelectorAll('.service-plus-status').forEach(select => {
            select.addEventListener('change', function() {
                const newStatus = this.value;
                fetch(`{{ route('admin.bookings.service_plus.update', $booking->id) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
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
                                text: data.message
                            }).then(() => {
                                if (newStatus === 'paid') select.disabled = true;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: data.message
                            }).then(() => {
                                select.value = '{{ $booking->service_plus_status }}';
                            });
                        }
                    })
                    .catch(() => Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra khi cập nhật trạng thái.'
                    }));
            });
        });

        // Hàm xử lý yêu cầu dịch vụ phát sinh
        function processServicePlusRequest(action, servicePlusId, formData, row) {
            formData.append('action', action);
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
                            text: data.message
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message
                        });
                    }
                })
                .catch(() => Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Có lỗi xảy ra khi xử lý.'
                }));
        }
    });

    // Hiển thị ảnh trong modal
    function showImageModal(src) {
        const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
        document.getElementById('modalImage').src = src;
        modal.show();
    }
</script>

<!-- Thư viện -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
@endsection
