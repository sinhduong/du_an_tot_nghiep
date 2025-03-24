@extends('layouts.client')

@section('content')
    <section class="section-banner">
        <div class="row banner-image">
            <div class="banner-overlay"></div>
            <div class="banner-section">
                <div class="lh-banner-contain">
                    <h2>Lịch sử giao dịch</h2>
                    <div class="lh-breadcrumb">
                        <h5>
                            <span class="lh-inner-breadcrumb">
                                <a href="{{ route('home') }}">Trang chủ</a>
                            </span>
                            <span> / </span>
                            <span>
                                <a href="javascript:void(0)">Lịch sử giao dịch</a>
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="booking-section py-5" style="background-color: #fff; position: relative;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <!-- Danh sách giao dịch -->
                    <div class="transaction-list">
                        @forelse ($payments as $payment)
                            <div class="transaction-item card shadow-sm mb-4 position-relative"
                                 style="border: 1px solid #e0e0e0; border-radius: 12px; overflow: visible; transition: all 0.3s ease; position: relative;">
                                <div class="card-body d-flex align-items-center p-3">
                                    <!-- Icon giao dịch -->
                                    <div class="transaction-icon" style="flex: 0 0 50px;">
                                        @if ($payment->method == 'momo')
                                            <img src="https://cdn-icons-png.flaticon.com/512/825/825500.png" alt="MoMo"
                                                 style="width: 40px; height: 40px; object-fit: contain;">
                                        @elseif ($payment->method == 'vnpay')
                                            <img src="https://cdn-icons-png.flaticon.com/512/825/825500.png" alt="VNPay"
                                                 style="width: 40px; height: 40px; object-fit: contain;">
                                        @else
                                            <img src="https://cdn-icons-png.flaticon.com/512/2331/2331971.png"
                                                 alt="Cash" style="width: 40px; height: 40px; object-fit: contain;">
                                        @endif
                                    </div>

                                    <!-- Thông tin giao dịch -->
                                    <div class="transaction-details flex-grow-1 px-4">
                                        <h5 class="mb-1 text-dark">Giao dịch #{{ $payment->id }}</h5>
                                        <p class="mb-1 text-muted">
                                            Ngày giao
                                            dịch: {{ \App\Helpers\FormatHelper::formatDateTime($payment->created_at) }}
                                        </p>
                                        <p class="mb-1 text-muted">
                                            Phương thức:
                                            @php
                                                $methodMapping = [
                                                    'momo' => 'MoMo',
                                                    'vnpay' => 'VNPay',
                                                    'cash' => 'Tiền mặt',
                                                ];
                                            @endphp
                                            {{ $methodMapping[$payment->method] ?? 'Không xác định' }}
                                        </p>
                                        <span
                                            class="status-badge {{ 'status-' . $payment->status }} {{ $payment->status == 'pending' ? 'text-warning' : ($payment->status == 'completed' ? 'text-success' : 'text-danger') }} fw-bold">
                                                @php
                                                    $statusMapping = [
                                                        'pending' => 'Chưa thanh toán',
                                                        'completed' => 'Hoàn thành',
                                                        'failed' => 'Thất bại',
                                                    ];
                                                @endphp
                                            {{ $statusMapping[$payment->status] ?? 'Không xác định' }}
                                            </span>
                                    </div>

                                    <!-- Số tiền -->
                                    <div class="transaction-amount d-flex align-items-center pe-3 position-relative"
                                         style="min-width: 180px; color: #555;">
                                        <div class="">
                                            <h4 class="mb-5 text-dark" style="font-size: 22px;">
                                                VND {{ number_format($payment->amount, 0, ',', '.') }}
                                            </h4>
                                            <a href="{{ route('bookings.show', $payment->booking_id) }}" class="vertical-dots ms-4 mb-5">Xem đơn đặt phòng</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">Bạn chưa có giao dịch nào.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .transaction-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
            border-radius: 4px;
        }

        .booking-section {
            position: relative;
            z-index: 1;
        }

        .dropdown-menu-custom {
            max-height: 100px;
            overflow-y: auto;
        }
    </style>
@endsection
