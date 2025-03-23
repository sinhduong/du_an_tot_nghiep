@extends('layouts.client')

@section('content')
<section class="section-room-details padding-tb-100">
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
                            <div class="lh-side-reservation-from">
                                <label>Ngày nhận phòng - trả phòng</label>
                                <div class="calendar">
                                    <input type="text" id="booking_date_range" class="reservation-form-control" value="{{ $checkIn }} - {{ $checkOut }}" readonly>
                                    <i class="ri-calendar-line"></i>
                                    <input type="hidden" name="check_in" id="booking_check_in" value="{{ $checkIn }}">
                                    <input type="hidden" name="check_out" id="booking_check_out" value="{{ $checkOut }}">
                                </div>
                            </div>

                            <div class="lh-side-reservation-from">
                                <label>Số lượng</label>
                                <div class="counter-box">
                                    <div class="counter-item">
                                        <label>Người lớn</label>
                                        <div class="counter-controls">
                                            <button type="button" class="counter-btn minus" data-target="total_guests">-</button>
                                            <input type="text" name="total_guests" class="counter-input" value="{{ $totalGuests }}" readonly>
                                            <button type="button" class="counter-btn plus" data-target="total_guests">+</button>
                                        </div>
                                    </div>
                                    <div class="counter-item">
                                        <label>Trẻ em</label>
                                        <div class="counter-controls">
                                            <button type="button" class="counter-btn minus" data-target="children_count">-</button>
                                            <input type="text" name="children_count" class="counter-input" value="{{ $childrenCount }}" readonly>
                                            <button type="button" class="counter-btn plus" data-target="children_count">+</button>
                                        </div>
                                    </div>
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

                            <div class="lh-side-reservation-from ex-service">
                                <h4>Dịch Vụ Bổ Sung</h4>
                                @if ($roomType->services->isNotEmpty())
                                    @foreach ($roomType->services as $service)
                                        <div class="form-check">
                                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="{{ $service->id }}" id="service-{{ $service->id }}"
                                                {{ $service->price == 0 ? 'checked' : '' }} data-price="{{ $service->price ?? 0 }}">
                                            <label class="form-check-label" for="service-{{ $service->id }}">
                                                {{ $service->name }} ({{ $service->price > 0 ? \App\Helpers\FormatHelper::FormatPrice($service->price) : 'Miễn phí' }})
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p>Không có dịch vụ bổ sung nào.</p>
                                @endif
                            </div>

                            <div class="lh-side-reservation-from">
                                <h4>Chi Tiết Giá</h4>
                                @php
                                    $basePrice = $roomType->total_original_price;
                                    $discountedPrice = $roomType->total_discounted_price;
                                    $discountAmount = $basePrice - $discountedPrice;
                                    $serviceTotal = 0;
                                    $subTotal = $discountedPrice + $serviceTotal;
                                    $taxFee = $subTotal * 0.08;
                                    $totalPrice = $subTotal + $taxFee;
                                @endphp
                                <div class="d-flex justify-content-between">
                                    <p>Giá gốc ({{ $roomCount }} phòng x {{ $nights }} đêm)</p>
                                    <p>VND <span id="base-price-display">{{ number_format($basePrice, 0, ',', '.') }}</span></p>
                                </div>
                                @if ($discountedPrice < $basePrice)
                                    <div class="d-flex justify-content-between">
                                        <p>Giảm giá (Mã: {{ $roomType->promotion_info['code'] }} - {{ $roomType->promotion_info['value'] }}{{ $roomType->promotion_info['type'] === 'percent' ? '%' : 'K' }})</p>
                                        <p>- VND <span id="discount-amount-display">{{ number_format($discountAmount, 0, ',', '.') }}</span></p>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between" id="service-total" style="display: none;">
                                    <p>Dịch vụ bổ sung</p>
                                    <p>VND <span id="service-total-amount">0</span></p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Thuế và phí (8%)</p>
                                    <p>VND <span id="tax-fee-display">{{ number_format($taxFee, 0, ',', '.') }}</span></p>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h4>Tổng cộng</h4>
                                    <h5 class="text-danger" id="total-price-display">VND {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                                </div>
                                <p class="text-muted">Đã bao gồm thuế và phí</p>
                            </div>

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <input type="hidden" name="base_price" id="base-price-input" value="{{ $basePrice }}">
                            <input type="hidden" name="discounted_price" id="discounted-price-input" value="{{ $discountedPrice }}">
                            <input type="hidden" name="discount_amount" id="discount-amount-input" value="{{ $discountAmount }}">
                            <input type="hidden" name="service_total" id="service-total-input" value="0">
                            <input type="hidden" name="tax_fee" id="tax-fee-input" value="{{ $taxFee }}">
                            <input type="hidden" name="total_price" id="total-price-input" value="{{ $totalPrice }}">

                            <div class="lh-side-reservation-from">
                                <div class="lh-side-reservation-from-buttons d-flex">
                                    <button type="submit" class="lh-buttons result-placeholder">Đặt Ngay</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thêm Flatpickr và Slick theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    #total-price-display, #service-total-amount, #tax-fee-display {
        font-weight: bold;
    }
    .ex-service .form-check {
        margin-bottom: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Kiểm tra jQuery có sẵn không
    if (typeof jQuery === 'undefined') {
        console.error('jQuery không được tải. Vui lòng kiểm tra layout.');
        return;
    }

    // Khởi tạo Flatpickr
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#booking_date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: ["{{ $checkIn }}", "{{ $checkOut }}"],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    document.getElementById('booking_check_in').value = selectedDates[0].toISOString().split('T')[0];
                    document.getElementById('booking_check_out').value = selectedDates[1].toISOString().split('T')[0];
                    updatePrice();
                }
            }
        });
    } else {
        console.warn('Flatpickr không được tải.');
    }

    // Khởi tạo Slick Slider
    if (jQuery.fn.slick) {
        try {
            jQuery('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.slider-nav'
            });
            jQuery('.slider-nav').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.slider-for',
                dots: true,
                centerMode: true,
                focusOnSelect: true
            });
        } catch (error) {
            console.error('Lỗi khi khởi tạo Slick Slider:', error);
        }
    } else {
        console.warn('Slick Slider không được tải hoặc jQuery không hoạt động.');
    }

    // Constants
    const CHILDREN_FREE_LIMIT = {{ $roomType->children_free_limit }};
    const ORIGINAL_PRICE_PER_NIGHT = {{ $roomType->price }};
    const DISCOUNTED_PRICE_PER_NIGHT = {{ $roomType->discounted_price_per_night ?? $roomType->price }};
    const MAX_CAPACITY = {{ $roomType->max_capacity }};
    const MAX_ROOMS = {{ $roomType->available_rooms }};

    // Hàm cập nhật giá
    function updatePrice() {
        const checkIn = new Date(document.getElementById('booking_check_in').value);
        const checkOut = new Date(document.getElementById('booking_check_out').value);
        const days = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
        const roomCount = parseInt(document.querySelector('input[name="room_quantity"]').value) || 1;
        const totalGuests = parseInt(document.querySelector('input[name="total_guests"]').value) || 1;
        const childrenCount = parseInt(document.querySelector('input[name="children_count"]').value) || 0;

        // Tính giá cơ bản
        const basePrice = ORIGINAL_PRICE_PER_NIGHT * roomCount * days;
        const discountedPrice = DISCOUNTED_PRICE_PER_NIGHT * roomCount * days;
        const discountAmount = basePrice - discountedPrice;

        // Tính tổng giá dịch vụ
        let serviceTotal = 0;
        const checkedServices = document.querySelectorAll('#booking-form .service-checkbox:checked');
        checkedServices.forEach(function(checkbox) {
            const price = parseFloat(checkbox.getAttribute('data-price')) || 0;
            if (!isNaN(price)) {
                serviceTotal += price;
            }
        });

        // Tính tổng phụ, thuế và tổng cộng
        const subTotal = discountedPrice + serviceTotal;
        const taxFee = subTotal * 0.08;
        const totalPrice = subTotal + taxFee;

        // Cập nhật giao diện
        document.getElementById('base-price-display').textContent = basePrice.toLocaleString('vi-VN');
        const discountDisplay = document.getElementById('discount-amount-display');
        if (discountDisplay) {
            discountDisplay.textContent = discountAmount.toLocaleString('vi-VN');
        }
        document.getElementById('service-total-amount').textContent = serviceTotal.toLocaleString('vi-VN');
        document.getElementById('service-total').style.display = serviceTotal > 0 ? 'flex' : 'none';
        document.getElementById('tax-fee-display').textContent = taxFee.toLocaleString('vi-VN');
        document.getElementById('total-price-display').textContent = 'VND ' + totalPrice.toLocaleString('vi-VN');

        // Cập nhật input ẩn
        document.getElementById('base-price-input').value = basePrice;
        document.getElementById('discounted-price-input').value = discountedPrice;
        document.getElementById('discount-amount-input').value = discountAmount;
        document.getElementById('service-total-input').value = serviceTotal;
        document.getElementById('tax-fee-input').value = taxFee;
        document.getElementById('total-price-input').value = totalPrice;

        console.log('Days:', days, 'Room Count:', roomCount, 'Service Total:', serviceTotal, 'Sub Total:', subTotal, 'Tax Fee:', taxFee, 'Total Price:', totalPrice);
    }

    // Logic counter
    const counterButtons = document.querySelectorAll('.counter-btn');
    counterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const input = document.querySelector(`input[name="${target}"]`);
            let value = parseInt(input.value) || 0;

            const totalGuests = parseInt(document.querySelector('input[name="total_guests"]').value) || 0;
            const childrenCount = parseInt(document.querySelector('input[name="children_count"]').value) || 0;
            const totalPeople = totalGuests + childrenCount;

            if (this.classList.contains('plus')) {
                if (target === 'total_guests' || target === 'children_count') {
                    if (totalPeople < MAX_CAPACITY) {
                        value++;
                    } else {
                        alert('Tổng số người vượt quá sức chứa tối đa của loại phòng này (' + MAX_CAPACITY + ' người).');
                    }
                } else if (target === 'room_quantity') {
                    if (value < MAX_ROOMS) {
                        value++;
                    } else {
                        alert('Không đủ số phòng còn trống. Hiện tại chỉ còn ' + MAX_ROOMS + ' phòng.');
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

            if (target === 'children_count' && value > CHILDREN_FREE_LIMIT) {
                alert(`Số trẻ em vượt quá giới hạn miễn phí (${CHILDREN_FREE_LIMIT}). Phí bổ sung có thể được áp dụng.`);
            }

            updatePrice();
        });
    });

    // Sự kiện checkbox dịch vụ
    const serviceCheckboxes = document.querySelectorAll('#booking-form .service-checkbox');
    serviceCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            console.log('Service Checkbox Changed:', this.checked, 'Price:', this.getAttribute('data-price'));
            updatePrice();
        });
    });

    // Kiểm tra đăng nhập
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        @if (!Auth::check())
            e.preventDefault();
            alert('Vui lòng đăng nhập để đặt phòng!');
            window.location.href = '{{ route("login") }}';
        @endif
    });

    // Cập nhật giá ban đầu
    updatePrice();
});
</script>
@endsection
