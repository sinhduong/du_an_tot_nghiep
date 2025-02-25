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
                <div class="col-xxl-3 col-xl-4 col-md-12">
                    <div class="lh-card-sticky guest-card">
                        <div class="lh-card">
                            <div class="lh-card-content card-default">
                                <div class="guest-profile">
                                    @if ($staff->avatar)
                                        <img src="{{ Storage::url($staff->avatar) }}" alt="profile" width="150px">
                                    @endif
                                    {{-- @foreach ($staff->rooms as $room)
                                        <td>

                                            <span class="badge bg-primary">{{ $room->name }}</span>
                                        </td>
                                    @endforeach --}}
                                    <h5>{{ $staff->name }}</h5>
                                    <p>{{ \Carbon\Carbon::parse($staff->birthday)->format('d/m/Y') }}</p>
                                </div>
                                <ul class="list">
                                    <li><i class="ri-phone-line"></i><span>{{ $staff->phone }}</span></li>
                                    <li><i class="ri-mail-line"></i><span>{{ $staff->email }}</span></li>
                                    <li><i class="ri-map-pin-line"></i><span>{{ $staff->address }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <p>ID : {{ $staff->id }}</p>

                                    <h5>{{ $staff->status }}</h5>
                                    <h5>
                                        @foreach ($staff->rooms as $room)
                                            <td>
                                                <span class="badge bg-primary">{{ $room->name }}</span>
                                            </td>
                                        @endforeach
                                    </h5>
                                </span>
                            </div>
                            <div class="booking-box">
                                <div class="booking-info">
                                    <p><i class="ri-calendar-check-line"></i>Ngày bắt đầu hợp đồng</p>
                                    <h6>{{ \Carbon\Carbon::parse($staff->contract_start)->format('d/m/Y') }}</h6>
                                </div>
                                <div class="booking-info">
                                    <p><i class="ri-calendar-check-line"></i>Ngày kết thúc hợp đồng</p>
                                    <h6>{{ \Carbon\Carbon::parse($staff->contract_end)->format('d/m/Y') }}</h6>
                                </div>
                                <div class="booking-info">
                                    <p><i class="ri-hotel-bed-line"></i>Loại hợp đồng</p>
                                    <h6>{{ $staff->contract_type }}</h6>
                                </div>
                                <div class="booking-info">
                                    <p><i class="ri-user-line"></i>Vai trò</p>
                                    <h6>{{ $staff->role }}</h6>
                                </div>
                                <div class="booking-info">
                                    <p><i class="ri-hotel-bed-line"></i>Số bảo hiểm</p>
                                    <h6><span>{{ $staff->insurance_number }}</span></h6>
                                </div>
                                <div class="booking-info">
                                    <p><i class="ri-pass-valid-line"></i>Lương</p>
                                    <td class="active">{{ \App\Helpers\FormatHelper::formatPrice($staff->salary) }}</td>

                                </div>
                            </div>
                            <div class="facilities-details">
                                <h6 class="lh-card-title">Ghi chú</h6>
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
