@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Chính sách hoàn tiền</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Quản lý chính sách hoàn tiền</li>
                </ul>
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
                            <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.refund-policies.create') }}'">
                                Tạo mới
                            </button>
                        </div>
                    </div>
                    <!-- Hiển thị thông báo -->
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
                        <div class="table-responsive" style="min-height: 200px">
                            <table id="booking_table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên chính sách</th>
                                        <th>Mô tả</th>
                                        <th>Hoàn tiền (%)</th>
                                        <th>Phí huỷ (%)</th>
                                        <th>Ngày trước check-in</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refundPolicies as $policy)
                                    <tr>
                                        <td>{{ $policy->id }}</td>
                                        <td>{{ $policy->name }}</td>
                                        <td>{{ Str::limit($policy->description, 100) }}</td>
                                        <td>{{ $policy->refund_percentage }}%</td>
                                        <td>{{ $policy->cancellation_fee_percentage }}%</td>
                                        <td>{{ $policy->days_before_checkin }} ngày</td>
                                        <td>
                                            <span class="badge {{ $policy->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $policy->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ri-settings-3-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.refund-policies.edit', $policy->id) }}">
                                                            <i class="ri-edit-line"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.refund-policies.destroy', $policy->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa chính sách hoàn tiền này không? ');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-line"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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