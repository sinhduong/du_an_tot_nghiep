@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title ">
                <div class="lh-breadcrumb">
                    <h5 class="mb-0">{{ $title }}</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Mối quan hệ Loại phòng - Khuyến mãi</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title"></h4>
                            <div class="header-tools">
                                    <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.room_types_promotion.create') }}'">
                                        Thêm mới
                                    </button>
                            </div>
                        </div>
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="lh-card-content card-default">
                            <div class="booking-table">
                                <div class="table-responsive">
                                    <table id="booking_table" class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Loại phòng</th>
                                                <th>Khuyến mãi</th>
                                                <th>Ngày tạo</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($relationships as $relationship)
                                                <tr>
                                                    <td>{{ $relationship->id }}</td>
                                                    <td>{{ $relationship->room_type_name }}</td>
                                                    <td>{{ $relationship->promotion_name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($relationship->created_at)->format('d/m/Y H:i:s') }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ri-settings-3-line"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.room_types_promotion.show', $relationship->id) }}">
                                                                        <i class="ri-eye-line"></i> Chi tiết
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.room_types_promotion.edit', $relationship->id) }}">
                                                                        <i class="ri-edit-line"></i> Sửa
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <form action="{{ route('admin.room_types_promotion.destroy', $relationship->id) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa mối quan hệ này?');">
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
    </div>
@endsection
