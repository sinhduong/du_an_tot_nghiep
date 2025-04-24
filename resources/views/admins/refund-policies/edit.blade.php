@extends('layouts.admin')

@section('title', 'Chỉnh sửa chính sách hoàn tiền')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Chính sách hoàn tiền</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Quản lý chính sách hoàn tiền</li>
                </ul>
            </div>
            <!-- Giữ nguyên phần tools -->
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                    data-bs-toggle="tooltip" aria-label="Full Screen"
                                    data-bs-original-title="Full Screen"></i></a>
                        </div>
                        <!-- Giữ nguyên header-tools -->
                    </div>
                    <div class="lh-card-content card-booking">
                        <form action="{{ route('admin.refund-policies.update', $refundPolicy) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li>
                                                <strong>Tên chính sách <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="name" placeholder="Tên dịch vụ"
                                                        class="form-control" value="{{ $refundPolicy->name }}">
                                                    @error('name')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>

                                            <li>
                                                <strong>Mô tả <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <textarea name="description" id="description" rows="3">{{ $refundPolicy->description }}</textarea>
                                                    @error('description')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>

                                            <li>
                                                <strong>Tỷ lệ hoàn tiền (%) <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="number" placeholder="Tỷ lệ hoàn tiền (%)" id="refund_percentage" name="refund_percentage" value="{{ $refundPolicy->refund_percentage }}" min="0" max="100"
                                                        class="form-control" step="1">
                                                    @error('refund_percentage')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>

                                            <li>
                                                <strong>Số ngày trước check-in <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="number" placeholder="Số ngày trước check-in" id="days_before_checkin" name="days_before_checkin" value="{{ $refundPolicy->days_before_checkin }}" min="0"
                                                        class="form-control" step="1">
                                                    @error('days_before_checkin')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li>
                                                <strong>Trạng thái <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <select name="is_active" class="form-control">
                                                        <option value="1" {{ $refundPolicy->is_active ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="0" {{ !$refundPolicy->is_active ? 'selected' : '' }}>Không hoạt động</option>
                                                    </select>
                                                    @error('is_active')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <button type="submit" class="lh-btn-primary">Lưu</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 


