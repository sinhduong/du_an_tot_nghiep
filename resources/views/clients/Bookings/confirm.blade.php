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
                        <span class="lh-inner-breadcrumb"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span> / </span>
                        <span><a href="{{ route('bookings.create') }}">Đặt phòng</a></span>
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
                            <p>{{ $service->name }} ({{ $service->price == 0 ? 'Miễn phí' : \App\Helpers\FormatHelper::FormatPrice($service->price) }}) x {{ $serviceQuantities[$service->id] ?? 1 }}</p>
                            @endforeach
                            @endif
                        </div>
                        <div class="lh-check-block-content mb-3">
                            <h4 class="lh-room-inner-heading">Tổng giá</h4>
                            <small class="text-danger">(*) mặc định</small>
                            <div class="d-flex justify-content-between">
                                <p>Giá phòng & dịch vụ ({{ $roomQuantity }} phòng x {{ $days }} đêm)</p>
                                <p id="base-price-display">{{ \App\Helpers\FormatHelper::formatPrice($basePrice + $serviceTotal) }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Thuế và phí (8%)</p>
                                <p id="initial-tax-display">{{ \App\Helpers\FormatHelper::formatPrice(($basePrice + $serviceTotal) * 0.08) }}</p>
                            </div>

                            <div id="discount-section" style="display: {{ $discountAmount > 0 ? 'block' : 'none' }};">
                                <hr>
                                <small class="text-danger">(*) sau khi áp dụng chương trình giảm giá</small>
                                <div class="d-flex justify-content-between">
                                    <p>Giảm trừ của mã giảm giá</p>
                                    <p id="discount-amount">{{ $discountAmount > 0 ? '- ' . \App\Helpers\FormatHelper::formatPrice($discountAmount) : '' }}</p>
                                </div>
                            </div>

                            <div id="voucher-section" style="display: none;">
                                <hr>
                                <small class="text-success">(*) sau khi áp dụng mã voucher</small>
                                <div class="d-flex justify-content-between">
                                    <p>Giảm trừ của mã voucher</p>
                                    <p id="voucher-amount"></p>
                                </div>
                            </div>

                            <div id="after-discount-section" style="display: {{ $discountAmount > 0 ? 'block' : 'none' }};">
                                <div class="d-flex justify-content-between">
                                    <p>Giá phòng & dịch vụ (sau áp mã)</p>
                                    <p id="after-base-price-display">{{ \App\Helpers\FormatHelper::formatPrice($basePrice + $serviceTotal - $discountAmount) }}</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <p>Thuế và phí (8%)</p>
                                    <p id="tax-fee-display">{{ \App\Helpers\FormatHelper::formatPrice($taxFee) }}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <input type="text" id="promotion-code" class="form-control w-50" placeholder="Nhập mã giảm giá">
                                <div>
                                    <button type="button" id="apply-promotion-btn" class="btn btn-outline-primary">Áp dụng</button>
                                    <button type="button" id="cancel-promotion-btn" class="btn btn-outline-danger" style="display: none;">Hủy</button>
                                </div>
                            </div>
                            <div id="promotion-message" class="mt-2"></div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <h5 class="lh-room-inner-heading">Tổng thanh toán</h5>
                                <h5 class="lh-room-inner-heading text-danger" id="total_price_display">{{ \App\Helpers\FormatHelper::formatPrice($totalPrice) }}</h5>
                            </div>

                            <input type="hidden" id="base_price" value="{{ $basePrice }}">
                            <input type="hidden" id="service_total" value="{{ $serviceTotal }}">
                            <input type="hidden" id="total_price" value="{{ $totalPrice }}">
                            <input type="hidden" id="tax_fee" value="{{ $taxFee }}">
                            <input type="hidden" id="sub_total" value="{{ $subTotal }}">
                            <input type="hidden" id="default_discount" value="{{ $discountAmount }}">
                            <input type="hidden" id="sub_total_after_default" value="{{ $basePrice + $serviceTotal - $discountAmount }}">
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
                                    <form id="confirm-form" method="POST" action="{{ route('bookings.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                        <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                        <input type="hidden" name="total_guests" value="{{ $totalGuests }}">
                                        <input type="hidden" name="children_count" value="{{ $childrenCount }}">
                                        <input type="hidden" name="room_quantity" value="{{ $roomQuantity }}">
                                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                        <input type="hidden" name="total_price" id="total_price_input" value="{{ $totalPrice }}">
                                        <input type="hidden" name="special_request" value="{{ request('special_request') }}">
                                        <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                        <input type="hidden" name="service_total" value="{{ $serviceTotal }}">
                                        <input type="hidden" name="tax_fee" id="tax_fee_input" value="{{ $taxFee }}">
                                        <input type="hidden" name="sub_total" value="{{ $subTotal }}">
                                        <input type="hidden" name="guests[0][name]" value="{{ $guestData['name'] }}">
                                        <input type="hidden" name="guests[0][email]" value="{{ $guestData['email'] }}">
                                        <input type="hidden" name="guests[0][phone]" value="{{ $guestData['phone'] }}">
                                        <input type="hidden" name="guests[0][country]" value="{{ $guestData['country'] }}">
                                        <input type="hidden" name="guests[0][relationship]" value="{{ $guestData['relationship'] ?? 'Người ở chính' }}">
                                        <input type="hidden" id="discount_amount_input" name="discount_amount" value="{{ $discountAmount }}">
                                        <input type="hidden" id="promotion_id" name="promotion_id">

                                        <!-- Truyền dịch vụ và số lượng sang bước tiếp theo -->
                                        @if (!empty($selectedServices))
                                        @foreach ($selectedServices as $service)
                                        <input type="hidden" name="services[{{ $service->id }}][id]" value="{{ $service->id }}">
                                        <input type="hidden" name="services[{{ $service->id }}][quantity]" value="{{ $serviceQuantities[$service->id] ?? 1 }}">
                                        <input type="hidden" name="services[{{ $service->id }}][price]" value="{{ $service->price ?? 0 }}">
                                        @endforeach
                                        @endif

                                        <div id="level-payment-section">
                                            <label class="form-label mt-2">Chọn mức thanh toán:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_amount_type" id="payment_full" value="full" checked>
                                                <label class="form-check-label" for="payment_full" selected>Thanh toán toàn bộ</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_amount_type" id="payment_partial" value="partial">
                                                <label class="form-check-label" for="payment_partial">Thanh toán trước {{ $deposit_percentage }}%</label>
                                            </div>
                                        </div>

                                        <div id="method-payment-section">
                                            <label class="form-label mt-2">Phương thức thanh toán:</label>
                                            <!-- <div class="form-check">
                                                    <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment1" value="cash" checked>
                                                    <label class="form-check-label" for="payment1">Thanh toán tại chỗ (Tiền mặt)</label>
                                                </div> -->
                                            <div class="form-check">
                                                <input class="form-check-input payment-method" type="radio" name="payment_method" id="payment2" value="online" checked>
                                                <label class="form-check-label" for="payment2">Thanh toán trực tuyến</label>
                                            </div>
                                        </div>

                                        <div id="online-payment-section" style="display: block; margin-left: 20px;">
                                            <div class="form-check">
                                                <input class="form-check-input online-payment-method" type="radio" name="online_payment_method" id="vnpay" value="vnpay" checked>
                                                <label class="form-check-label" for="vnpay">
                                                    <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/6/0oxhzjmxbksr1686814746087.png" alt="VNPay" class="payment-icon"> VNPay Thanh toán qua VNPay
                                                </label>
                                            </div>
                                        </div>

                                        <div id="payment-instruction" class="mt-3">
                                            <p>Vui lòng thanh toán bằng tiền mặt khi nhận phòng.</p>
                                        </div>

                                        <div id="momo-qr-section" class="mt-3" style="display: none; text-align: center;">
                                            <h4>Thanh toán qua MoMo</h4>
                                            <p>Quét mã QR bằng ứng dụng MoMo để thanh toán:</p>
                                            <div id="momo-qr-code"></div>
                                            <p>Hoặc nhấp vào liên kết để thanh toán:</p>
                                            <a id="momo-pay-link" href="#" class="btn btn-primary" target="_blank">Thanh toán ngay</a>
                                        </div>

                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-primary" id="confirm-button">Hoàn tất đặt phòng</button>
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
    $(document).ready(function() {
        const basePrice = parseFloat($('#base_price').val());
        const serviceTotal = parseFloat($('#service_total').val());
        const defaultDiscount = parseFloat($('#default_discount').val()) || 0;
        const initialBasePrice = basePrice + serviceTotal;
        const subTotalAfterDefault = parseFloat($('#sub_total_after_default').val());
        const initialTax = subTotalAfterDefault * 0.08;
        const initialTotal = subTotalAfterDefault + initialTax;

        let voucherDiscount = 0;

        $('#base-price-display').text(initialBasePrice.toLocaleString('vi-VN') + ' VND');
        $('#initial-tax-display').text(initialTax.toLocaleString('vi-VN') + ' VND');
        $('#total_price_display').text(initialTotal.toLocaleString('vi-VN') + ' VND');
        if (defaultDiscount > 0) {
            $('#after-base-price-display').text(subTotalAfterDefault.toLocaleString('vi-VN') + ' VND');
            $('#tax-fee-display').text(initialTax.toLocaleString('vi-VN') + ' VND');
        }

        $('#confirm-button').prop('disabled', false);

        $('#apply-promotion-btn').on('click', function() {
            const code = $('#promotion-code').val();

            if (!code) {
                $('#promotion-message').html('<p class="text-danger">Vui lòng nhập mã giảm giá.</p>');
                return;
            }

            $.ajax({
                url: '{{ route("bookings.check-promotion") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    code: code,
                    base_price: subTotalAfterDefault,
                    service_total: 0
                },
                success: function(response) {
                    if (response.success) {
                        voucherDiscount = response.discount_amount;
                        const promotionId = response.promotion_id;

                        const subTotalAfterVoucher = subTotalAfterDefault - voucherDiscount;
                        const taxAfterVoucher = subTotalAfterVoucher * 0.08;
                        const totalAfterVoucher = subTotalAfterVoucher + taxAfterVoucher;

                        $('#voucher-amount').text(voucherDiscount.toLocaleString('vi-VN') + '- VND');
                        $('#voucher-section').show();

                        $('#after-base-price-display').text(subTotalAfterVoucher.toLocaleString('vi-VN') + ' VND');
                        $('#tax-fee-display').text(taxAfterVoucher.toLocaleString('vi-VN') + ' VND');
                        $('#after-discount-section').show();

                        $('#total_price_display').text(totalAfterVoucher.toLocaleString('vi-VN') + ' VND');

                        $('#total_price_input').val(totalAfterVoucher);
                        $('#tax_fee_input').val(taxAfterVoucher);
                        $('#discount_amount_input').val(defaultDiscount + voucherDiscount);
                        $('#promotion_id').val(promotionId);

                        $('#confirm-button').prop('disabled', false);
                        $('#cancel-promotion-btn').show();

                        $('#promotion-message').html('<p class="text-success">' + response.message + '</p>');
                    } else {
                        $('#promotion_id').val('');
                        $('#confirm-button').prop('disabled', true);
                        $('#voucher-section').hide();
                        $('#promotion-message').html('<p class="text-danger">' + response.message + '</p>');
                    }
                },
                error: function() {
                    $('#voucher-section').hide();
                    $('#promotion-message').html('<p class="text-danger">Đã có lỗi xảy ra. Vui lòng thử lại.</p>');
                }
            });
        });

        $('#cancel-promotion-btn').on('click', function() {
            $('#voucher-section').hide();
            $('#cancel-promotion-btn').hide();
            $('#promotion-code').val('');
            $('#promotion-message').html('');
            $('#confirm-button').prop('disabled', false);

            const afterDefaultTax = subTotalAfterDefault * 0.08;
            const afterDefaultTotal = subTotalAfterDefault + afterDefaultTax;

            if (defaultDiscount > 0) {
                $('#after-base-price-display').text(subTotalAfterDefault.toLocaleString('vi-VN') + ' VND');
                $('#tax-fee-display').text(afterDefaultTax.toLocaleString('vi-VN') + ' VND');
                $('#after-discount-section').show();
            } else {
                $('#after-discount-section').hide();
            }

            $('#total_price_display').text(afterDefaultTotal.toLocaleString('vi-VN') + ' VND');

            $('#total_price_input').val(afterDefaultTotal);
            $('#tax_fee_input').val(afterDefaultTax);
            $('#discount_amount_input').val(defaultDiscount);

            $('#promotion_id').val('');
            voucherDiscount = 0;
        });

        // $('.payment-method').on('change', function () {
        //     const method = $(this).val();
        //     if (method === 'cash') {
        //         $('#online-payment-section').hide();
        //         $('#payment-instruction p').text('Vui lòng thanh toán bằng tiền mặt khi nhận phòng.');
        //         $('#momo-qr-section').hide();
        //     } else {
        //         $('#online-payment-section').show();
        //         $('#payment-instruction p').text('Vui lòng lưu ý hiện thanh toán qua cổng thanh toán:');
        //         $('#momo-qr-section').hide();
        //     }
        // });
        $('#payment2').on('click', function() {
            const method = $(this).val();

            $('#online-payment-section').show();
            $('#payment-instruction p').text('Vui lòng lưu ý hiện thanh toán qua cổng thanh toán.');
            $('#momo-qr-section').hide();

        });

        $('.online-payment-method').on('change', function() {
            const onlineMethod = $(this).val();
            if (onlineMethod === 'vnpay') {
                $('#payment-instruction p').text('Vui lòng lưu ý hiện thanh toán qua cổng thanh toán: VNPay');
            }
            $('#momo-qr-section').hide();
        });

        $('#confirm-form').on('submit', function(e) {
            e.preventDefault();

            const paymentMethod = $('input[name="payment_method"]:checked').val();
            if (paymentMethod === 'online') {
                const onlineMethod = $('input[name="online_payment_method"]:checked').val();
                if (!onlineMethod) {
                    alert('Vui lòng chọn một cổng thanh toán (VNPay).');
                    return;
                }

                this.submit();
            } else {
                this.submit();
            }
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

    .lh-checkout-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-check {
        margin-bottom: 10px;
    }

    .form-check-label {
        margin-left: 10px;
        display: flex;
        align-items: center;
    }

    .payment-icon {
        width: 30px;
        height: 30px;
        margin-right: 10px;
    }

    #payment-instruction p {
        font-size: 14px;
        color: #555;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }
</style>
@endsection
