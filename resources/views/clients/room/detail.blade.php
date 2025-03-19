@extends('layouts.client')

@section('content')
<section class="section-room-detsils padding-tb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-room-details">
                    <div class="lh-main-room">
                        <div class="slider slider-for">
                            @foreach ($roomType->roomTypeImages as $image)
                                <div class="lh-room-details-image">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="room-{{ $loop->index + 1 }}">
                                </div>
                            @endforeach
                            @if ($roomType->roomTypeImages->isEmpty())
                                <div class="lh-room-details-image">
                                    <img src="{{ asset('assets/client/assets/img/room/room-1.jpg') }}" alt="room-1">
                                </div>
                            @endif
                        </div>
                        <div class="slider slider-nav">
                            @foreach ($roomType->roomTypeImages as $image)
                                <div class="lh-room-details-inner">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="room-{{ $loop->index + 1 }}">
                                </div>
                            @endforeach
                            @if ($roomType->roomTypeImages->isEmpty())
                                <div class="lh-room-details-inner">
                                    <img src="{{ asset('assets/client/assets/img/room/room-1.jpg') }}" alt="room-1">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="lh-room-details-contain">
                        <h4 class="lh-room-details-contain-heading">{{ $roomType->name }}</h4>
                        <p>{{ $roomType->description ?? 'Phòng sang trọng với không gian rộng rãi, nội thất hiện đại và đầy đủ tiện nghi.' }}</p>
                        <p><strong>Số phòng còn trống:</strong> {{ $roomType->available_rooms }}</p>
                        <div class="lh-room-details-amenities">
                            <div class="row">
                                <h4 class="lh-room-inner-heading">Tiện Nghi</h4>
                                @if ($roomType->amenities->isNotEmpty())
                                    @foreach ($roomType->amenities->chunk(3) as $amenityChunk)
                                        <div class="col-lg-4 lh-cols-room">
                                            <ul>
                                                @foreach ($amenityChunk as $amenity)
                                                    <li><code>*</code>{{ $amenity->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-lg-4 lh-cols-room">
                                        <ul>
                                            <li><code>*</code>Chưa có tiện nghi</li>

                                        </ul>
                                    </div>

                                @endif
                            </div>
                        </div>
                        <div class="lh-room-details-rules">
                            <div class="lh-room-rules">
                                <h4 class="lh-room-inner-heading">Quy tắc & quy định</h4>
                                <div class="lh-cols-room">
                                    <ul>
                                        @if ($roomType->rulesAndRegulations->isNotEmpty())
                                            @foreach ($roomType->rulesAndRegulations as $rule)
                                                <li><code>*</code>{{ $rule->name }}</li>
                                            @endforeach
                                        @else

                                            <li><code>*</code>Chưa có quy tắc & quy định</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="lh-room-details-review">
                            <div class="lh-room-review">
                                <h4 class="lh-room-inner-heading">Thêm Đánh Giá</h4>
                                <p>Vui lòng chia sẻ trải nghiệm của bạn về phòng này.</p>
                            </div>
                            <form action="#">
                                <div class="lh-room-review-form">
                                    <label>Tên của bạn</label>
                                    <input type="text" name="firstname" class="review-form-control" required>
                                </div>
                                <div class="lh-room-review-form">
                                    <label>Email của bạn</label>
                                    <input type="email" name="email" class="review-form-control" required>
                                </div>
                                <div class="lh-room-review-form">
                                    <label>Bình luận</label>
                                    <textarea class="review-form-control"></textarea>
                                </div>
                                <div class="lh-room-review-form">
                                    <label>Đánh giá</label>
                                    <div class="lh-review-form-rating">
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                    </div>
                                </div>
                                <div class="lh-room-review-buttons">
                                    <button class="lh-buttons result-placeholder" type="submit">
                                        Gửi Đánh Giá
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-duration="3000">
                <div class="lh-side-room">
                    <h4 class="lh-room-inner-heading">Đặt Phòng</h4>
                    <div class="lh-side-reservation">
                        <form action="{{ route('bookings.create') }}" method="GET" id="booking-form">
                            <div class="lh-side-reservation-from">
                                <label>Nhận Phòng</label>
                                <div class="calendar" id="date_1">
                                    <input type="text" name="check_in" class="reservation-form-control datepicker" value="{{ $checkIn }}" readonly>
                                    <i class="ri-calendar-line"></i>
                                </div>
                            </div>
                            <div class="lh-side-reservation-from">
                                <label>Trả Phòng</label>
                                <div class="calendar" id="date_2">
                                    <input type="text" name="check_out" class="reservation-form-control datepicker" value="{{ $checkOut }}" readonly>
                                    <i class="ri-calendar-line"></i>
                                </div>
                            </div>
                            <div class="lh-side-reservation-from">
                                <label>Số Phòng</label>
                                <div class="custom-select reservation-form-control">
                                    <select name="room_quantity" required>
                                        @for ($i = 1; $i <= $roomType->available_rooms; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="lh-side-reservation-from">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Người Lớn</label>
                                        <div class="custom-select reservation-form-control">
                                            <select name="total_guests" required>
                                                @for ($i = 1; $i <= $roomType->max_capacity; $i++)
                                                    <option value="{{ $i }}" {{ $i == $totalGuests ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Trẻ Em</label>
                                        <div class="custom-select reservation-form-control">
                                            <select name="children_count" required>
                                                @for ($i = 0; $i <= $roomType->max_capacity - $totalGuests; $i++)
                                                    <option value="{{ $i }}" {{ $i == $childrenCount ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lh-side-reservation-from ex-service">
                                <h4>Dịch Vụ Bổ Sung</h4>
                                @foreach ($roomType->services as $service)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="{{ $service->id }}" id="service-{{ $service->id }}"
                                            {{ $service->price == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="service-{{ $service->id }}">
                                            {{ $service->name }} ({{ $service->price > 0 ? \App\Helpers\FormatHelper::FormatPrice($service->price) : 'Miễn phí' }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="lh-side-reservation-from">
                                <h4>Giá Phòng</h4>
                                <span>{{ \App\Helpers\FormatHelper::FormatPrice($roomType->price) }} / mỗi phòng</span>
                            </div>
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <div class="lh-side-reservation-from">
                                <div class="lh-side-reservation-from-buttons d-flex">
                                    <button type="submit" class="lh-buttons result-placeholder">
                                        Đặt Ngay
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thêm jQuery và Slick Slider -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function () {
        // Kiểm tra xem jQuery và Slick có được tải không
        if (typeof jQuery === 'undefined') {
            console.error('jQuery không được tải!');
            return;
        }
        if (typeof $.fn.slick === 'undefined') {
            console.error('Slick Slider không được tải!');
            return;
        }

        // Khởi tạo Slick Slider
        $('.slider-for').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.slider-nav'
        });

        $('.slider-nav').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.slider-for',
            dots: true,
            centerMode: true,
            focusOnSelect: true
        });

        // Kiểm tra đăng nhập trước khi đặt phòng
        $('#booking-form').on('submit', function (e) {
            @if (!Auth::check())
                e.preventDefault();
                alert('Vui lòng đăng nhập để đặt phòng!');
                window.location.href = '{{ route("login") }}';
            @endif
        });
    });
</script>
@endsection
