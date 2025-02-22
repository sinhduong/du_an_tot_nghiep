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
            <div class="col-xxl-3 col-xl-4 col-md-12">
                <div class="lh-card-sticky guest-card">
                    <div class="lh-card">
                        <div class="lh-card-content card-default">
                            <div class="guest-profile">
                                <img
                                 style="background-image: url({{ $booking->user->avatar ? assets('upload/avatars/'. $booking->user->avatar) : 'https://dongvat.edu.vn/upload/200x200/2025/01/lam-anh-200x200.webp' }})"
                                src="{{asset('assets/admin/assets/img/user/1.jpg') }}" alt="profile">
                                <h5>{{ $booking->user->name }}</h5>
                                <p>ID: {{ $booking->room_id }}</p>
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
            </div>
            <div class="col-xxl-9 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line" data-bs-toggle="tooltip" aria-label="Full Screen" data-bs-original-title="Full Screen"></i></a>
                        </div>
                    </div>
                    <div class="lh-card-content card-default">
                        <div class="booking-details">
                            <i class="ri-home-8-line"></i>
                            <span>
                                <p>ID: {{ $booking->room_id }}</p>
                                <h6>{{ $booking->room->name }}</h6>
                            </span>
                        </div>
                        <div class="booking-box">

                            <div class="booking-info">
                                <p><i class="ri-user-line"></i>Số người</p>
                                <h6>{{ $booking->room->max_capacity }}</h6>
                            </div>
                            <div class="booking-info">
                                <p><i class="ri-user-line"></i>Person</p>
                                <h6>4 Person</h6>
                            </div>
                            <div class="booking-info">
                                <p><i class="ri-user-line"></i>Person</p>
                                <h6>4 Person</h6>
                            </div>
                            <div class="booking-info">
                                <p><i class="ri-hotel-bed-line"></i>Bed Type</p>
                                <h6>Double</h6>
                            </div>
                            <div class="booking-info">
                                <p><i class="ri-hotel-bed-line"></i>Rooms</p>
                                <h6><span>101</span>, <span>102</span></h6>
                            </div>
                            <div class="booking-info">
                                <p><i class="ri-pass-valid-line"></i>Proof</p>
                                <h6>Pan Card</h6>
                            </div>
                        </div>
                        <div class="facilities-details">
                            <h6 class="lh-card-title">Room Facilities</h6>
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/1.png" alt="facilities">
                                        <p>Air Conditioner</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/2.png" alt="facilities">
                                        <p>LED TV</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/3.png" alt="facilities">
                                        <p>Breakfast</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/4.png" alt="facilities">
                                        <p>GYM</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/5.png" alt="facilities">
                                        <p>Parking</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/6.png" alt="facilities">
                                        <p>Swimming Pool</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/7.png" alt="facilities">
                                        <p>Restaurant</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="facilities-info">
                                        <img src="assets/img/facilities/8.png" alt="facilities">
                                        <p>Game zone</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Chi tiết đặt phòng</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                    class="ri-fullscreen-line" title="Full Screen"></i></a>
                            <div class="lh-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Mã đặt phòng</th>
                                            <th>Tên khách</th>
                                            <th>Tên phòng</th>
                                            <th>Ngày check-in</th>
                                            <th>Ngày check-out</th>
                                            <th>Thanh toán</th>
                                            <th>Thành tiền</th>
                                            <th>Loại phòng</th>
                                            <th>Số người</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $booking->booking_code }}</td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>
                                                <img class="cat-thumb" src="{{ asset('assets/img/room/' . $booking->room->avatar) }}" alt="Room Image">
                                                <span class="name">{{ $booking->room->name }}</span>
                                            </td>
                                            <td>{{\App\Helpers\FormatHelper::formatDate($booking->check_int) }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatDate($booking->check_out)}}</td>
                                            @foreach ($booking->payments as $payment)
                                            @endforeach
                                            <td>{{ $payment->method }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</td>
                                            <td class="type"><span>{{ $booking->room->roomType->name }}</td>
                                            <td class="type"><span>Người lớn{{ $booking->total_guests }} |  </span>Trẻ em: {{ $booking->children_count }}</td>
                                            {{-- <td class="type"><span>VIP : </span>{{ $booking->phong_so }}</td> --}}
                                            <td class="rooms">
                                                {{-- <span class="mem">{{ $booking->so_nguoi }} Member</span> /
                                                <span class="room">{{ $booking->so_phong }} Room</span> --}}
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
    </div>
</div>
@endsection
