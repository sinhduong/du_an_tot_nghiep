@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Dịch vụ phát sinh</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Quản lý dịch vụ phát sinh</li>
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
                        <form action="{{ route('admin.service_plus.store') }}" method="POST">
                            @csrf
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li>
                                                <strong>Tên dịch vụ <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="name" placeholder="Tên dịch vụ"
                                                        class="form-control" value="{{ old('name') }}" >
                                                    @error('name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li>
                                                <strong>Giá dịch vụ <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="number" name="price" placeholder="Giá dịch vụ"
                                                        class="form-control" step="0.01" min="0"
                                                        value="{{ old('price') }}" >
                                                    @error('price')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li>
                                                <strong>Trạng thái <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <select name="is_active" class="form-control" >
                                                        <option value="1" {{ old('is_active') == 0 ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="0" {{ old('is_active') == 1 ? 'selected' : '' }}>Không hoạt động</option>
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
                                        <button type="submit" class="lh-btn-primary">Thêm</button>
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
