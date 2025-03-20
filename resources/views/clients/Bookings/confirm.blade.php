<!-- resources/views/clients/bookings/confirm.blade.php -->
@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Hoàn tất đặt phòng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="{{ route('bookings.create') }}">Đặt phòng</a>
                        </span>
                        <span> / </span>
                        <span>Hoàn tất</span>
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
                <span class="step-circle">✔</span>
                <span class="step-label">Chi tiết về bạn</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step active" data-step="3">
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
                                    <p>06:00 - 12:00</p>
                                </div>
                            </div>
                            <p><strong>Tổng thời gian lưu trú:</strong> {{ $days }} đêm</p>
                        </div>

                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Bạn đã chọn</h4>
                            <p>{{ request('room_quantity') }} phòng cho {{ request('total_guests') + request('children_count') }} người</p>
                            <p>{{ request('room_quantity') }} x {{ $roomType->name }}</p>
                            @if (!empty($selectedServices))
                                <p><strong>Dịch vụ bổ sung:</strong></p>
                                @foreach ($selectedServices as $service)
                                    <p>{{ $service->name }} ({{ \App\Helpers\FormatHelper::FormatPrice($service->price) }}) x {{ request("service_quantity_{$service->id}", 1) }}</p>
                                @endforeach
                            @endif
                        </div>
                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Tổng giá</h4>
                            <div class="d-flex justify-content-between">
                                <p>Giá gốc</p>
                                <p>VND {{ number_format($basePrice, 0, ',', '.') }}</p>
                            </div>
                            @if (!empty($selectedServices))
                                <div class="d-flex justify-content-between">
                                    <p>Dịch vụ bổ sung</p>
                                    <p>VND {{ number_format($serviceTotal, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <p>Giảm giá (Mã khuyến mãi)</p>
                                <p id="discount-amount">- VND {{ number_format($discountAmount, 0, ',', '.') }}</p>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="lh-room-inner-heading">Tổng cộng</h5>
                                <h5 class="lh-room-inner-heading text-danger" id="total_price_display">VND {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                            </div>
                            <p class="text-muted">Đã bao gồm thuế và phí (VND {{ number_format($taxFee, 0, ',', '.') }})</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 check-dash" data-aos="fade-up" data-aos-duration="2000">
                <div class="lh-checkout">
                    <div class="lh-checkout-content">
                        <div class="lh-checkout-inner">
                            <div class="lh-checkout-wrap mb-24">
                                <h3 class="lh-checkout-title">Phương thức thanh toán</h3>
                                <div class="lh-check-block-content">
                                    <form action="{{ route('bookings.store') }}" method="POST" id="confirm-form">
                                        @csrf
                                        <!-- Truyền tất cả dữ liệu từ bước 2 -->
                                        <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                        <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                        <input type="hidden" name="total_guests" value="{{ request('total_guests') }}">
                                        <input type="hidden" name="children_count" value="{{ request('children_count') }}">
                                        <input type="hidden" name="room_quantity" value="{{ request('room_quantity') }}">
                                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                        <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                                        <input type="hidden" name="discount_amount" value="{{ $discountAmount }}">
                                        <input type="hidden" name="special_request" value="{{ request('special_request') }}">
                                        <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                        <input type="hidden" name="service_total" value="{{ $serviceTotal }}">
                                        @foreach (request('guests', []) as $index => $guest)
                                            <input type="hidden" name="guests[{{$index}}][name]" value="{{ $guest['name'] }}">
                                            <input type="hidden" name="guests[{{$index}}][id_number]" value="{{ $guest['id_number'] ?? '' }}">
                                            <input type="hidden" name="guests[{{$index}}][birth_date]" value="{{ $guest['birth_date'] ?? '' }}">
                                            <input type="hidden" name="guests[{{$index}}][gender]" value="{{ $guest['gender'] ?? '' }}">
                                            <input type="hidden" name="guests[{{$index}}][phone]" value="{{ $guest['phone'] ?? '' }}">
                                            <input type="hidden" name="guests[{{$index}}][email]" value="{{ $guest['email'] ?? '' }}">
                                            <input type="hidden" name="guests[{{$index}}][relationship]" value="{{ $guest['relationship'] ?? '' }}">
                                        @endforeach
                                        @foreach ($selectedServices as $service)
                                            <input type="hidden" name="services[]" value="{{ $service->id }}">
                                            <input type="hidden" name="service_quantity_{{ $service->id }}" value="{{ request("service_quantity_{$service->id}", 1) }}">
                                        @endforeach

                                        <div class="form-check">
                                            <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment1" value="on_site" checked>
                                            <label class="form-check-label" for="payment1">Thanh toán tại chỗ (Tiền mặt)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment2" value="online">
                                            <label class="form-check-label" for="payment2">Thanh toán trực tuyến</label>
                                        </div>

                                        <div id="online-payment-section" style="display: none;">
                                            <p>Vui lòng thực hiện thanh toán qua cổng thanh toán:</p>
                                            <button type="button" class="btn btn-success mt-2" id="process-payment-btn">Thanh toán ngay</button>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary">Hoàn tất đặt phòng</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.payment-method').on('change', function () {
            const method = $(this).val();
            if (method === 'on_site') {
                $('#online-payment-section').hide();
            } else {
                $('#online-payment-section').show();
            }
        });

        $('#process-payment-btn').on('click', function () {
            alert('Đây là giả lập thanh toán trực tuyến. Vui lòng tích hợp cổng thanh toán thực tế.');
        });
    });
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
