@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Tài khoản quản trị viên</h5>
                <ul>    
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li><a href="{{ route('admin.admin_accounts.index') }}">Tài khoản quản trị viên</a></li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    <div class="lh-date-range" title="Date">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i class="ri-fullscreen-line"
                                    title="Full Screen"></i></a>
                        </div>
                    </div>
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên</th>
                                            <th>Email</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày tạo</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $stt = 0;
                                        @endphp
                                        @foreach ($admins as $admin)
                                        @php
                                            $stt++;
                                        @endphp
                                        <tr>
                                            <td class="token">{{ $stt }}</td>
                                            <td><span class="name">{{ $admin->name }}</span></td>
                                            <td class="email">{{ $admin->email }}</td>
                                            <td>
                                                @if($admin->is_active)
                                                <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                <span class="badge bg-danger">Không hoạt động</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->created_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group">

                                                    <button type="button"
                                                        class="btn btn-outline-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.admin_accounts.edit', $admin->id) }}">
                                                                <i class="ri-edit-line"></i> Sửa
                                                            </a>
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
</div>
@endsection