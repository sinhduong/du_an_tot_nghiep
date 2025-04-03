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
                                <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.sale-room-types.create') }}'">
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
                                                <th>Trạng thái</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saleRoomTypes as $saleRoomType)
                                            <tr>

                                                    <td>{{ $saleRoomType->id }}</td>
                                                    <td>{{ $saleRoomType->roomType->name ?? 'N/A' }}</td>
                                                    <td>
                                                        {{ $saleRoomType->name }} ({{ $saleRoomType->value }}{{ $saleRoomType->type == 'percent' ? '%' : 'VND' }})
                                                    </td>
                                                    <td>{{\App\Helpers\FormatHelper::formatDate( $saleRoomType->created_at) }}</td>
                                                    <td>
                                                        <span
                                                        class="text-{{$saleRoomType->status ==='active' ? 'success' : 'danger'}}">{{ $saleRoomType->status == 'active' ? 'Hoạt động' : 'Không hoạt động'}}</span>

                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="ri-settings-3-line"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.sale-room-types.edit', $saleRoomType) }}" >
                                                                        <i class="ri-edit-line"></i>Sửa
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('admin.sale-room-types.show', $saleRoomType->id) }}">
                                                                        <i class="ri-eye-line"></i> Chi tiết
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                <form class="delete-form" action="{{ route('admin.sale-room-types.destroy', $saleRoomType) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                                                        <i class="ri-delete-bin-line"></i>Xóa</button>
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

