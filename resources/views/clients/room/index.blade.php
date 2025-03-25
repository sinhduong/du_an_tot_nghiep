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
                            <img src="{{ $imagePath }}" alt="{{ $roomType->name }}">
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
                                                <h4>{{ \App\Helpers\FormatHelper::FormatPrice($roomType->price) }} /<span>Mỗi đêm</span></h4>
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
