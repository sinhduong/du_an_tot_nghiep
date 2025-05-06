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
                            <span class="lh-inner-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a></span>
                            <span> / </span>
                            <span><a href="javascript:void(0)">Đặt phòng</a></span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout-page padding-tb-20">
        <div class="container">
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
                <div class="col-lg-4 check-sidebar" data-aos="fade-up" data-aos-duration="3000">
                    <div class="lh-side-room">
                        <div class="lh-side-reservation">
                            <div class="lh-check-block-content mb-3">
                                <h4 class="lh-room-inner-heading">Chi tiết đặt phòng của bạn</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nhận phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($checkIn) }}</p>
                                        <p>14:00 - 22:00</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Trả phòng:</strong> {{ \App\Helpers\FormatHelper::FormatDate($checkOut) }}</p>
                                        <p>Trước 12:00</p>
                                    </div>
                                </div>
                                <p><strong>Tổng thời gian lưu trú:</strong> {{ $days }} đêm</p>
                            </div>

                            <div class="lh-check-block-content mb-3">
                                <h4 class="lh-room-inner-heading">Bạn đã chọn</h4>
                                <p>{{ $roomQuantity }} phòng cho {{ $totalGuests + $childrenCount }} người</p>
                                <p>{{ $roomQuantity }} x {{ $roomType->name }}</p>
                                @if (!empty($selectedServices))
                                    <p><strong>Dịch vụ bổ sung:</strong></p>
                                    @foreach ($selectedServices as $service)
                                        <p>{{ $service['name'] }} ({{ $service['price'] == 0 ? 'Miễn phí' : \App\Helpers\FormatHelper::FormatPrice($service['price']) }}) x {{ $service['quantity'] }}</p>
                                    @endforeach
                                @endif
                            </div>

                            <div class="lh-check-block-content mb-3">
                                <h4 class="lh-room-inner-heading">Tổng giá</h4>
                                <div class="d-flex justify-content-between">
                                    <p>Giá gốc ({{ $roomQuantity }} phòng x {{ $days }} đêm)</p>
                                    <p>{{ \App\Helpers\FormatHelper::formatPrice($basePrice) }}</p>
                                </div>
                                @if ($discountAmount > 0)
                                    <div class="d-flex justify-content-between">
                                        <p>Giảm giá</p>
                                        <p>- {{ \App\Helpers\FormatHelper::formatPrice($discountAmount) }}</p>
                                    </div>
                                @endif
                                @if ($serviceTotal > 0)
                                    <div class="d-flex justify-content-between">
                                        <p>Dịch vụ bổ sung</p>
                                        <p>{{ \App\Helpers\FormatHelper::formatPrice($serviceTotal) }}</p>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <p>Thuế và phí (8%)</p>
                                    <p>{{ \App\Helpers\FormatHelper::formatPrice($taxFee) }}</p>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5 class="lh-room-inner-heading">Tổng cộng</h5>
                                    <h5 class="lh-room-inner-heading text-danger" id="total_price_display">{{ \App\Helpers\FormatHelper::formatPrice($totalPrice) }}</h5>
                                </div>
                                <p class="text-muted">Đã bao gồm thuế và phí</p>
                            </div>

                            <div class="lh-check-block-content">
                                <h4 class="lh-room-inner-heading">Thông tin thêm</h4>
                                <p><i class="fas fa-check-circle text-success"></i> Đã bao gồm thuế VAT</p>
                                <p><i class="fas fa-check-circle text-success"></i> 8% Thuế GTGT</p>
                                <p>{{ \App\Helpers\FormatHelper::formatPrice($taxFee) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 check-dash" data-aos="fade-up" data-aos-duration="2000">
                    <div class="lh-check-block-content">
                        <div class="lh-checkout-wrap mb-24">
                            <form action="{{ route('bookings.confirm') }}" method="POST" id="booking-form" enctype="multipart/form-data">
                                @csrf
                                <div class="lh-checkout-wrap mb-24">
                                    <h3 class="lh-checkout-title">Thông tin người đặt</h3>
                                    <div class="lh-check-block-content">
                                        <p><strong>Email:</strong> {{ $user->email ?? '' }} (Tài khoản đang đăng nhập)</p>
                                    </div>
                                </div>

                                <div class="lh-checkout-wrap mb-24">
                                    <h3 class="lh-checkout-title">Thông tin chi tiết của bạn (Người ở chính)</h3>
                                    @php
                                        $user = Auth::user();
                                    @endphp

                                    <div class="lh-check-block-content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Họ và tên <span class="text-danger">*</span> </label>
                                                    <input type="text" name="guest[name]" class="form-control"
                                                        value="{{ old('guest.name', $user->name ?? '') }}"
                                                        placeholder="Nhập họ và tên" required />
                                                    @error('guest.name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span class="text-danger">*</span> </label>
                                                    <input type="email" name="guest[email]" class="form-control"
                                                        value="{{ old('guest.email', $user->email ?? '') }}"
                                                        placeholder="Nhập email" required />
                                                    @error('guest.email')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span> </label>
                                                    <input type="text" name="guest[phone]" class="form-control"
                                                        value="{{ old('guest.phone', $user->phone ?? '') }}"
                                                        placeholder="Nhập số điện thoại" required />
                                                    @error('guest.phone')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Vùng quốc gia <span class="text-danger">*</span> </label>
                                                    <select name="guest[country]" class="form-control" required>
                                                        <option value="">Chọn quốc gia</option>
                                                        <option value="Việt Nam" {{ old('guest.country', $user->country ?? '') == 'Việt Nam' ? 'selected' : '' }}>Việt Nam</option>
                                                        <option value="United States" {{ old('guest.country', $user->country ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                                                        <option value="Japan" {{ old('guest.country', $user->country ?? '') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                                    </select>
                                                    @error('guest.country')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <input type="hidden" name="guest[relationship]" value="Người ở chính">
                                        </div>
                                    </div>
                                </div>

                                <div class="lh-checkout-wrap mb-24">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="me-3">
                                            @php
                                                $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                            @endphp
                                            @if ($mainImage)
                                                <img src="{{ Storage::url($mainImage->image) }}" alt="{{ $roomType->name }}" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                            @else
                                                <img src="{{ asset('images/default-room.jpg') }}" alt="Default Room Image" class="rounded" style="width: 150px; height: 100px; object-fit: cover; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="lh-checkout-title mb-1">{{ $roomType->name }}</h3>
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

                                    @if ($roomType->description)
                                        <div class="lh-check-block-content mb-4">
                                            <h3 class="lh-checkout-title">Mô tả</h3>
                                            <p class="text-muted">{{ $roomType->description }}</p>
                                        </div>
                                    @endif

                                    <div class="lh-check-block-content mb-4">
                                        <h3 class="lh-checkout-title">Tiện nghi</h3>
                                        @if ($roomType->amenities->isNotEmpty())
                                            <div class="row">
                                                @foreach ($roomType->amenities as $amenity)
                                                    <div class="col-md-6 mb-2">
                                                        <p class="mb-0"><i class="fas fa-check-circle text-success me-2"></i> {{ $amenity->name }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted"><i class="fas fa-info-circle me-2"></i> Chưa có tiện nghi</p>
                                        @endif
                                    </div>

                                    @if ($roomType->rulesAndRegulations->isNotEmpty())
                                        <div class="lh-check-block-content mb-4">
                                            <h3 class="lh-checkout-title">Quy tắc & quy định</h3>
                                            <ul class="list-unstyled">
                                                @foreach ($roomType->rulesAndRegulations as $rule)
                                                    <li class="mb-2"><i class="fas fa-exclamation-circle text-warning me-2"></i> {{ $rule->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <div class="lh-checkout-wrap mb-24">
                                    <h3 class="lh-checkout-title">Yêu cầu đặc biệt (không bắt buộc)</h3>
                                    <div class="lh-check-block-content">
                                        <textarea class="form-control" name="special_request" rows="3" placeholder="Nhập yêu cầu của bạn">{{ old('special_request') }}</textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                <input type="hidden" name="total_guests" value="{{ $totalGuests }}">
                                <input type="hidden" name="children_count" value="{{ $childrenCount }}">
                                <input type="hidden" name="room_quantity" value="{{ $roomQuantity }}">
                                <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                                <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                <input type="hidden" name="service_total" value="{{ $serviceTotal }}">
                                <input type="hidden" name="discount_amount" value="{{ $discountAmount }}">

                                <!-- Truyền dịch vụ và số lượng -->
                                @if (!empty($selectedServices))
                                    @foreach ($selectedServices as $service)
                                        <input type="hidden" name="services[{{ $service['id'] }}][id]" value="{{ $service['id'] }}">
                                        <input type="hidden" name="services[{{ $service['id'] }}][quantity]" value="{{ $service['quantity'] }}">
                                        <input type="hidden" name="services[{{ $service['id'] }}][price]" value="{{ $service['price'] }}">
                                    @endforeach
                                @endif

                                <div class="lh-checkout-wrap mb-24">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Tôi đã đọc và đồng ý với các điều khoản và điều kiện.
                                        </label>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Tiếp theo: Hoàn tất đặt phòng</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6-beta3/css/all.min.css">
    <style>
        .progress-bar-custom { display: flex; align-items: center; justify-content: center; margin-bottom: 30px; }
        .progress-step { display: flex; flex-direction: column; align-items: center; position: relative; width: 120px; }
        .progress-step .step-circle { width: 30px; height: 30px; background-color: #007bff; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 5px; }
        .progress-step.active .step-circle { background-color: #007bff; }
        .progress-step:not(.active) .step-circle { background-color: #ccc; }
        .progress-step .step-label { font-size: 14px; color: #333; text-align: center; }
        .progress-line { flex: 1; height: 2px; background-color: #007bff; margin: 0 10px; }
        .form-check { margin-bottom: 10px; }
    </style>
@endsection
