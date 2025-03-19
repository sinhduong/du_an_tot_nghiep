<!-- resources/views/clients/bookings/create.blade.php -->
@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Đặt phòng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Đặt phòng</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="checkout-page padding-tb-20">
    <div class="container">
        <!-- Thanh tiến trình -->
        <div class="progress-bar-custom mt-4">
            <div class="progress-step active" data-step="1">
                <span class="step-circle">✔</span>
                <span class="step-label">Bạn chọn</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active" data-step="2">
                <span class="step-circle">2</span>
                <span class="step-label">Chi tiết về bạn</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step" data-step="3">
                <span class="step-circle">3</span>
                <span class="step-label">Hoàn tất đặt</span>
            </div>
        </div>
        <div class="row">
            <!-- Phần thông tin đặt phòng (bên trái) -->
            <div class="col-lg-4 check-sidebar" data-aos="fade-up" data-aos-duration="3000">
                <div class="lh-side-room">
                    <div class="lh-side-reservation">
                        <!-- Chi tiết đặt phòng -->
                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Chi tiết đặt phòng của bạn</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nhận phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($checkIn) }}</p>
                                    <p>14:00 - 22:00</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Trả phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($checkOut) }}</p>
                                    <p>06:00 - 12:00</p>
                                </div>
                            </div>
                            <p><strong>Tổng thời gian lưu trú:</strong>
                                @php
                                    $checkInDate = Carbon\Carbon::parse($checkIn);
                                    $checkOutDate = Carbon\Carbon::parse($checkOut);
                                    $days = $checkOutDate->diffInDays($checkInDate);
                                @endphp
                                {{ $days }} đêm
                            </p>
                            <p><strong>Phương thức thanh toán:</strong> <span id="payment-method-display">Thanh toán tại chỗ</span></p>
                        </div>

                        <!-- Bạn đã chọn -->
                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Bạn đã chọn</h4>
                            <p>{{ $roomQuantity }} phòng cho {{ $totalGuests + $childrenCount }} người</p>
                            <p>{{ $roomQuantity }} x {{ $selectedRoomType->name }}</p>
                            @if (!empty($services))
                                <p><strong>Dịch vụ bổ sung:</strong></p>
                                @foreach ($selectedRoomType->services->whereIn('id', $services) as $service)
                                    <p>{{ $service->name }} ({{ \App\Helpers\FormatHelper::FormatPrice($service->price) }}) x {{ request()->input("service_quantity_{$service->id}", 1) }}</p>
                                @endforeach
                            @endif
                        </div>

                        <!-- Tổng giá -->
                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Tổng giá</h4>
                            <div class="d-flex justify-content-between">
                                <p>Giá gốc</p>
                                <p>VND {{ number_format($selectedRoomType->price * $roomQuantity * $days, 0, ',', '.') }}</p>
                            </div>
                            @if (!empty($services))
                                <div class="d-flex justify-content-between">
                                    <p>Dịch vụ bổ sung</p>
                                    <p>
                                        @php
                                            $serviceTotal = 0;
                                            foreach ($selectedRoomType->services->whereIn('id', $services) as $service) {
                                                $quantity = request()->input("service_quantity_{$service->id}", 1);
                                                $serviceTotal += $service->price * $quantity;
                                            }
                                        @endphp
                                        VND {{ number_format($serviceTotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <p id="discount-label">Giảm giá (Mã khuyến mãi)</p>
                                <p id="discount-amount">VND 0</p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="lh-room-inner-heading">Tổng cộng</h5>
                                <h5 class="lh-room-inner-heading text-danger" id="total_price_display">
                                    @php
                                        $basePrice = $selectedRoomType->price * $roomQuantity * $days;
                                        $subTotal = $basePrice + ($serviceTotal ?? 0);
                                        $taxFee = $subTotal * 0.08;
                                        $totalPrice = $subTotal + $taxFee;
                                    @endphp
                                    VND {{ number_format($totalPrice, 0, ',', '.') }}
                                </h5>
                            </div>
                            <p class="text-muted">Đã bao gồm thuế và phí</p>
                        </div>

                        <!-- Thông tin bổ sung -->
                        <div class="lh-check-block-content">
                            <h4 class="lh-room-inner-heading">Thông tin thêm</h4>
                            <p><i class="fas fa-check-circle text-success"></i> Đã bao gồm thuế VAT</p>
                            <p><i class="fas fa-check-circle text-success"></i> 8% Thuế GTGT</p>
                            <p>VND {{ number_format($subTotal * 0.08, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phần thông tin loại phòng (bên phải) -->
            <div class="col-lg-8 check-dash" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-checkout">
                    <div class="lh-checkout-content">
                        <div class="lh-checkout-inner">
                            <div class="lh-checkout-wrap mb-24">

                               <!-- Tiêu đề và ảnh chính -->
                               <div class="d-flex align-items-center mb-4">
                                <div class="me-3">
                                    @php
                                        $mainImage = $selectedRoomType->roomTypeImages->where('is_main', true)->first();
                                    @endphp
                                    @if ($mainImage)
                                        <img src="{{ Storage::url($mainImage->image) }}" alt="{{ $selectedRoomType->name }}" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    @else
                                        <img src="{{ asset('images/default-room.jpg') }}" alt="Default Room Image" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    @endif
                                </div>
                                <div>
                                    <h3 class="lh-checkout-title mb-1">{{ $selectedRoomType->name }}</h3>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-ruler-combined me-1"></i> {{ $selectedRoomType->size }} m² |
                                        <i class="fas fa-bed me-1"></i> @php
                                        $bedTypeMapping = [
                                            'single' => 'Giường đơn',
                                            'double' => 'Giường đôi',
                                            'queen' => 'Giường Queen',
                                            'king' => 'Giường King',
                                            'bunk' => 'Giường tầng',
                                            'sofa' => 'Giường sofa',
                                        ];
                                    @endphp
                                    {{ $bedTypeMapping[$selectedRoomType->bed_type] ?? 'Không xác định' }} |
                                        <i class="fas fa-users me-1"></i> Tối đa {{ $selectedRoomType->max_capacity }} người
                                    </p>
                                </div>
                            </div>

                            <!-- Mô tả loại phòng -->
                            @if ($selectedRoomType->description)
                                <div class="lh-check-block-content mb-4">
                                    <h5 class="lh-room-inner-heading">Mô tả</h5>
                                    <p class="text-muted">{{ $selectedRoomType->description }}</p>
                                </div>
                            @endif

                            <!-- Tiện nghi -->
                            <div class="lh-check-block-content mb-4">
                                <h5 class="lh-room-inner-heading">Tiện nghi</h5>
                                @if ($selectedRoomType->amenities->isNotEmpty())
                                    <div class="row">
                                        @foreach ($selectedRoomType->amenities as $amenity)
                                            <div class="col-md-6 mb-2">
                                                <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> {{ $amenity->name }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có tiện nghi</p>
                                @endif
                            </div>

                            <!-- Quy định -->
                            @if ($selectedRoomType->rulesAndRegulations->isNotEmpty())
                                <div class="lh-check-block-content mb-4">
                                    <h5 class="lh-room-inner-heading">Quy định</h5>
                                    <ul class="list-unstyled">
                                        @foreach ($selectedRoomType->rulesAndRegulations as $rule)
                                            <li class="mb-2"><i class="fas fa-exclamation-circle text-warning me-2"></i> {{ $rule->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                            <!-- Thông tin người đặt -->
                            <!-- Thông tin người đặt -->
                            <div class="lh-checkout-wrap mb-24">
                                <h3 class="lh-checkout-title">Nhập thông tin chi tiết của bạn</h3>
                                <div class="lh-check-block-content">
                                    <p>Vui lòng nhập thông tin của bạn để ký tự tin để nghỉ có thể hiệu quả</p>
                                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Họ (tiếng Anh) *</label>
                                                    <input type="text" name="first_name" class="form-control" value="{{ Auth::user()->first_name ?? old('first_name') }}" placeholder="Nhập họ của bạn" required />
                                                    @error('first_name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Tên (tiếng Anh) *</label>
                                                    <input type="text" name="last_name" class="form-control" value="{{ Auth::user()->last_name ?? old('last_name') }}" placeholder="Nhập tên của bạn" required />
                                                    @error('last_name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Email *</label>
                                                    <input type="email" name="email" class="form-control" value="{{ Auth::user()->email ?? old('email') }}" placeholder="Nhập email của bạn" required />
                                                    <small class="form-text text-muted">Email đặt phòng sẽ được gửi đến địa chỉ này</small>
                                                    @error('email')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Số điện thoại *</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone ?? old('phone') }}" placeholder="Nhập số điện thoại của bạn" required />
                                                    @error('phone')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Quốc gia *</label>
                                                    <select name="country" class="form-control" required>
                                                        <option value="" disabled selected>Chọn quốc gia</option>
                                                        <option value="Vietnam" {{ (Auth::user()->country ?? old('country')) == 'Vietnam' ? 'selected' : '' }}>Việt Nam</option>
                                                        <option value="USA" {{ (Auth::user()->country ?? old('country')) == 'USA' ? 'selected' : '' }}>USA</option>
                                                        <option value="UK" {{ (Auth::user()->country ?? old('country')) == 'UK' ? 'selected' : '' }}>UK</option>
                                                        <option value="Japan" {{ (Auth::user()->country ?? old('country')) == 'Japan' ? 'selected' : '' }}>Japan</option>
                                                        <option value="Singapore" {{ (Auth::user()->country ?? old('country')) == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                                    </select>
                                                    @error('country')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Ngày sinh</label>
                                                    <input type="date" name="birth_date" class="form-control" value="{{ Auth::user()->birth_date ?? old('birth_date') }}" />
                                                    @error('birth_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Giới tính</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="" disabled selected>Chọn giới tính</option>
                                                        <option value="male" {{ (Auth::user()->gender ?? old('gender')) == 'male' ? 'selected' : '' }}>Nam</option>
                                                        <option value="female" {{ (Auth::user()->gender ?? old('gender')) == 'female' ? 'selected' : '' }}>Nữ</option>
                                                        <option value="other" {{ (Auth::user()->gender ?? old('gender')) == 'other' ? 'selected' : '' }}>Khác</option>
                                                    </select>
                                                    @error('gender')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Số CMND/CCCD</label>
                                                    <input type="text" name="id_number" class="form-control" value="{{ Auth::user()->id_number ?? old('id_number') }}" placeholder="Nhập số CMND/CCCD" />
                                                    @error('id_number')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Ảnh CMND/CCCD</label>
                                                    <input type="file" name="id_photo" class="form-control" accept="image/*" />
                                                    @if (Auth::user()->id_photo)
                                                        <small class="form-text text-muted">Ảnh hiện tại: <a href="{{ Storage::url(Auth::user()->id_photo) }}" target="_blank">Xem ảnh</a></small>
                                                    @endif
                                                    @error('id_photo')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Thông tin người ở -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Thông tin người ở</h3>
                                            <div class="lh-check-block-content" id="guest-list">
                                                <div class="guest-form" data-index="0">
                                                    <h5>Người ở 1</h5>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Họ và tên *</label>
                                                                <input type="text" name="guests[0][name]" class="form-control" placeholder="Nhập họ và tên" required />
                                                                @error('guests.0.name')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Số CMND/CCCD</label>
                                                                <input type="text" name="guests[0][id_number]" class="form-control" placeholder="Nhập số CMND/CCCD" />
                                                                @error('guests.0.id_number')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Ngày sinh</label>
                                                                <input type="date" name="guests[0][birth_date]" class="form-control" />
                                                                @error('guests.0.birth_date')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Giới tính</label>
                                                                <select name="guests[0][gender]" class="form-control">
                                                                    <option value="" disabled selected>Chọn giới tính</option>
                                                                    <option value="male">Nam</option>
                                                                    <option value="female">Nữ</option>
                                                                    <option value="other">Khác</option>
                                                                </select>
                                                                @error('guests.0.gender')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Số điện thoại</label>
                                                                <input type="text" name="guests[0][phone]" class="form-control" placeholder="Nhập số điện thoại" />
                                                                @error('guests.0.phone')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="guests[0][email]" class="form-control" placeholder="Nhập email" />
                                                                @error('guests.0.email')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Quan hệ với người đặt</label>
                                                                <input type="text" name="guests[0][relationship]" class="form-control" placeholder="Ví dụ: bạn, gia đình" />
                                                                @error('guests.0.relationship')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Ảnh CMND/CCCD</label>
                                                                <input type="file" name="guests[0][id_photo]" class="form-control" accept="image/*" />
                                                                @error('guests.0.id_photo')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mã giảm giá -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Mã giảm giá</h3>
                                            <div class="lh-check-block-content">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" name="promotion_code" id="promotion_code" class="form-control" placeholder="Nhập mã giảm giá">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" id="apply-promotion" class="btn btn-primary w-100">Áp dụng</button>
                                                    </div>
                                                </div>
                                                <p id="promotion-message" class="mt-2"></p>
                                            </div>
                                        </div>

                                        <!-- Tùy chọn đặc biệt -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="special_request" id="special_request">
                                                <label class="form-check-label" for="special_request">
                                                    Có, bạn muốn nhận được chương trình điểm để tích lũy điểm thưởng
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    Chúng tôi sẽ sử dụng thông tin của bạn để đặt vé và đảm bảo đặt phòng của bạn có thể được xác nhận.
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Thông tin đặt phòng (ẩn) -->
                                        <input type="hidden" name="check_in" id="check_in" value="{{ $checkIn }}">
                                        <input type="hidden" name="check_out" id="check_out" value="{{ $checkOut }}">
                                        <input type="hidden" name="total_guests" id="total_guests" value="{{ $totalGuests }}">
                                        <input type="hidden" name="children_count" id="children_count" value="{{ $childrenCount }}">
                                        <input type="hidden" name="room_quantity" id="room_quantity" value="{{ $roomQuantity }}">
                                        <input type="hidden" name="room_type_id" id="room_type_id" value="{{ $selectedRoomType->id }}">
                                        <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice }}">
                                        <input type="hidden" name="service_plus_status" id="service_plus_status" value="{{ !empty($services) ? 'not_yet_paid' : 'none' }}">
                                        <input type="hidden" name="discount_amount" id="discount_amount" value="0">
                                        @foreach ($services as $serviceId)
                                            <input type="hidden" name="services[]" value="{{ $serviceId }}">
                                            <input type="hidden" name="service_quantity_{{ $serviceId }}" value="{{ request()->input("service_quantity_{$serviceId}", 1) }}">
                                        @endforeach

                                        <!-- Thanh toán -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Phương thức thanh toán</h3>
                                            <div class="lh-check-block-content">
                                                <div class="form-check">
                                                    <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment1" value="on_site" checked>
                                                    <label class="form-check-label" for="payment1">
                                                        Thanh toán tại chỗ (Tiền mặt)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment2" value="online">
                                                    <label class="form-check-label" for="payment2">
                                                        Thanh toán trực tuyến
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lịch thanh toán -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Thời gian ở của bạn</h3>
                                            <div class="lh-check-block-content">
                                                <p><i class="fas fa-check-circle text-success"></i> Phòng sẽ sẵn sàng từ 14:00</p>
                                                <p><i class="fas fa-users"></i> Lên tàu 24 giờ - Lưu trữ có giới hạn khiến bạn cần!</p>
                                            </div>
                                        </div>

                                        <!-- Yêu cầu đặc biệt -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Thêm yêu cầu đặc biệt (không bắt buộc)</h3>
                                            <div class="lh-check-block-content">
                                                <p>Vui lòng ghi chú yêu cầu của bạn tại đây.</p>
                                                <textarea class="form-control" name="special_request" rows="3" placeholder="Nhập yêu cầu của bạn"></textarea>
                                            </div>
                                        </div>

                                        <!-- Nút tiếp theo -->
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Tiếp theo: Chi tiết cuối cùng</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thêm jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script xử lý mã giảm giá và hiển thị phương thức thanh toán -->
<script>
    $(document).ready(function () {
        // Xử lý mã giảm giá
        $('#apply-promotion').on('click', function () {
            const promotionCode = $('#promotion_code').val();
            const basePrice = {{ $selectedRoomType->price * $roomQuantity * $days }};
            const serviceTotal = {{ $serviceTotal ?? 0 }};
            const subTotal = basePrice + serviceTotal;
            const taxFee = subTotal * 0.08;
            let totalPrice = subTotal + taxFee;

            if (promotionCode) {
                $.ajax({
                    url: '{{ route("bookings.check-promotion") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promotion_code: promotionCode,
                        total_price: subTotal
                    },
                    success: function (response) {
                        if (response.success) {
                            const discountAmount = response.discount_amount;
                            $('#discount-amount').text(`- VND ${discountAmount.toLocaleString()}`);
                            $('#discount_amount').val(discountAmount);
                            totalPrice = subTotal - discountAmount + taxFee;
                            $('#total_price_display').text(`VND ${totalPrice.toLocaleString()}`);
                            $('#total_price').val(totalPrice);
                            $('#promotion-message').text('Mã giảm giá đã được áp dụng!').css('color', 'green');
                        } else {
                            $('#discount-amount').text('VND 0');
                            $('#discount_amount').val(0);
                            $('#total_price_display').text(`VND ${totalPrice.toLocaleString()}`);
                            $('#total_price').val(totalPrice);
                            $('#promotion-message').text(response.message).css('color', 'red');
                        }
                    },
                    error: function () {
                        $('#discount-amount').text('VND 0');
                        $('#discount_amount').val(0);
                        $('#total_price_display').text(`VND ${totalPrice.toLocaleString()}`);
                        $('#total_price').val(totalPrice);
                        $('#promotion-message').text('Đã có lỗi xảy ra, vui lòng thử lại.').css('color', 'red');
                    }
                });
            } else {
                $('#discount-amount').text('VND 0');
                $('#discount_amount').val(0);
                $('#total_price_display').text(`VND ${totalPrice.toLocaleString()}`);
                $('#total_price').val(totalPrice);
                $('#promotion-message').text('Vui lòng nhập mã giảm giá.').css('color', 'red');
            }
        });

        // Hiển thị phương thức thanh toán được chọn
        $('.payment-method').on('change', function () {
            const method = $(this).val();
            if (method === 'on_site') {
                $('#payment-method-display').text('Thanh toán tại chỗ (Tiền mặt)');
            } else {
                $('#payment-method-display').text('Thanh toán trực tuyến');
            }
        });
    });
</script>

<!-- Thêm Font Awesome cho các biểu tượng -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- CSS tùy chỉnh cho thanh tiến trình -->
<style>
    .progress-bar-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        width: 120px;
    }

    .progress-step .step-circle {
        width: 30px;
        height: 30px;
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .progress-step.active .step-circle {
        background-color: #007bff;
    }

    .progress-step:not(.active) .step-circle {
        background-color: #ccc;
    }

    .progress-step .step-label {
        font-size: 14px;
        color: #333;
        text-align: center;
    }

    .progress-line {
        flex: 1;
        height: 2px;
        background-color: #007bff;
        margin: 0 10px;
    }
</style>
@endsection
