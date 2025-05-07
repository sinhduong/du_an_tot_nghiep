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
                                aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                            @php
                                $mainImage = $roomType->roomTypeImages->where('is_main', true)->first();
                                $imagePath = $mainImage ? asset('storage/' . $mainImage->image) : asset('assets/client/assets/img/room/' . ($index + 1) . '.jpg');
                            @endphp
                            <div style="position: relative;">
                                <img src="{{ $imagePath }}" alt="{{ $roomType->name }}" width="50px" height="100px">
                                @if ($roomType->promotion_info)
                                    <span class="discount-badge">
                                        Giảm {{ $roomType->promotion_info['value'] }}{{ $roomType->promotion_info['type'] === 'percent' ? '%' : ' VND' }}
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
                                                            {{ $roomType->promotion_info['name'] }} - Giảm {{ $roomType->promotion_info['value'] }}{{ $roomType->promotion_info['type'] === 'percent' ? '%' : ' VND' }}
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

<!-- Reviews Section -->
<section class="section-testimonials padding-tb-100">
    <div class="container">
        <div class="row">
            <div class="lh-testimonials-banner" data-aos="fade-up" data-aos-duration="1500">
                <div class="banner">
                    <h2>Đánh giá từ <span>Khách Hàng</span></h2>
                </div>
            </div>
            <div class="col-lg-12" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-slider">
                    @forelse ($reviews as $review)
                        <div class="lh-slide">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-6 p-0 align-self-center">
                                        <div class="lh-testimonials">
                                            <div class="row">
                                                <div class="lh-testimonials-contain">
                                                    <div class="d-flex">
                                                        <div class="lh-testimonials-inner">
                                                            <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('assets/client/assets/img/businessman/businessman-1.jpg') }}"
                                                                 alt="{{ $review->user->name }}" class="businessman">
                                                            <div class="lh-testimonials-name-detalis">
                                                                <h5>{{ $review->user->name }}</h5>
                                                                <span>{{ $review->created_at->format('d/m/Y') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="lh-testimonials-side-image">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="70" height="70" x="0" y="0" viewBox="0 0 32 32" style="enable-background:new 0 0 70 70" xml:space="preserve" class="testimonials svg-img">
                                                            <g>
                                                                <path d="M2.957 16.1v10.612a.5.5 0 0 0 .5.5h10.611a.5.5 0 0 0 .5-.5V16.1a.5.5 0 0 0-.5-.5H7.196a5.945 5.945 0 0 1 5.916-5.437.5.5 0 0 0 .5-.5V6.446a.5.5 0 0 0-.5-.5c-5.6 0-10.155 4.556-10.155 10.155zm9.655-9.14v2.222c-3.592.257-6.436 3.262-6.436 6.919a.5.5 0 0 0 .5.5h6.892v9.61H3.957v-10.11c0-4.88 3.839-8.881 8.655-9.141zM28.106 10.164a.5.5 0 0 0 .5-.5V6.446a.5.5 0 0 0-.5-.5c-5.598 0-10.154 4.556-10.154 10.155v10.61a.5.5 0 0 0 .5.5h10.61a.5.5 0 0 0 .5-.5v-10.61a.5.5 0 0 0-.5-.5H22.19a5.945 5.945 0 0 1 5.916-5.437zm-6.936 5.937a.5.5 0 0 0 .5.5h6.893v9.61h-9.61v-10.11c0-4.88 3.837-8.881 8.653-9.141v2.222c-3.592.257-6.436 3.262-6.436 6.919z" fill="#ed5b31" opacity="1" data-original="#ed5b31" class=""></path>
                                                            </g>
                                                        </svg>
                                                        </div>
                                                    </div>
                                                    <p>{{ $review->comment }}</p>
                                                    <div class="lh-testimonials-holiday">
                                                        <span>Loại phòng: {{ $review->booking->rooms->first()->roomType->name }}</span>
                                                        <div class="lh-star">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="ri-star-fill {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    @if ($review->response)
                                                        <div class="response-section mt-3 p-3 bg-light rounded">
                                                            <h6 class="mb-2 text-dark">
                                                                <i class="fas fa-reply me-2"></i>
                                                                Phản hồi từ khách sạn
                                                            </h6>
                                                            <p class="mb-0">{{ $review->response }}</p>
                                                            <small class="text-muted">
                                                                {{ $review->updated_at->format('d/m/Y H:i') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>Chưa có đánh giá nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .response-section {
        border-left: 3px solid #ed5b31;
        margin-top: 20px;
    }

    .lh-star {
        display: flex;
        gap: 5px;
    }

    .lh-star i {
        font-size: 1.2rem;
    }

    .text-warning {
        color: #ffc107 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }
</style>
