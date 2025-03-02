@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Chi tiết đơn đặt phòng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Đơn đặt phòng</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh"><i class="ri-refresh-line"></i></a>
                <div class="lh-date-range" title="Date">
                    <span></span>
                </div>
                <div class="filter">
                    <div class="dropdown" title="Filter">
                        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-sound-module-line"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Booking</a></li>
                            <li><a class="dropdown-item" href="#">Revenue</a></li>
                            <li><a class="dropdown-item" href="#">Expence</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 1. Thông tin chi tiết tài khoản -->
            <div class="col-md-4">
                <div class="info-section">
                    <h4>Thông tin tài khoản</h4>
                    <ul>
                        <li>Tên khách hàng: {{ $booking->user->name }}</li>
                        <li>Số điện thoại: {{ $booking->user->phone ?: 'Không có' }}</li>
                        <li>Email: {{ $booking->user->email ?: 'Không có' }}</li>
                        <li>Địa chỉ: {{ $booking->user->address ?: 'Không có' }}</li>
                        <li>Giới tính: {{ $booking->user->gender ?: 'Không xác định' }}</li>
                        <li>Trạng thái: {{ $booking->user->is_active ? 'Hoạt động' : 'Bị khóa' }}</li>
                    </ul>
                </div>
            </div>

            <!-- 2. Thông tin chi tiết phòng và loại phòng -->
            <div class="col-md-4">
                <div class="info-section">
                    <h4>Thông tin phòng và loại phòng</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Loại phòng</th>
                                <th>Trạng thái</th>
                                <th>Giá</th>
                                <th>Tiện ích</th>
                                <th>Quy định</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->rooms as $room)
                            <tr>
                                <td>{{ $room->roomType->name }} ({{ $room->room_number }})</td>
                                <td>{{ $room->status === 'available' ? 'Còn trống' : 'Đã đặt' }}</td>
                                <td>{{\App\Helpers\FormatHelper::formatPrice($room->roomType->price ?? 0)}}</td>
                                <td>
                                    @foreach ($room->roomType->amenities as $amenity)
                                        {{ $amenity->name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($room->roomType->rulesAndRegulations as $rule)
                                        {{ $rule->name }}<br>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Thông tin chi tiết đơn đặt phòng -->
            <div class="col-md-4">
                <div class="info-section">
                    <h4>Thông tin đơn đặt phòng</h4>
                    <ul>
                        <li>Ngày check-in: {{\App\Helpers\FormatHelper::formatDate($booking->check_in) }}</li>
                        <li>Ngày check-out: {{\App\Helpers\FormatHelper::formatDate($booking->check_out) }}</li>
                        <li>Thanh toán:
                            @if ($booking->payments->isNotEmpty())
                                @foreach ($booking->payments as $payment)
                                    {{ $payment->method }} ({{ \App\Helpers\FormatHelper::formatPrice($payment->amount ?? 0) }})<br>
                                @endforeach
                            @else
                                Chưa thanh toán
                            @endif
                        </li>
                        <li>Tổng tiền: {{\App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</li>
                        <li>Số người: Người lớn: {{ $booking->total_guests }} | Trẻ em: {{ $booking->children_count }}</li>
                        <li>Trạng thái:
                            @php
                                $statusMapping = [
                                    'pending_confirmation' => 'Chưa xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'paid' => 'Đã thanh toán',
                                    'check_in' => 'Đã check in',
                                    'check_out' => 'Đã checkout',
                                    'cancelled' => 'Đã hủy',
                                    'refunded' => 'Đã hoàn tiền',
                                ];
                            @endphp
                            {{ $statusMapping[$booking->status] }}
                        </li>
                    </ul>

                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật trạng thái -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Cập nhật trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái mới</label>
                        <select name="status" id="status" class="form-control" required>
                            @php
                                $statusOptions = [
                                    'pending_confirmation' => 'Chưa xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'paid' => 'Đã thanh toán',
                                    'check_in' => 'Đã check in',
                                    'check_out' => 'Đã checkout',
                                    'cancelled' => 'Đã hủy',
                                    'refunded' => 'Đã hoàn tiền',
                                ];
                            @endphp
                            @foreach ($statusOptions as $key => $value)
                                <option value="{{ $key }}" {{ $booking->status === $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
