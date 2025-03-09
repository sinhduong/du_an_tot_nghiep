@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Loại phòng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Chi tiết loại phòng</li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line" data-bs-toggle="tooltip" aria-label="Full Screen" data-bs-original-title="Full Screen"></i></a>
                        </div>
                    </div>
                    <div class="lh-card-content card-booking">
                        <!-- Thông tin cơ bản -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Thông tin cơ bản</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Tên loại phòng: </strong> {{ $roomType->name }}</li>
                                    <li><strong>Giá: </strong> {{ \App\Helpers\FormatHelper::formatPrice($roomType->price) ?? 0 }}</li>
                                    <li><strong>Số người tối đa: </strong> {{ $roomType->max_capacity }} người</li>
                                    <li><strong>Loại giường: </strong>
                                        @php
                                            $bedTypeMapping = [
                                                'single' => 'Giường đơn',
                                                'double' => 'Giường đôi',
                                                'queen' => 'Giường Queen',
                                                'king' => 'Giường King',
                                                'bunk' => 'Giường tầng',
                                                'sofa' => 'Giường sofa',
                                            ];
                                        @endphp
                                        {{ $bedTypeMapping[$roomType->bed_type] ?? 'Không xác định' }}
                                    </li>
                                    <li><strong>Kích thước: </strong> {{ $roomType->size }} m²</li>
                                    <li><strong>Số người lớn: </strong> {{ $roomType->max_capacity }} người</li>
                                    <li><strong>Số trẻ em miễn phí: </strong> {{ $roomType->children_free_limit }} trẻ</li>
                                    <li><strong>Trạng thái: </strong> {{ $roomType->is_active ? 'Hoạt động' : 'Không hoạt động' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Mô tả</h5>
                                <p>{{ $roomType->description ?? 'Chưa có mô tả' }}</p>
                            </div>
                        </div>

                        <!-- Tiện nghi, Dịch vụ, Quy định -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h5 class="mb-3">Tiện nghi</h5>
                                @if ($roomType->amenities->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach ($roomType->amenities as $amenity)
                                            <li>{{ $amenity->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Chưa có tiện nghi</p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-3">Quy tắc & Quy định</h5>
                                @if ($roomType->rulesAndRegulations->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach ($roomType->rulesAndRegulations as $rule)
                                            <li>{{ $rule->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Chưa có quy định</p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-3">Dịch vụ</h5>
                                @if ($roomType->services->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach ($roomType->services as $service)
                                            <li>{{ $service->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Chưa có dịch vụ</p>
                                @endif
                            </div>
                        </div>

                        <!-- Hình ảnh -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Hình ảnh</h5>
                                <div class="row">
                                    @if ($roomType->roomTypeImages->isNotEmpty())
                                        @foreach ($roomType->roomTypeImages as $image)
                                            <div class="col-md-3 col-sm-6 mb-3">
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
                        </div>

                        <!-- Danh sách phòng -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="mb-3">Danh sách phòng ({{ $roomType->rooms->count() }} phòng)</h5>
                                @if ($roomType->rooms->isNotEmpty())
                                <div class="table-responsive">
                                    <table id="booking_table" class="table table-striped table-hover table-sm">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th>ID</th>
                                                <th>Số phòng</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @forelse ($roomType->rooms as $index=>$room)

                                                <td >{{ $index+1 }}</td>
                                                <td>{{ $room->room_number }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="ri-settings-3-line"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.rooms.edit', $room->id) }}">
                                                                    <i class="ri-edit-line"></i> Sửa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.rooms.show', $room->id) }}">
                                                                    <i class="ri-eye-line"></i> Chi tiết
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <p>Chưa có phòng nào thuộc loại này.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Nút Quay lại -->
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('admin.room_types.index') }}">
                                    <button type="button" class="btn btn-secondary btn-sm px-4">Quay lại</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .lh-card-content {
        padding: 20px;
    }
    .lh-user-detail ul {
        list-style: none;
        padding: 0;
    }
    .lh-user-detail ul li {
        margin-bottom: 10px;
    }
    .card-img-top {
        transition: opacity 0.3s;
    }
    h5 {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 5px;
    }
    .card {
        transition: all 0.3s;
    }
    .booked {
        background-color: #f8d7da; /* Màu nền đỏ nhạt cho phòng đã đặt */
        border-color: #f5c6cb; /* Viền đỏ nhạt */
    }
    .booked .card-header {
        background-color: #f5c6cb; /* Đầu thẻ màu đỏ nhạt hơn */
    }
    .booked h6 {
        color: #721c24; /* Màu chữ đỏ đậm */
    }
</style>

@endsection
