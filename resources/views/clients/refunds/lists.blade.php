@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Lịch sử hoàn tiền</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>Lịch sử hoàn tiền</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="booking-details padding-tb-20 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đặt phòng</th>
                                        <th>Ngày yêu cầu</th>
                                        <th>Số tiền hoàn</th>
                                        <th>Phí hủy</th>
                                        <th>Trạng thái</th>
                                        <th>Lý do</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($refunds as $refund)
                                    <tr>
                                        <td>
                                            <a href="{{ route('bookings.show', $refund->booking_id) }}">
                                                {{ $refund->booking->booking_code }}
                                            </a>
                                        </td>
                                        <td>{{ $refund->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($refund->amount) }} VNĐ</td>
                                        <td>{{ number_format($refund->cancellation_fee) }} VNĐ</td>
                                        <td>
                                            <span class="badge 
                                                @if($refund->status == 'pending') bg-warning
                                                @elseif($refund->status == 'approved') bg-success
                                                @elseif($refund->status == 'rejected') bg-danger
                                                @else bg-info @endif">
                                                @switch($refund->status)
                                                    @case('pending')
                                                        Đang chờ xử lý
                                                        @break
                                                    @case('approved')
                                                        Đã được phê duyệt
                                                        @break
                                                    @case('rejected')
                                                        Đã bị từ chối
                                                        @break
                                                    @default
                                                        {{ $refund->status }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>{{ $refund->reason ?? 'Không có' }}</td>
                                        <td>
                                            <a href="{{ route('bookings.show', $refund->booking_id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Xem chi tiết">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có yêu cầu hoàn tiền nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $refunds->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 