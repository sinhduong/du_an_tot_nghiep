@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Chi tiết khách hàng</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.customers.index') }}">Khách hàng</a></li>
                        <li>{{ $customer->name }}</li>
                    </ul>
                </div>
                <div class="lh-tools">
                    <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Thông tin khách hàng -->
                <div class="col-xl-4 col-md-12">
                    <div class="lh-card mb-4">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Thông tin khách hàng</h4>
                        </div>
                        <div class="lh-card-content card-default">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Tên:</strong> {{ $customer->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> {{ $customer->email }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Số điện thoại:</strong> {{ $customer->phone ?? 'Chưa cập nhật' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Địa chỉ:</strong> {{ $customer->address ?? 'Chưa cập nhật' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Giới tính:</strong> {{ $customer->gender ?? 'Chưa xác định' }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Trạng thái:</strong>
                                    <span class="text-{{ $customer->is_active ? 'success' : 'danger' }}">
                                        {{ $customer->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <strong>Ngày tạo:</strong> {{ $customer->created_at->locale('vi')->format('d F Y') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Lịch sử đặt phòng -->
                <div class="col-xl-8 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Lịch sử đặt phòng</h4>
                        </div>
                        <div class="lh-card-content card-default">
                            <div class="table-responsive" style="min-height: 200px">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th>Ngày nhận phòng</th>
                                        <th>Ngày trả phòng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày đặt</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($customer->bookings as $index => $booking)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $booking->check_in }}</td>
                                            <td>{{ $booking->check_out }}</td>
                                            <td>{{ number_format($booking->total_price) }} VNĐ</td>
                                            <td>
                                                <span class="text-{{
                                                    $booking->status === 'completed' ? 'success' :
                                                    ($booking->status === 'cancelled' || $booking->status === 'refunded' ? 'danger' :
                                                    ($booking->status === 'check_in' || $booking->status === 'check_out' ? 'info' : 'warning'))
                                                }}">
                                                    {{ ucfirst(
                                                        $booking->status === 'pending_confirmation' ? 'Chờ xác nhận' :
                                                        ($booking->status === 'confirmed' ? 'Đã xác nhận' :
                                                        ($booking->status === 'paid' ? 'Đã thanh toán' :
                                                        ($booking->status === 'check_in' ? 'Đã nhận phòng' :
                                                        ($booking->status === 'check_out' ? 'Đã trả phòng' :
                                                        ($booking->status === 'cancelled' ? 'Đã hủy' : 'Đã hoàn tiền')))))
                                                    ) }}
                                                </span>
                                            </td>
                                            <td>{{ $booking->created_at->locale('vi')->format('d F Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-eye-line"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Khách hàng chưa có lịch sử đặt phòng</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
