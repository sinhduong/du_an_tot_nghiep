@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Chi tiết đơn đặt phòng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="">Danh sách đặt phòng</a>
                        </span>
                        <span> / </span>
                        <span>Chi tiết</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="booking-details padding-tb-20 mt-5">
    <div class="container">
        <div class="row">
            <div>
                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
            </div>
            <!-- Phần thông tin đặt phòng (bên trái) -->
            <div class="col-lg-4 check-sidebar" data-aos="fade-up" data-aos-duration="3000">
                <div class="lh-side-room">
                    <div class="lh-side-reservation">
                        <!-- Chi tiết đặt phòng -->
                        <div class="lh-check-block-content mb-4">
                            <h4 class="lh-room-inner-heading mb-3">Chi tiết đặt phòng của bạn</h4>
                            <p><strong>Mã đặt phòng:</strong> {{ $booking->booking_code }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nhận phòng:</strong>
                                        {{ \App\Helpers\FormatHelper::FormatDate($booking->check_in) }}
                                    </p>
                                    <p>{{ $booking->check_in_time }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Trả phòng:</strong>
                                        {{ \App\Helpers\FormatHelper::FormatDate($booking->check_out) }}
                                    </p>
                                    <p>{{ $booking->check_out_time }}</p>
                                </div>
                            </div>
                            <p><strong>Tổng thời gian lưu trú:</strong>
                                @php
                                $checkInDate = $booking->check_in->startOfDay();
                                $checkOutDate = $booking->check_out->startOfDay();
                                $days = $checkOutDate->diffInDays($checkInDate);
                                $nights = $days == 0 ? 1 : $days;
                                @endphp
                                {{ $nights }} đêm
                            </p>
                            <p><strong>Trạng thái:</strong>
                                <!-- @switch($booking->status)
                                        @case('unpaid')
                                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                        @break

                                        @case('partial')
                                            <span class="badge bg-success">Đã cọc</span>
                                        @break

                                        @case('paid')
                                            <span class="badge bg-info">Đã thanh toán</span>
                                        @break

                                        @case('check_in')
                                            <span class="badge bg-primary">Đã vào (đang ở)</span>
                                        @break

                                        @case('check_out')
                                            <span class="badge bg-secondary">Đã trả phòng</span>
                                        @break

                                        @case('cancelled')
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @break

                                        @case('refunded')
                                            <span class="badge bg-dark">Đã hoàn tiền</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                                    @endswitch -->
                                <span class="{{ \App\Helpers\BookingStatusHelper::getStatusClass($booking->status) }}">
                                    {{ \App\Helpers\BookingStatusHelper::getStatusLabel($booking->status) }}
                                </span>
                                @php
                                $payment = $booking->payments->first();
                                $method = $payment->method;
                                $paymentStatus = $payment->status;
                                @endphp
                            </p>
                            <p><strong>Phương thức thanh toán:</strong>
                                @if ($booking->payments->isNotEmpty())
                                @switch($method)
                                @case('cash')
                                <span class="badge bg-secondary">Thanh toán tại chỗ (Tiền mặt)</span>
                                @break

                                @case('momo')
                                <span class="badge bg-danger">Thanh toán qua MoMo</span>
                                @break

                                @case('vnpay')
                                <span class="badge bg-primary">Thanh toán qua VNPay</span>
                                @break

                                @default
                                <span class="badge bg-info">Thanh toán trực tuyến ({{ $method }})</span>
                                @endswitch
                                @else
                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                @endif

                                @if ($paymentStatus=='completed')
                                    <span class="badge bg-info mt-2">
                                        <i class="fas fa-clock"></i> {{ $payment->created_at->format('H:i:s d-m-Y') }}
                                    </span>
                                    @endif
                            </p>
                        </div>

                        <!-- Bạn đã chọn -->
                        <div class="lh-check-block-content mb-4">
                            <h4 class="lh-room-inner-heading mb-3">Bạn đã chọn</h4>
                            <p>{{ $booking->rooms->count() }} phòng cho
                                {{ $booking->total_guests + $booking->children_count }} người
                            </p>
                            @if ($booking->rooms->isNotEmpty() && $booking->rooms->first() && $booking->rooms->first()->roomType)
                            <p>{{ $booking->rooms->count() }} x {{ $booking->rooms->first()->roomType->name }}</p>
                            @else
                            <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông tin loại
                                phòng.</p>
                            @endif
                            @if ($booking->services->isNotEmpty())
                            <h5 class="lh-room-inner-heading mb-2">Dịch vụ bổ sung</h5>
                            @foreach ($booking->services as $service)
                            <div class="d-flex justify-content-between mb-2">
                                <p class="mb-0">
                                    <i class="fas fa-concierge-bell me-2 text-primary"></i>
                                    {{ $service->service->name }} ({{ $service->pivot->quantity }} x
                                    {{ \App\Helpers\FormatHelper::FormatPrice($service->pivot->price) }})
                                </p>
                                <p class="mb-0">
                                    {{ \App\Helpers\FormatHelper::FormatPrice($service->pivot->price * $service->pivot->quantity) }}
                                </p>
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có dịch vụ bổ sung.</p>
                            @endif
                        </div>

                        <!-- Tổng giá -->
                        <div class="lh-check-block-content mb-4">
                            <h4 class="lh-room-inner-heading mb-3">Tổng giá</h4>
                            <div class="d-flex justify-content-between">
                                <p>Giá gốc ({{ $booking->rooms->count() }} phòng x {{ $nights }} đêm)</p>
                                <p>{{ \App\Helpers\FormatHelper::FormatPrice($booking->base_price) }}</p>
                            </div>
                            @if ($booking->service_total > 0)
                            <div class="d-flex justify-content-between">
                                <p>Dịch vụ bổ sung</p>
                                <p>{{ \App\Helpers\FormatHelper::FormatPrice($booking->service_total) }}</p>
                            </div>
                            @endif
                            @if ($booking->discount_amount > 0)
                            <div class="d-flex justify-content-between">
                                <p>Giảm giá (Mã khuyến mãi)</p>
                                <p>- {{ \App\Helpers\FormatHelper::FormatPrice($booking->discount_amount) }}</p>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <p>Thuế và phí (8%)</p>
                                <p>{{ \App\Helpers\FormatHelper::FormatPrice($booking->tax_fee) }}</p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="lh-room-inner-heading border-0">Tổng cộng</h5>
                                <h5 class="lh-room-inner-heading text-danger border-0">
                                    {{ \App\Helpers\FormatHelper::FormatPrice($booking->total_price) }}
                                </h5>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h6 class="lh-room-inner-heading border-0">Đã thanh toán</h6>
                                <h6 class="lh-room-inner-heading text-danger border-0">
                                    {{ \App\Helpers\FormatHelper::FormatPrice($booking->paid_amount) }}
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <h6 class="lh-room-inner-heading border-0">Còn lại</h6>
                                <h6 class="lh-room-inner-heading text-danger border-0">
                                    {{ \App\Helpers\FormatHelper::FormatPrice($booking->total_price - $booking->paid_amount) }}
                                </h6>
                            </div>
                            <hr>
                            <!-- <p class="text-muted">Đã bao gồm thuế và phí</p> -->
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="lh-check-block-content">
                            <h4 class="lh-room-inner-heading mb-3">Thông tin thêm</h4>
                            <p><i class="fas fa-check-circle text-success me-2"></i> Đã bao gồm thuế VAT</p>
                            <p><i class="fas fa-check-circle text-success me-2"></i> 8% Thuế GTGT:
                                {{ \App\Helpers\FormatHelper::FormatPrice($booking->tax_fee) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần thông tin chi tiết (bên phải) -->
            <div class="col-lg-8 check-dash" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-checkout">
                    <div class="lh-checkout-content">
                        <div class="lh-checkout-inner">
                            <!-- Thông tin loại phòng -->
                            <div class="lh-checkout-wrap mb-4">
                                <h4 class="lh-checkout-title mb-3">Thông tin loại phòng</h4>
                                <div class="lh-check-block-content">
                                    @if ($booking->rooms->isNotEmpty() && $booking->rooms->first() && $booking->rooms->first()->roomType)
                                    @php
                                    $roomType = $booking->rooms->first()->roomType;
                                    $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                    @endphp
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="me-3">
                                            @if ($mainImage)
                                            <img src="{{ Storage::url($mainImage->image) }}"
                                                alt="{{ $roomType->name }}" class="rounded"
                                                style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                            @else
                                            <img src="{{ asset('images/default-room.jpg') }}"
                                                alt="Default Room Image" class="rounded"
                                                style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="lh-checkout-title mb-1">{{ $roomType->name }}</h4>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-ruler-combined me-1"></i> {{ $roomType->size }} m²
                                                |
                                                <i class="fas fa-bed me-1"></i>
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
                                                {{ $bedTypeMapping[$roomType->bed_type] ?? 'Không xác định' }} |
                                                <i class="fas fa-users me-1"></i> Tối đa
                                                {{ $roomType->max_capacity }} người
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Mô tả loại phòng -->
                                    @if ($roomType->description)
                                    <div class="lh-check-block-content mb-4">
                                        <h5 class="lh-room-inner-heading mb-2">Mô tả</h5>
                                        <p class="text-muted">{{ $roomType->description }}</p>
                                    </div>
                                    @endif

                                    <!-- Tiện nghi -->
                                    <div class="lh-check-block-content mb-4">
                                        <h5 class="lh-room-inner-heading mb-2">Tiện nghi</h5>
                                        @if ($roomType->amenities->isNotEmpty())
                                        <div class="row">
                                            @foreach ($roomType->amenities as $amenity)
                                            <div class="col-md-6 mb-2">
                                                <p class="mb-0"><i
                                                        class="fas fa-check-circle text-success me-2"></i>
                                                    {{ $amenity->name }}
                                                </p>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có
                                            tiện nghi.</p>
                                        @endif
                                    </div>

                                    <!-- Quy định -->
                                    @if ($roomType->rulesAndRegulations->isNotEmpty())
                                    <div class="lh-check-block-content mb-4">
                                        <h5 class="lh-room-inner-heading mb-2">Quy định</h5>
                                        <ul class="list-unstyled">
                                            @foreach ($roomType->rulesAndRegulations as $rule)
                                            <li class="mb-2"><i
                                                    class="fas fa-exclamation-circle text-warning me-2"></i>
                                                {{ $rule->name }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @else
                                    <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có quy
                                        định.</p>
                                    @endif
                                    @else
                                    <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông
                                        tin loại phòng.</p>
                                    @endif

                                    <!-- Chính sách huỷ đặt phòng và hoàn tiền -->
                                    @if ($refundPolicies->isNotEmpty())
                                    <div class="lh-check-block-content mb-4">
                                        <h5 class="lh-room-inner-heading mb-2">Chính sách huỷ đặt phòng và hoàn tiền</h5>
                                        <ul class="list-unstyled">
                                            @foreach ($refundPolicies as $refundPolicy)
                                            <li class="mb-2"><i
                                                    class="fas fa-exclamation-circle text-warning me-2"></i>
                                                {{ $refundPolicy->name }}: {{ $refundPolicy->description }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <!-- Trạng thái hoàn tiền -->
                                    @if ($booking->refund)
                                    <div class="lh-check-block-content mb-4">
                                        <h5 class="lh-room-inner-heading mb-2">Trạng thái hoàn tiền</h5>
                                        <div class="alert
                                            @if($booking->refund->status == 'pending') alert-warning
                                            @elseif($booking->refund->status == 'approved') alert-success
                                            @elseif($booking->refund->status == 'rejected') alert-danger
                                            @else alert-info @endif">
                                            <div class="d-flex align-items-center">
                                                <i class="fas
                                                    @if($booking->refund->status == 'pending') fa-clock
                                                    @elseif($booking->refund->status == 'approved') fa-check-circle
                                                    @elseif($booking->refund->status == 'rejected') fa-times-circle
                                                    @else fa-info-circle @endif
                                                    me-2"></i>
                                                <div>
                                                    <strong>Trạng thái:</strong>
                                                    @switch($booking->refund->status)
                                                        @case('pending')
                                                            Đang chờ xử lý
                                                            @break
                                                        @case('approved')
                                                            Đã được phê duyệt
                                                            @break
                                                        @case('rejected')
                                                            Đã bị từ chối
                                                            @break
                                                        @default
                                                            {{ $booking->refund->status }}
                                                    @endswitch
                                                </div>
                                            </div>
                                            @if($booking->refund->amount >= 0)
                                            <div class="mt-2">
                                                <strong>Số tiền hoàn:</strong> {{ number_format($booking->refund->amount) }} VNĐ
                                            </div>
                                            @endif
                                            @if($booking->refund->cancellation_fee >= 0)
                                            <div class="mt-2">
                                                <strong>Phí hủy:</strong> {{ number_format($booking->refund->cancellation_fee) }} VNĐ
                                            </div>
                                            @endif
                                            @if($booking->refund->reason)
                                            <div class="mt-2">
                                                <strong>Lý do:</strong> {{ $booking->refund->reason }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>

                            <!-- Thông tin người đặt -->
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Thông tin người đặt</h3>
                                <div class="lh-check-block-content">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Họ và tên:</strong> {{ $booking->user->name }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Số điện thoại:</strong>
                                                {{ $booking->user->phone ?? 'Chưa cung cấp' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Quốc gia:</strong>
                                                {{ $booking->user->country ?? 'Chưa cung cấp' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Ngày sinh:</strong>
                                                {{ $booking->user->birth_date ? \App\Helpers\FormatHelper::FormatDate($booking->user->birth_date) : 'Chưa cung cấp' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Giới tính:</strong>
                                                @switch($booking->user->gender)
                                                @case('male')
                                                Nam
                                                @break

                                                @case('female')
                                                Nữ
                                                @break

                                                @case('other')
                                                Khác
                                                @break

                                                @default
                                                Chưa cung cấp
                                                @endswitch
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Số CMND/CCCD:</strong>
                                                {{ $booking->user->id_number ?? 'Chưa cung cấp' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Ảnh CMND/CCCD:</strong>
                                                @if ($booking->user->id_photo)
                                                <a href="{{ Storage::url($booking->user->id_photo) }}"
                                                    target="_blank" class="text-primary">Xem ảnh</a>
                                                @else
                                                Chưa cung cấp
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin người ở -->
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Thông tin người ở</h3>
                                <div class="lh-check-block-content">
                                    @if ($booking->guests->isNotEmpty())
                                    @foreach ($booking->guests as $index => $guest)
                                    <div class="guest-info mb-4">
                                        <h5 class="mb-2">Người ở {{ $index + 1 }}</h5>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Họ và tên:</strong> {{ $guest->name }}</p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Số CMND/CCCD:</strong>
                                                    {{ $guest->id_number ?? 'Chưa cung cấp' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Ngày sinh:</strong>
                                                    {{ $guest->birth_date ? \App\Helpers\FormatHelper::FormatDate($guest->birth_date) : 'Chưa cung cấp' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Giới tính:</strong>
                                                    @switch($guest->gender)
                                                    @case('male')
                                                    Nam
                                                    @break

                                                    @case('female')
                                                    Nữ
                                                    @break

                                                    @case('other')
                                                    Khác
                                                    @break

                                                    @default
                                                    Chưa cung cấp
                                                    @endswitch
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Số điện thoại:</strong>
                                                    {{ $guest->phone ?? 'Chưa cung cấp' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Email:</strong>
                                                    {{ $guest->email ?? 'Chưa cung cấp' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Quan hệ với người đặt:</strong>
                                                    {{ $guest->relationship ?? 'Chưa cung cấp' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <p><strong>Ảnh CMND/CCCD:</strong>
                                                    @if ($guest->id_photo)
                                                    <a href="{{ Storage::url($guest->id_photo) }}"
                                                        target="_blank" class="text-primary">Xem ảnh</a>
                                                    @else
                                                    Chưa cung cấp
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông
                                        tin người ở.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Yêu cầu đặc biệt -->
                            @if ($booking->special_request)
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Yêu cầu đặc biệt</h3>
                                <div class="lh-check-block-content">
                                    <p>{{ $booking->special_request }}</p>
                                </div>
                            </div>
                            @else
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Yêu cầu đặc biệt</h3>
                                <div class="lh-check-block-content">
                                    <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có yêu cầu
                                        đặc biệt.</p>
                                </div>
                            </div>
                            @endif

                            <!-- Hành động -->
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Hành động</h3>
                                <div class="lh-check-block-content">
                                    @if (in_array($booking->status, ['unpaid', 'partial', 'paid']))
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        Hủy đặt phòng
                                    </button>
                                    @endif

                                    @if ($booking->status == 'unpaid')
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                        Thanh toán
                                    </button>
                                    @endif

                                    @if ($booking->status === 'check_out' && $booking->actual_check_out && !$booking->review)
                                        @php
                                            $roomTypeIds = $booking->rooms->pluck('roomType.id');
                                            $hasReviewedRoomType = \App\Models\Review::where('user_id', auth()->id())
                                                ->whereHas('booking.rooms', function ($query) use ($roomTypeIds) {
                                                    $query->whereIn('room_type_id', $roomTypeIds);
                                                })
                                                ->exists();
                                        @endphp
                                        @if (!$hasReviewedRoomType)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                                <i class="fas fa-star"></i> Đánh giá
                                            </button>
                                        @endif
                                    @endif

                                    @if (in_array($booking->status, ['cancelled', 'cancelled_without_refund']))
                                    <p class="text-danger"><i class="fas fa-times-circle me-2"></i> Đặt phòng đã
                                        được hủy.</p>
                                    @endif
                                    @if ($booking->status == 'refunded')
                                    <p class="text-dark"><i class="fas fa-undo-alt me-2"></i> Đặt phòng đã được
                                        hoàn tiền.</p>
                                    @endif
                                    @if ($booking->status == 'check_in')
                                    <p class="text-primary"><i class="fas fa-sign-in-alt me-2"></i> Đặt phòng đã
                                        check-in, không thể hủy.</p>
                                    @endif
                                    @if ($booking->status == 'check_out')
                                    <p class="text-secondary"><i class="fas fa-sign-out-alt me-2"></i> Đặt phòng
                                        đã check-out, không thể hủy.</p>
                                    @endif

                                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Quay lại danh
                                        sách</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" method="POST" action="{{ route('bookings.process-next-payment', $booking->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>
                    <input type="hidden" name="deposit_percentage" value="{{ $deposit_percentage }}">
                    <div class="mb-3">
                        <label for="payment_amount_type" class="form-label">Loại thanh toán</label>
                        <select class="form-select" id="payment_amount_type" name="payment_amount_type" required>
                            <option value="full">Thanh toán toàn bộ</option>
                            <option value="partial">Thanh toán một phần ({{ $deposit_percentage }}%)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_note" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="payment_note" name="payment_note" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Hủy đặt phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('refunds.request', $booking->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="reason">Lý do hủy phòng(Không bắt buộc)</label>
                        <textarea name="reason" id="reason" class="form-control"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">Gửi yêu cầu </button>
                        <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Đánh giá trải nghiệm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                    <div class="mb-3">
                        <label class="form-label">Đánh giá của bạn</label>
                        <div class="rating">
                            @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                            <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Nhận xét của bạn</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 30px;
    color: #ddd;
    padding: 5px;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}
</style>

<!-- Thêm Font Awesome và Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
