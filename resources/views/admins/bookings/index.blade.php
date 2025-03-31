@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Đặt phòng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
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
                            <li><a class="dropdown-booking" href="#">Booking</a></li>
                            <li><a class="dropdown-booking" href="#">Revenue</a></li>
                            <li><a class="dropdown-booking" href="#">Expence</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                    class="ri-fullscreen-line" title="Full Screen"></i></a>
                            <div class="lh-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Mã đặt phòng</th>
                                            <th>Khách hàng</th>
                                            <th>Phòng</th>
                                            <th>Ngày Check-in</th>
                                            <th>Ngày Check-out</th>
                                            <th>Tổng giá</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $index => $booking)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $booking->booking_code }}</td>
                                                <td>
                                                    <small> Người đặt : {{ $booking->user->name ?? 'Không xác định' }}
                                                    </small>
                                                    @if ($booking->guests->isNotEmpty())
                                                        <br>
                                                        <small>
                                                            Người ở:
                                                            @foreach ($booking->guests as $key => $guest)
                                                                {{ $guest->name }}{{ $key < count($booking->guests) - 1 ? ', ' : '' }}
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @foreach ($booking->rooms as $keyI => $room)
                                                        <span>{{ $room->room_number }}</span>
                                                        @if ($keyI < count($booking->rooms) - 1)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ \App\Helpers\FormatHelper::formatDate($booking->check_in) }}</td>
                                                <td>{{ \App\Helpers\FormatHelper::formatDate($booking->check_out) }}</td>
                                                <td>{{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</td>
                                                <td>
                                                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status" onchange="this.form.submit()" class="form-select">
{{--                                                            <option value="pending_confirmation" {{ $booking->status == 'pending_confirmation' ? 'selected' : '' }}>Chưa xác nhận</option>--}}
                                                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                                            <option value="check_in" {{ $booking->status == 'check_in' ? 'selected' : '' }}>Đã check in</option>
                                                            <option value="check_out" {{ $booking->status == 'check_out' ? 'selected' : '' }}>Đã checkout</option>
                                                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                            <option value="refunded" {{ $booking->status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-eye fs-5"></i>
                                                    </a>
                                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa đơn đặt phòng?')">
                                                            <i class="ri-delete-bin-5-fill fs-5"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $bookings->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
