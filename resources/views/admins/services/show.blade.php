@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Chi tiết dịch vụ</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li><a href="{{ route('admin.services.index') }}">Danh sách dịch vụ</a></li>
                    <li>Chi tiết dịch vụ</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="servicedetail">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line" data-bs-toggle="tooltip" aria-label="Full Screen" data-bs-original-title="Full Screen"></i></a>
                        </div>
                    </div>
                    <div class="lh-card-content card-booking">
                        <div class="row mtb-m-12">
                            <div class="col-md-12 col-sm-12">
                                <div class="lh-user-detail">
                                    <ul>
                                        <li>
                                            <strong>Tên dịch vụ: </strong>
                                            <span>{{ $service->name }}</span>
                                        </li>
                                        <li>
                                            <strong>Giá dịch vụ: </strong>
                                            <span>{{ \App\Helpers\FormatHelper::formatPrice($service->price) }}</span>
                                        </li>
                                        <li>
                                            <strong>Áp dụng cho: </strong>
                                            @if ($service->roomTypes->isNotEmpty())
                                                @foreach ($service->roomTypes as $roomType)
                                                    <span class="badge bg-primary">{{ $roomType->name }}</span>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="badge bg-secondary">Chưa gán loại phòng</span>
                                            @endif
                                        </li>
                                        <li>
                                            <strong>Trạng thái: </strong>
                                            <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $service->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
