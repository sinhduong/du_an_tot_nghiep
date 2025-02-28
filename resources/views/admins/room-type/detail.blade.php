@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Loại phòng</h5>
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
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
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
                                        <li><strong>Tên loại phòng: </strong>
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ $roomType->name }}" disabled>
                                            </div>
                                        </li>
                                        <li><strong>Mô tả: </strong>
                                            <div class="form-group">
                                                <textarea class="form-control" rows="3" disabled>{{ $roomType->description }}</textarea>
                                            </div>
                                        </li>
                                        <li><strong>Giá: </strong>
                                            <div class="form-group">
                                                <input type="number" class="form-control" value="{{ $roomType->price }}" step="0.01" disabled>
                                            </div>
                                        </li>
                                        <li><strong>Trạng thái: </strong>
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{ $roomType->is_active ? 'Hoạt động' : 'Không hoạt động' }}" disabled>
                                            </div>
                                        </li>
                                        <li><strong>Hình ảnh: </strong>
                                            <div class="form-group mb-3" id="currentImages">
                                                <div class="row">
                                                    @if ($roomType->roomTypeImages->isNotEmpty())
                                                        @foreach ($roomType->roomTypeImages as $image)
                                                            <div class="col-md-3 col-sm-6 image-container" data-image-id="{{ $image->id }}">
                                                                <div class="card">
                                                                    <img src="{{ asset('storage/' . $image->image) }}"
                                                                         class="card-img-top"
                                                                         alt="Room Image"
                                                                         style="height: 150px; object-fit: cover;">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p>Chưa có ảnh</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-md-12 ">
                                    <a href="{{ route('admin.room_types.index') }}" >

                                        <button type="submit" class="btn btn-primary btn-sm px-4" id="submitBtn">Quay lại</button>
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-input-group {
        max-width: 500px;
    }
    .image-container .card {
        transition: opacity 0.3s;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }
</style>

@endsection
