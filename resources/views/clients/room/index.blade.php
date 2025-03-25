<style>
    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #ff4d4f;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
        z-index: 10;
    }

    .discount-info {
        color: #ff4d4f;
        font-size: 14px;
        margin-top: 5px;
        display: flex;
        align-items: center;
    }

    .discount-info i {
        margin-right: 5px;
    }
</style>

<section class="section-room padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="rooms">
    <div class="container">
        <div class="banner">
            <h2>Chọn Phòng <span> Sang Trọng</span> Của Bạn</h2>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif
        @if ($roomTypes->isEmpty())
            <p>Không có phòng nào phù hợp với yêu cầu của bạn.</p>
        @else
            <nav>
                <div class="nav nav-tabs rooms lh-room" id="nav-tab" role="tablist">
                    @foreach ($roomTypes as $index => $roomType)
                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="nav-{{ $roomType->id }}-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-{{ $roomType->id }}" type="button" role="tab" aria-controls="nav-{{ $roomType->id }}"
                                aria-selected="{{ $index == 0 ? 'true' : 'false' }}" >
                            @php
                                $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                $imagePath = $mainImage ? asset('storage/' . $mainImage->image) : asset('assets/client/assets/img/room/' . ($index + 1) . '.jpg');
                            @endphp
                            <div style="position: relative;">
                                <img src="{{ $imagePath }}" alt="{{ $roomType->name }}">
                                @if ($roomType->promotion_info)
                                    <span class="discount-badge">
                                        Giảm {{ $roomType->promotion_info['value'] }}%
                                    </span>
                                @endif
                            </div>
                            {{ $roomType->name }}
                        </button>
                    @endforeach
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                @foreach ($roomTypes as $index => $roomType)
                    <div class="tab-pane fade {{ $index == 0 ? 'active show' : '' }}" id="nav-{{ $roomType->id }}" role="tabpanel" aria-labelledby="nav-{{ $roomType->id }}-tab">
                        <div class="container">
                            <div class="row p-0 lh-d-block">
                                <div class="col-xl-6 col-lg-12">
                                    <div class="lh-room-contain">
                                        <div class="lh-contain-heading">
                                            <h4>{{ $roomType->name }}</h4>
                                            <div class="lh-room-price">
                                                @if ($roomType->total_discounted_price < $roomType->total_original_price)
                                                    <p style="text-decoration: line-through; color: #888; font-size: 16px;">
                                                        {{ \App\Helpers\FormatHelper::FormatPrice($roomType->total_original_price) }}
                                                    </p>
                                                    <h4 style="color: red; font-weight: bold;">
                                                        {{ \App\Helpers\FormatHelper::FormatPrice($roomType->total_discounted_price) }}
                                                    </h4>
                                                    @if ($roomType->promotion_info)
                                                        <p class="discount-info">
                                                            <i class="ri-coupon-3-line"></i>
                                                            Mã: {{ $roomType->promotion_info['code'] }} - Giảm {{ round($roomType->promotion_info['value'], 2) }}%
                                                        </p>
                                                    @endif
                                                @else
                                                    <h4 style="color: #333; font-weight: bold;">
                                                        {{ \App\Helpers\FormatHelper::FormatPrice($roomType->total_original_price) }}
                                                    </h4>
                                                @endif
                                                <p style="font-size: 14px; color: #555;">
                                                    Chi phí cho {{ $nights }} đêm, {{ $totalGuests + $childrenCount }} khách
                                                </p>
                                                @if ($nights > 1 || $roomCount > 1)
                                                    <p style="font-size: 12px; color: #888;" data-toggle="tooltip" data-placement="top" title="Giá mỗi đêm">
                                                        (Giá mỗi đêm:
                                                        @if ($roomType->discounted_price_per_night < $roomType->price)
                                                            <span style="text-decoration: line-through;">
                                                                {{ \App\Helpers\FormatHelper::FormatPrice($roomType->price) }}
                                                            </span>
                                                            <span style="color: red;">
                                                                {{ \App\Helpers\FormatHelper::FormatPrice($roomType->discounted_price_per_night) }}
                                                            </span>
                                                        @else
                                                            {{ \App\Helpers\FormatHelper::FormatPrice($roomType->price) }}
                                                        @endif
                                                        )
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="lh-room-size d-flex">
                                            <p>{{ $roomType->size }} m² <span>|</span></p>
                                            <p>
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
                                                {{ $bedTypeMapping[$roomType->bed_type] ?? 'Không xác định' }}<span>|</span>
                                            </p>
                                            <p>Tối đa {{ $roomType->max_capacity }} khách</p>
                                        </div>
                                        <p>{{ $roomType->description ?? 'Phòng sang trọng với không gian rộng rãi, nội thất hiện đại và đầy đủ tiện nghi.' }}</p>
                                        <p><strong>Số phòng còn trống:</strong> {{ $roomType->available_rooms }}</p>
                                        <div class="lh-main-features">
                                            <div class="lh-contain-heading">
                                                <h4>Tiện Nghi Phòng</h4>
                                            </div>
                                            <div class="lh-room-features">
                                                <div class="lh-cols-room">
                                                    <ul>
                                                        @foreach ($roomType->amenities->take(3) as $amenity)
                                                            <li>{{ $amenity->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="lh-cols-room">
                                                    <ul>
                                                        @foreach ($roomType->amenities->skip(3)->take(3) as $amenity)
                                                            <li>{{ $amenity->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12 p-0">
                                    <div class="room-img">
                                        @php
                                            $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                            $imagePath = $mainImage ? asset('storage/' . $mainImage->image) : asset('assets/client/assets/img/room/room-' . ($index + 1) . '.jpg');
                                        @endphp
                                        <img src="{{ $imagePath }}" alt="room-img" class="room-image">
                                        <a href="{{ route('room.details', $roomType->id) }}?check_in={{ $checkIn }}&check_out={{ $checkOut }}&total_guests={{ $totalGuests }}&children_count={{ $childrenCount }}&room_count={{ $roomCount }}" class="link"><i class="ri-arrow-right-line"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
