@extends('layouts.admin')
@section('content')
<main class="wrapper sb-default">
    <!-- Loader -->
    <div class="lh-loader">
        <span class="loader"></span>
    </div>
    <div class="lh-sidebar-overlay"></div>
    <!-- Notify sidebar -->
    <div class="lh-notify-bar-overlay"></div>
    <!-- main content -->
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-page-title">
                    <div class="lh-breadcrumb">
                        <h5>Thùng rác</h5>
                        <ul>
                            <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                            <li>Thùng rác</li>
                        </ul>
                    </div>
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
            <div class="lh-card-header d-flex justify-content-between align-items-center">
                <div class="section-title">
                    <h4>Các phòng đã xóa</h4>
                </div>

            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                @foreach ($rooms as $room)
                    <div class="col-xl-3 col-md-6">
                        <div class="lh-card room-card" id="bookingtbl">
                            <div class="lh-card-header">
                                <h4 class="lh-card-title">{{ $room->room_number }}</h4>
                                <div class="header-tools">
                                    <div class="dropdown" data-bs-toggle="tooltip" data-bs-original-title="Settings">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form
                                                    action="{{ route('admin.rooms.restore', $room->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success">Khôi
                                                        phục</button>
                                                </form>
                                            </li>
{{--                                            <li>--}}
{{--                                                <form--}}
{{--                                                    action="{{ route('admin.rooms.forceDelete', $room->id) }}"--}}
{{--                                                    method="POST" style="display:inline;">--}}
{{--                                                    @csrf--}}
{{--                                                    @method('DELETE')--}}
{{--                                                    <button type="submit" class="btn btn-danger"--}}
{{--                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?');">Xóa--}}
{{--                                                        vĩnh viễn</button>--}}
{{--                                                </form>--}}
{{--                                            </li>--}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="lh-card-content card-default">
                                <div class="lh-room-details">
                                    <ul class="list">
                                        @foreach ($staffs as $item)
                                            @if ($room->manager_id == $item->id)
                                                <li>Nhân viên quản lý phòng : {{ $item->name }}</li>
                                            @endif
                                        @endforeach
                                            <li>Trạng thái :
                                                @switch($room->status)
                                                    @case('available')
                                                        Có sẵn
                                                        @break
                                                    @case('maintanance')
                                                        Bảo trì
                                                        @break
                                                    @case('booked')
                                                        Đã đặt
                                                        @break
                                                    @default
                                                        Không rõ
                                                @endswitch
                                            </li>
                                        @foreach ($room_types_id as $item)
                                            @if ($room->room_type_id == $item->id)
                                                <li>Loại phòng : {{ $item->name }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
</main>
@endsection

