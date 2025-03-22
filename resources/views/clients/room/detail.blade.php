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
                    </div>
                </div>
            </div>

            <div class="col-lg-4" data-aos="fade-up" data-aos-duration="3000">
                <div class="lh-side-room">
                    <h4 class="lh-room-inner-heading">Đặt Phòng</h4>
                    <div class="lh-side-reservation">
                        @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                        @endif
                        <form action="{{ route('bookings.create') }}" method="GET" id="booking-form">
                            <!-- Date Range Picker -->
                            <div class="lh-side-reservation-from">
                                <label>Ngày nhận phòng - trả phòng</label>
                                <div class="calendar">
                                    <input type="text" id="booking_date_range" class="reservation-form-control" value="{{ $checkIn }} - {{ $checkOut }}" readonly>
                                    <i class="ri-calendar-line"></i>
                                    <input type="hidden" name="check_in" id="booking_check_in" value="{{ $checkIn }}">
                                    <input type="hidden" name="check_out" id="booking_check_out" value="{{ $checkOut }}">
                                </div>
                            </div>

                            <!-- Counter for Rooms, Adults, and Children -->
                            <div class="lh-side-reservation-from">
                                <label>Số lượng</label>
                                <div class="counter-box">

                                    <!-- Adults -->
                                    <div class="counter-item">
                                        <label>Người lớn</label>
                                        <div class="counter-controls">
                                            <button type="button" class="counter-btn minus" data-target="total_guests">-</button>
                                            <input type="text" name="total_guests" class="counter-input" value="{{ $totalGuests }}" readonly>
                                            <button type="button" class="counter-btn plus" data-target="total_guests">+</button>
                                        </div>
                                    </div>
                                    <!-- Children -->
                                    <div class="counter-item">
                                        <label>Trẻ em</label>
                                        <div class="counter-controls">
                                            <button type="button" class="counter-btn minus" data-target="children_count">-</button>
                                            <input type="text" name="children_count" class="counter-input" value="{{ $childrenCount }}" readonly>
                                            <button type="button" class="counter-btn plus" data-target="children_count">+</button>
                                        </div>
                                    </div>
                                     <!-- Rooms -->
                                     <div class="counter-item">
                                        <label>Phòng</label>
                                        <div class="counter-controls">
                                            <button type="button" class="counter-btn minus" data-target="room_quantity">-</button>
                                            <input type="text" name="room_quantity" class="counter-input" value="{{ $roomCount }}" readonly>
                                            <button type="button" class="counter-btn plus" data-target="room_quantity">+</button>
                                        </div>
                                    </div>
                                </div>
                                @if ($childrenCount > $roomType->children_free_limit)
                                    <small class="note text-danger">Số trẻ em vượt quá giới hạn miễn phí ({{ $roomType->children_free_limit }}). Phí bổ sung có thể được áp dụng.</small>
                                @endif
                                <small class="note">
                                    <a href="#">Đọc thêm về chính sách đặt vé và lịch cùng với trẻ em</a>
                                </small>
                            </div>

                            <!-- Dịch vụ bổ sung -->
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

                            <!-- Giá phòng -->
                            <div class="lh-side-reservation-from">
                                <h4>Giá Phòng</h4>
                                <span>{{ \App\Helpers\FormatHelper::FormatPrice($roomType->price) }} / mỗi phòng</span>
                            </div>

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <!-- Nút đặt phòng -->
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

<!-- Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Slick Slider CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<!-- Custom CSS for Counter -->
<style>
    .counter-box {
        margin-top: 10px;
    }
    .counter-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .counter-item label {
        font-weight: 500;
    }
    .counter-controls {
        display: flex;
        align-items: center;
    }
    .counter-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ccc;
        background: #f5f5f5;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .counter-input {
        width: 40px;
        text-align: center;
        border: 1px solid #ccc;
        margin: 0 5px;
        padding: 5px;
    }
    .note {
        display: block;
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
</style>

<!-- JavaScript -->
<script>
    $(document).ready(function () {
        // Initialize Flatpickr for date range
        flatpickr("#booking_date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: ["{{ $checkIn }}", "{{ $checkOut }}"],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    document.getElementById('booking_check_in').value = selectedDates[0].toISOString().split('T')[0];
                    document.getElementById('booking_check_out').value = selectedDates[1].toISOString().split('T')[0];
                }
            }
        });

        // Initialize Slick Slider
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

        // Counter logic
        const childrenFreeLimit = {{ $roomType->children_free_limit }};
        document.querySelectorAll('.counter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.querySelector(`input[name="${target}"]`);
                let value = parseInt(input.value);

                const totalGuestsInput = document.querySelector('input[name="total_guests"]');
                const childrenCountInput = document.querySelector('input[name="children_count"]');
                const totalGuests = parseInt(totalGuestsInput.value);
                const childrenCount = parseInt(childrenCountInput.value);
                const maxCapacity = {{ $roomType->max_capacity }};
                const totalPeople = totalGuests + childrenCount;

                if (this.classList.contains('plus')) {
                    if (target === 'total_guests' || target === 'children_count') {
                        if (totalPeople < maxCapacity) {
                            value++;
                        } else {
                            alert('Tổng số người vượt quá sức chứa tối đa của loại phòng này.');
                        }
                    } else if (target === 'room_quantity') {
                        const maxRooms = {{ $roomType->available_rooms }};
                        if (value < maxRooms) {
                            value++;
                        } else {
                            alert('Không đủ số phòng còn trống. Hiện tại chỉ còn ' + maxRooms + ' phòng.');
                        }
                    }
                } else if (this.classList.contains('minus')) {
                    if (target === 'children_count' && value > 0) {
                        value--;
                    } else if ((target === 'total_guests' || target === 'room_quantity') && value > 1) {
                        value--;
                    }
                }

                input.value = value;

                // Check children_free_limit and show warning
                const newChildrenCount = parseInt(document.querySelector('input[name="children_count"]').value);
                if (newChildrenCount > childrenFreeLimit) {
                    alert(`Số trẻ em vượt quá giới hạn miễn phí (${childrenFreeLimit}). Phí bổ sung có thể được áp dụng.`);
                }
            });
        });

        // Check login before booking
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
