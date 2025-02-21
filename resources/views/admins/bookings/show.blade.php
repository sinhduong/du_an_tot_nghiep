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
                                <div class="lh-team-block-detail lh-profile-add">
                                    <div class="profile-img">
                                        <div class="avatar-preview">
                                            <div class="t-img" id="imagePreview"
                                                style="background-image: url({{ $booking->user->avatar ? assets('upload/avatars/'. $booking->user->avatar) : 'https://dongvat.edu.vn/upload/200x200/2025/01/lam-anh-200x200.webp' }})">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                       Tên khách hàng: {{ $booking->user->name }}
                                    </div>
                                    <div class="form-group">
                                        Số điện thoại: {{ $booking->user->phone }}
                                    </div>
                                    <div class="form-group">
                                        Địa chỉ: {{ $booking->user->address }}
                                    </div>
                                    <div class="form-group">
                                        Giới tính: {{ $booking->user->gender }}
                                    </div>
                                    <div class="form-group">
                                        Email: {{ $booking->user->email }}
                                    </div>
                                    <div class="form-group">
                                        Trạng thái: {{ $booking->user->is_active }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-9 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Booking Details</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                    title="Full Screen"></i></a>
                        </div>
                    </div>
                    <div class="lh-card-content card-booking">
                        <div class="row mtb-m-12">
                            <div class="col-md-6 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li><strong>CheckIn : </strong>
                                            <div class="form-group">
                                                <input type="date" name="dateofbirth">
                                            </div>
                                        </li>
                                        <li><strong>CheckOut : </strong>
                                            <div class="form-group">
                                                <input type="date" name="dateofbirth">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li><strong>Person : </strong>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Person">
                                            </div>
                                        </li>
                                        <li><strong>Rooms : </strong>
                                            <input type="text" class="form-control" placeholder="Enter Rooms">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li><strong>Bed type : </strong>
                                            <div class="form-group">
                                                <select name="select" id="select">
                                                    <option value="option-1">Single</option>
                                                    <option value="option-2">Double</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li><strong>Proof : </strong>
                                            <div class="form-group">
                                                <select name="select" id="select">
                                                    <option value="option-1">Pan Card</option>
                                                    <option value="option-2">Adhar Card</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li><strong>Room Type : </strong>
                                            <div class="form-group">
                                                <select name="select" id="select">
                                                    <option value="option-1">Junior Suit</option>
                                                    <option value="option-2">Delux</option>
                                                    <option value="option-2">VIP</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li><strong>Payment : </strong>
                                            <div class="form-group">
                                                <select name="select" id="select">
                                                    <option value="option-1">Cash</option>
                                                    <option value="option-2">UPI</option>
                                                    <option value="option-2">Cheque</option>
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li>
                                            <button type="submit" class="lh-btn-primary">Submit</button>
                                        </li>
                                    </ul>
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
                                            <th>Phòng</th>
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
                                            <td class="type"><span>{{ $booking->room->roomType->name }} :  </span>Số {{ $booking->room->room_number }}</td>
                                            <td>{{ $booking->room->room_number }}</td>
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
