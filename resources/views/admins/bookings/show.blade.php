@extends('layouts.admin')
<style>
    .guest-profile .form-group {
    font-size: 16px; /* Tăng kích thước chữ */
    font-weight: 500; /* Làm đậm chữ một chút */
    color: #333; /* Màu chữ tối hơn */
    background: #f8f9fa; /* Nền nhẹ */
    padding: 10px 15px; /* Thêm khoảng cách */
    border-radius: 8px; /* Bo góc */
    margin-bottom: 8px; /* Khoảng cách giữa các dòng */
    display: flex;
    align-items: center;
}

.guest-profile .form-group::before {
    content: "•"; /* Thêm dấu chấm trước mỗi thông tin */
    margin-right: 8px;
    font-size: 18px;
    color: #007bff;
}
.lh-main-room {
        text-align: center;
    }

    .lh-room-details-image img {
        width: 100%;
        max-width: 600px;
        height: auto;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .lh-room-details-thumbnails {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .lh-room-details-thumbnails img {
        width: 100px;
        height: auto;
        cursor: pointer;
        border-radius: 5px;
        transition: transform 0.3s ease;
    }

    .lh-room-details-thumbnails img:hover {
        transform: scale(1.1);
    }
</style>
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>{{ $title }}</h5>
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    <div class="lh-date-range" title="Date">
                        <span></span>
                    </div>
                </div>
                <div class="filter">
                    <div class="dropdown" title="Filter">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-sound-module-line"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Booking</a></li>
                            <li><a class="dropdown-item" href="#">Revenue</a></li>
                            <li><a class="dropdown-item" href="#">Expence</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-xxl-3 col-xl-4 col-md-12">
                <div class="lh-card-sticky guest-card">
                    <div class="lh-card">
                        <div class="lh-card-content card-default">
                            <div class="guest-profile">
                                <img
                                 style="background-image: url({{ $booking->user->avatar ? assets('upload/avatars/'. $booking->user->avatar) : 'https://dongvat.edu.vn/upload/200x200/2025/01/lam-anh-200x200.webp' }})"
                                src="{{asset('assets/admin/assets/img/user/1.jpg') }}" alt="profile">
                                <h5>{{ $booking->user->name }}</h5>
                            </div>
                            <ul class="list">
                                <li><i class="ri-phone-line"></i><span>{{ $booking->user->phone }}</span></li>
                                <li><i class="ri-mail-line"></i><span>{{ $booking->user->email }}</span></li>
                                <li><i class="ri-map-pin-line"></i><span>{{ $booking->user->address }}</span></li>
                                <li><i class="ri-genderless-line"></i><span>{{ $booking->user->gender }}</span></li>
                                <li><i class=""></i><span>Trạng thái: {{ $booking->user->is_active ? 'Hoạt động' : 'Bị hóa'  }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        {{-- <h4 class="lh-card-title">chi tiết đặt phòng</h4> --}}
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line" data-bs-toggle="tooltip" aria-label="Full Screen" data-bs-original-title="Full Screen"></i></a>
                        </div>
                    </div>


                    <div class="lh-card-content card-default p-3 border rounded">
                        <h5 class="mb-3">Thông tin đặt phòng</h5>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Tên khách hàng:</th>
                                    <td>
                                        <a href="#" class="badge bg-primary text-decoration-none">{{ $booking->user->name }} </a>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>Email khách hàng:</th>
                                    <td>{{ $booking->user->email }}</td>
                                </tr> --}}
                                <tr>
                                    <th>Tên phòng:</th>
                                    <td>
                                        @foreach ($booking->rooms as $room)
                                            <span class="badge bg-primary">
                                                <a href="#" class="badge bg-primary text-decoration-none">
                                                    {{ $room->name }}
                                                </a>
                                            </span>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày check-in:</th>
                                    <td>
                                        <span class="badge bg-primary">{{\App\Helpers\FormatHelper::formatDate($booking->check_int) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày check-out:</th>
                                    <td>
                                        <span class="badge bg-primary">{{\App\Helpers\FormatHelper::formatDate($booking->check_out) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Thanh toán:</th>
                                    <td>
                                        <span> @foreach ($booking->payments as $payment)
                                            {{ $payment->method }}
                                            @endforeach</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Loại phòng:</th>
                                    <td>
                                        <span>
                                            @foreach ($booking->rooms as $rooms)
                                                {{ $rooms->roomType->name }} <br>
                                            @endforeach
                                            @foreach ($booking->rooms as $keyI => $room)
                                            <span>{{ $room->room_number }}{{ $keyI < count($booking->rooms) - 1 ? ', ' : '' }}</span>
                                            @endforeach
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td>
                                        <span>
                                            {{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Số người:</th>
                                    <td>
                                        <span>Người lớn{{ $booking->total_guests }} |  </span>Trẻ em: {{ $booking->children_count }}
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <span>
                                            @php
                                            $bedTypeMapping = [
                                                'pending_confirmation' => 'Chưa xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'paid'  => 'Đã thanh toán',
                                                'check_in'   => 'Đã check in',
                                                'check_out'   => 'Đã checkout',
                                                'cancelled'   => 'Đã hủy',
                                                'refunded'   => 'Đã hoàn tiền',
                                            ];
                                            @endphp

                                                {{ $bedTypeMapping[$booking->status] }}

                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
<!-- SwiperJS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.querySelectorAll('.thumbnail').forEach(img => {
        img.addEventListener('click', function() {
            document.getElementById('mainImage').src = this.src;
        });
    });
</script>
