@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                {{-- <h5>rooms</h5> --}}
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    {{-- <div class="lh-date-range" title="Date">
                        <span></span>
                    </div> --}}
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
                        <form action="{{ route('admin.rule-regulations.update', $rules->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>Tên dịch vụ *: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="name" placeholder="Enter name" value="{{ $rules->name }}">
                                                    @error('name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Áp dụng cho *: </strong>
                                                <div class="form-group">
                                                    <select name="roomTypes[]" multiple="multiple" class="form-select">
                                                        <option value="">Chọn nhiều loại phòng</option>
                                                        @foreach($roomTypes as $roomType)
                                                            <option value="{{ $roomType->id }}"
                                                                {{ in_array($roomType->id, old('roomTypes', $selectedRoomTypes ?? [])) ? 'selected' : '' }}>
                                                                {{ $roomType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('is_active')
                                                    <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Trạng thái *: </strong>
                                                <div class="form-group">
                                                    <select name="is_active" class="form-control">
                                                        <option value="1" {{ old('is_active', $rules->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="0" {{ old('is_active', $rules->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                                    </select>
                                                    @error('is_active')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li>
                                                <button type="submit" class="lh-btn-primary">Lưu</button>
                                            </li>
                                        </ul>
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

