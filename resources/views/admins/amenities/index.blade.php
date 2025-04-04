@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5> Tiện nghi loại phòng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li> Tiện nghi</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                    class="ri-fullscreen-line" title="Full Screen"></i></a>
                            <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.amenities.create') }}'">
                                Tạo mới
                            </button>
                        </div>
                    </div>
                    <!-- Thêm phần hiển thị thông báo -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
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
                                            <th>Tên Tiện Ích</th>
                                            <th>Loại Phòng</th>
                                            <th>Trạng Thái </th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($amenities as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @foreach ($item->roomTypes as $roomType)
                                                    <span class="badge bg-primary">{{ $roomType->name }}</span>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                                @if ($item->roomTypes->isEmpty())
                                                    <span class="badge bg-secondary">Chưa gán loại phòng</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.amenities.edit', $item->id) }}">
                                                                <i class="ri-edit-line"></i> Sửa
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.amenities.show', $item->id) }}">
                                                                <i class="ri-eye-line"></i> Chi tiết
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.amenities.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa mềm không?');">
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
