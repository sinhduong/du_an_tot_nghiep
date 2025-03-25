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
                            <p>{{ $roomQuantity }} x {{ $selectedRoomType->name }}</p>
                            @if (!empty($services))
                                <p><strong>Dịch vụ bổ sung:</strong></p>
                                @foreach ($selectedRoomType->services->whereIn('id', $services) as $service)
                                    <p>{{ $service->name }} ({{ \App\Helpers\FormatHelper::FormatPrice($service->price) }}) x {{ request()->input("service_quantity_{$service->id}", 1) }}</p>
                                @endforeach
                            @endif
                        </div>

                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Tổng giá</h4>
                            <div class="d-flex justify-content-between">
                                <p>Giá gốc</p>
                                <p>VND {{ number_format($basePrice, 0, ',', '.') }}</p>
                            </div>
                            @if (!empty($services))
                                <div class="d-flex justify-content-between">
                                    <p>Dịch vụ bổ sung</p>
                                    <p>VND {{ number_format($serviceTotal, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="lh-room-inner-heading">Tổng cộng</h5>
                                <h5 class="lh-room-inner-heading text-danger" id="total_price_display">VND {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                            </div>
                            <p class="text-muted">Đã bao gồm thuế và phí</p>
                        </div>

                        <div class="lh-check-block-content">
                            <h4 class="lh-room-inner-heading">Thông tin thêm</h4>
                            <p><i class="fas fa-check-circle text-success"></i> Đã bao gồm thuế VAT</p>
                            <p><i class="fas fa-check-circle text-success"></i> 8% Thuế GTGT</p>
                            <p>VND {{ number_format($taxFee, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 check-dash" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-checkout">
                    <div class="lh-checkout-content">
                        <div class="lh-checkout-inner">
                            <div class="lh-checkout-wrap mb-24">
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

                            <h3 class="lh-checkout-title">Nhập thông tin chi tiết của bạn</h3>
                            <div class="lh-check-block-content">
                                <div class="lh-checkout-wrap mb-24">
                                    <h3 class="lh-checkout-title">Thông tin người đặt</h3>
                                    <form action="{{ route('bookings.confirm') }}" method="POST" id="booking-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Họ Tên*</label>
                                                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name ?? old('name') }}" placeholder="Nhập họ của bạn" required />
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
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
                                                        <option value="Singapore" {{ (Auth::user()->country ?? old('country')) == 'Singapore' ? 'selected' : '' }}>Singapore</option>                                                    </select>
                                                    @error('country')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Ngày sinh</label>
                                                    @if (Auth::user()->birth_date)
                                                        <input type="text" name="birth_date" class="form-control" value="{{ \Carbon\Carbon::parse(Auth::user()->birth_date)->format('d/m/Y') }}" />
                                                    @else
                                                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}" />
                                                    @endif
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
                                        </div>

                                        <!-- Thông tin người ở -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Thông tin người ở</h3>
                                            <div class="lh-check-block-content" id="guest-list">
                                                <!-- Form người ở đầu tiên -->
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
                                                                <label class="form-label">Ảnh CCCD</label>
                                                                <input type="file" name="guests[0][id_photo]" class="form-control" accept="image/*" />
                                                                @error('guests.0.id_photo')
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
                                                                <label class="form-label">Mối quan hệ với người đặt</label>
                                                                <select name="guests[0][relationship]" class="form-control">
                                                                    <option value="" disabled selected>Chọn mối quan hệ</option>
                                                                    <option value="Vợ/Chồng">Vợ/Chồng</option>
                                                                    <option value="Con">Con</option>
                                                                    <option value="Bạn">Bạn</option>
                                                                    <option value="Đồng nghiệp">Đồng nghiệp</option>
                                                                    <option value="Khác">Khác</option>
                                                                </select>
                                                                @error('guests.0.relationship')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary mt-3" id="add-guest-btn">
                                                <i class="fas fa-plus me-2"></i> Thêm người ở
                                            </button>
                                            <input type="hidden" id="guest-count" value="1">
                                        </div>

                                        <!-- Thông tin đặt phòng (ẩn) -->
                                        <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                        <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                        <input type="hidden" name="total_guests" value="{{ $totalGuests }}">
                                        <input type="hidden" name="children_count" value="{{ $childrenCount }}">
                                        <input type="hidden" name="room_quantity" value="{{ $roomQuantity }}">
                                        <input type="hidden" name="room_type_id" value="{{ $selectedRoomType->id }}">
                                        <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice }}">
                                        <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                        <input type="hidden" name="service_total" value="{{ $serviceTotal }}">
                                        @foreach ($services as $serviceId)
                                            <input type="hidden" name="services[]" value="{{ $serviceId }}">
                                            <input type="hidden" name="service_quantity_{{ $serviceId }}" value="{{ request()->input("service_quantity_{$serviceId}", 1) }}">
                                        @endforeach

                                        <!-- Yêu cầu đặc biệt -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <h3 class="lh-checkout-title">Yêu cầu đặc biệt (không bắt buộc)</h3>
                                            <div class="lh-check-block-content">
                                                <textarea class="form-control" name="special_request" rows="3" placeholder="Nhập yêu cầu của bạn"></textarea>
                                            </div>
                                        </div>

                                        <!-- Điều khoản và điều kiện -->
                                        <div class="lh-checkout-wrap mb-24">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    Tôi đã đọc và đồng ý với các điều khoản và điều kiện.
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Nút tiếp theo -->
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Tiếp theo: Hoàn tất đặt phòng</button>
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

<!-- Thêm Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- CSS tùy chỉnh -->
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

<!-- JavaScript cho thêm form động -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addGuestBtn = document.getElementById('add-guest-btn');
        const guestList = document.getElementById('guest-list');
        let guestCount = parseInt(document.getElementById('guest-count').value);
        const maxGuests = {{ $totalGuests + $childrenCount }};

        addGuestBtn.addEventListener('click', function () {
            if (guestCount >= maxGuests) {
                alert('Bạn đã đạt số lượng người ở tối đa (' + maxGuests + ' người).');
                return;
            }

            guestCount++;
            document.getElementById('guest-count').value = guestCount;

            // Tạo HTML cho form mới
            const index = guestCount - 1;
            const newForm = `
                <div class="guest-form" data-index="${index}">
                    <h5>Người ở ${index + 1}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="guests[${index}][name]" class="form-control" placeholder="Nhập họ và tên" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số CMND/CCCD</label>
                                <input type="text" name="guests[${index}][id_number]" class="form-control" placeholder="Nhập số CMND/CCCD" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ảnh CCCD</label>
                                <input type="file" name="guests[${index}][id_photo]" class="form-control" accept="image/*" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="guests[${index}][birth_date]" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="guests[${index}][gender]" class="form-control">
                                    <option value="" disabled selected>Chọn giới tính</option>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="guests[${index}][phone]" class="form-control" placeholder="Nhập số điện thoại" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="guests[${index}][email]" class="form-control" placeholder="Nhập email" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mối quan hệ với người đặt</label>
                                <select name="guests[${index}][relationship]" class="form-control">
                                    <option value="" disabled selected>Chọn mối quan hệ</option>
                                    <option value="Vợ/Chồng">Vợ/Chồng</option>
                                    <option value="Con">Con</option>
                                    <option value="Bạn">Bạn</option>
                                    <option value="Đồng nghiệp">Đồng nghiệp</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Thêm form mới vào danh sách
            guestList.insertAdjacentHTML('beforeend', newForm);
        });
    });
</script>
@endsection
