@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Danh sách thanh toán</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Thanh toán</li>
                    </ul>
                </div>
                <div class="lh-tools">
                    <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                    <div id="pagedate">
                        <div class="lh-date-range" title="Date"><span></span></div>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách thanh toán</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card">
                                    <i class="ri-fullscreen-line" title="Full Screen"></i>
                                </a>
                                <!-- Optional: Add a "Create Payment" button if applicable -->
                                <!-- <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.payments.create') }}'">
                                    <i class="ri-add-line"></i> Thêm mới
                                </button> -->
                            </div>
                        </div>
                        <div class="lh-card-content card-default">
                            <div class="table-responsive" style="min-height: 200px">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th>Người thanh toán</th>
                                        <th>Phương thức</th>
                                        <th>Số tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Mã giao dịch</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($payments as $index => $payment)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $payment->user->name ?? 'Không xác định' }}</td>
                                            <td>{{ ucfirst($payment->method) }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatPrice($payment->amount, 2, ',', '.') }} VNĐ</td>
                                            <td>
                                                    <span class="text-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($payment->status === 'pending' ? 'Chờ xử lý' : ($payment->status === 'completed' ? 'Hoàn thành' : 'Thất bại')) }}
                                                    </span>
                                            </td>
                                            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                            <td>{{ $payment->created_at ? $payment->created_at->diffForHumans() : 'Không có ngày' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.bookings.show', $payment->booking_id) }}">
                                                                <i class="ri-eye-line"></i> Xem chi tiết
                                                            </a>
                                                        </li>
                                                        <!-- Optional: Add edit/delete actions if needed -->
                                                        <!-- <li>
                                                                <a class="dropdown-item" href="{{ route('admin.payments.edit', $payment->id) }}">
                                                                    <i class="ri-edit-line"></i> Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                                                    @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line"></i> Xóa
                                                        </button>
                                                    </form>
                                                </li> -->
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Không có dữ liệu thanh toán</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                {{ $payments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
