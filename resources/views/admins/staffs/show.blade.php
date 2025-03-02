@extends('layouts.admin')
@section('content')
    <!-- main content -->
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Chi tiết nhân viên</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Chi tiết nhân viên</li>
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
                @if ($staff->user)
                    <div class="col-xxl-3 col-xl-4 col-md-12">
                        <div class="lh-card-sticky guest-card">
                            <div class="lh-card">
                                <div class="lh-card-content card-default">
                                    <div class="guest-profile">
                                        @if ($staff->user->avatar && Storage::exists($staff->user->avatar))
                                            <img src="{{ Storage::url($staff->user->avatar) }}" alt="profile"
                                                width="150px">
                                        @else
                                            <img class="user" src="{{ asset('assets/admin/assets/img/user/thumb.jpg') }}"
                                                alt="user">
                                        @endif
                                        <h5>{{ $staff->user->name }}</h5>
                                        <p>{{ \Carbon\Carbon::parse($staff->user->birthday)->format('d/m/Y') }}</p>
                                    </div>
                                    <ul class="list">
                                        <li><i class="ri-phone-line"></i><span>{{ $staff->user->phone }}</span></li>
                                        <li><i class="ri-mail-line"></i><span>{{ $staff->user->email }}</span></li>
                                        <li><i class="ri-map-pin-line"></i><span>{{ $staff->user->address }}</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif




                <div class="col-xxl-9 col-xl-8 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Chi tiết</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                        title="Full Screen"></i></a>
                            </div>
                        </div>
                        <div class="lh-card-content card-default">
                            <div class="booking-details">
                                <i class="ri-home-8-line"></i>
                                <span>
                                    @foreach ($roles as $role)
                                        @if ($role->id == $staff->role_id)
                                            <h5>{{ $role->name }}</h5>
                                        @endif
                                    @endforeach
                                    <h5>
                                        {{-- @foreach ($staff->rooms as $room)
                                            <td>
                                                <span class="badge bg-primary">{{ $room->name }}</span>
                                            </td>
                                        @endforeach --}}
                                    </h5>
                                </span>
                            </div>
                            <div class="booking-box">
                                <h5 class="lh-card-title">Quản lý các phòng:</h5>

                                <div class="booking-info">
                                    @foreach ($rooms as $room)
                                        <div class="booking-details">
                                            <h5><span class="badge bg-primary">{{ $room->room_number }}</span></h5>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="booking-box">
                                <h5 class="lh-card-title">Ca làm:</h5>

                                <div class="booking-info">
                                    <div class="booking-details">
                                        <h5><span class="badge bg-primary">{{ $shifts->name }} {{ $shifts->start_time }} -
                                                {{ $shifts->end_time }}</span></h5>
                                    </div>

                                </div>
                            </div>
                            <div class="facilities-details">
                                <h5 class="lh-card-title">Ghi chú</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="facilities-info">
                                            <p>{{ $staff->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
