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
                            <a href="{{ route('bookings.index') }}">Danh sách đặt phòng</a>
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
                                    <p><strong>Nhận phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($booking->check_in) }}</p>
                                    <p>{{ $booking->check_in_time }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Trả phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($booking->check_out) }}</p>
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
                                @switch($booking->status)
                                    @case('pending_confirmation')
                                        <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-success">Đã xác nhận</span>
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
                                @endswitch
                            </p>
                            <p><strong>Phương thức thanh toán:</strong>
                                @if ($booking->payments->isNotEmpty())
                                    @php
                                        $payment = $booking->payments->first();
                                        $method = $payment->method;
                                        $paymentStatus = $payment->status;
                                    @endphp
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
                                    <span class="ms-2">
                                        @switch($paymentStatus)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Đã thanh toán</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Thanh toán thất bại</span>
                                                @break
                                            @case('refunded')
                                                <span class="badge bg-dark">Đã hoàn tiền</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $paymentStatus }}</span>
                                        @endswitch
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                @endif
                            </p>
                        </div>

                        <!-- Bạn đã chọn -->
                        <div class="lh-check-block-content mb-4">
                            <h4 class="lh-room-inner-heading mb-3">Bạn đã chọn</h4>
                            <p>{{ $booking->rooms->count() }} phòng cho {{ $booking->total_guests + $booking->children_count }} người</p>
                            @if ($booking->rooms->isNotEmpty() && $booking->rooms->first() && $booking->rooms->first()->roomType)
                                <p>{{ $booking->rooms->count() }} x {{ $booking->rooms->first()->roomType->name }}</p>
                            @else
                                <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông tin loại phòng.</p>
                            @endif
                            @if ($booking->servicePlus->isNotEmpty())
                                <p><strong>Dịch vụ bổ sung:</strong></p>
                                @foreach ($booking->servicePlus as $service)
                                    <p>{{ $service->name }} ({{ $service->pivot->quantity }} x {{ \App\Helpers\FormatHelper::FormatPrice($service->price) }}) = {{ \App\Helpers\FormatHelper::FormatPrice($service->price * $service->pivot->quantity) }}</p>
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
                                <h5 class="lh-room-inner-heading">Tổng cộng</h5>
                                <h5 class="lh-room-inner-heading text-danger">
                                    {{ \App\Helpers\FormatHelper::FormatPrice($booking->total_price) }}
                                </h5>
                            </div>
                            <p class="text-muted">Đã bao gồm thuế và phí</p>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="lh-check-block-content">
                            <h4 class="lh-room-inner-heading mb-3">Thông tin thêm</h4>
                            <p><i class="fas fa-check-circle text-success me-2"></i> Đã bao gồm thuế VAT</p>
                            <p><i class="fas fa-check-circle text-success me-2"></i> 8% Thuế GTGT: {{ \App\Helpers\FormatHelper::FormatPrice($booking->tax_fee) }}</p>
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
                                <h3 class="lh-checkout-title mb-3">Thông tin loại phòng</h3>
                                <div class="lh-check-block-content">
                                    @if ($booking->rooms->isNotEmpty() && $booking->rooms->first() && $booking->rooms->first()->roomType)
                                        @php
                                            $roomType = $booking->rooms->first()->roomType;
                                            $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                        @endphp
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="me-3">
                                                @if ($mainImage)
                                                    <img src="{{ Storage::url($mainImage->image) }}" alt="{{ $roomType->name }}" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                                @else
                                                    <img src="{{ asset('images/default-room.jpg') }}" alt="Default Room Image" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="lh-checkout-title mb-1">{{ $roomType->name }}</h4>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-ruler-combined me-1"></i> {{ $roomType->size }} m² |
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
                                                    <i class="fas fa-users me-1"></i> Tối đa {{ $roomType->max_capacity }} người
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
                                                            <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> {{ $amenity->name }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có tiện nghi.</p>
                                            @endif
                                        </div>

                                        <!-- Quy định -->
                                        @if ($roomType->rulesAndRegulations->isNotEmpty())
                                            <div class="lh-check-block-content mb-4">
                                                <h5 class="lh-room-inner-heading mb-2">Quy định</h5>
                                                <ul class="list-unstyled">
                                                    @foreach ($roomType->rulesAndRegulations as $rule)
                                                        <li class="mb-2"><i class="fas fa-exclamation-circle text-warning me-2"></i> {{ $rule->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có quy định.</p>
                                        @endif
                                    @else
                                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông tin loại phòng.</p>
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
                                            <p><strong>Số điện thoại:</strong> {{ $booking->user->phone ?? 'Chưa cung cấp' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Quốc gia:</strong> {{ $booking->user->country ?? 'Chưa cung cấp' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Ngày sinh:</strong> {{ $booking->user->birth_date ? \App\Helpers\FormatHelper::FormatDate($booking->user->birth_date) : 'Chưa cung cấp' }}</p>
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
                                            <p><strong>Số CMND/CCCD:</strong> {{ $booking->user->id_number ?? 'Chưa cung cấp' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Ảnh CMND/CCCD:</strong>
                                                @if ($booking->user->id_photo)
                                                    <a href="{{ Storage::url($booking->user->id_photo) }}" target="_blank" class="text-primary">Xem ảnh</a>
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
                                                        <p><strong>Số CMND/CCCD:</strong> {{ $guest->id_number ?? 'Chưa cung cấp' }}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p><strong>Ngày sinh:</strong> {{ $guest->birth_date ? \App\Helpers\FormatHelper::FormatDate($guest->birth_date) : 'Chưa cung cấp' }}</p>
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
                                                        <p><strong>Số điện thoại:</strong> {{ $guest->phone ?? 'Chưa cung cấp' }}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p><strong>Email:</strong> {{ $guest->email ?? 'Chưa cung cấp' }}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p><strong>Quan hệ với người đặt:</strong> {{ $guest->relationship ?? 'Chưa cung cấp' }}</p>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <p><strong>Ảnh CMND/CCCD:</strong>
                                                            @if ($guest->id_photo)
                                                                <a href="{{ Storage::url($guest->id_photo) }}" target="_blank" class="text-primary">Xem ảnh</a>
                                                            @else
                                                                Chưa cung cấp
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có thông tin người ở.</p>
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
                                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không có yêu cầu đặc biệt.</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Hành động -->
                            <div class="lh-checkout-wrap mb-4">
                                <h3 class="lh-checkout-title mb-3">Hành động</h3>
                                <div class="lh-check-block-content">
                                    @if (in_array($booking->status, ['pending_confirmation', 'confirmed']))
                                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đặt phòng này?');" class="d-inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="btn btn-danger me-2">Hủy đặt phòng</button>
                                        </form>
                                    @elseif ($booking->status == 'cancelled')
                                        <p class="text-danger"><i class="fas fa-times-circle me-2"></i> Đặt phòng đã được hủy.</p>
                                    @elseif ($booking->status == 'refunded')
                                        <p class="text-dark"><i class="fas fa-undo-alt me-2"></i> Đặt phòng đã được hoàn tiền.</p>
                                    @elseif ($booking->status == 'paid')
                                        <p class="text-info"><i class="fas fa-check-circle me-2"></i> Đặt phòng đã được thanh toán, không thể hủy.</p>
                                    @elseif ($booking->status == 'check_in')
                                        <p class="text-primary"><i class="fas fa-sign-in-alt me-2"></i> Đặt phòng đã check-in, không thể hủy.</p>
                                    @elseif ($booking->status == 'check_out')
                                        <p class="text-secondary"><i class="fas fa-sign-out-alt me-2"></i> Đặt phòng đã check-out, không thể hủy.</p>
                                    @else
                                        <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Không thể hủy đơn đặt phòng ở trạng thái hiện tại ({{ $booking->status }}).</p>
                                    @endif
                                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thêm Font Awesome và Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS tùy chỉnh -->
<style>
    .lh-room-inner-heading {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }
    .lh-checkout-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }
    .lh-check-block-content p {
        margin-bottom: 0.5rem;
    }
    .lh-check-block-content .text-muted {
        font-size: 0.9rem;
    }
    .badge {
        padding: 5px 10px;
        font-size: 0.9rem;
        border-radius: 12px;
    }
    .guest-info {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 15px;
    }
    .guest-info:last-child {
        border-bottom: none;
    }
    .lh-side-room {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .lh-checkout {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .btn-danger, .btn-secondary {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    .text-primary {
        color: #007bff !important;
    }
</style>
@endsection
