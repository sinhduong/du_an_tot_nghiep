@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-breadcrumb">
                <h5 class="mb-0">Phòng</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 bg-transparent">
                        <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="lh-tools d-flex gap-2">
                <button class="btn btn-link p-0" title="Refresh"><i class="ri-refresh-line"></i></button>
                <div class="lh-date-range" title="Date"><span></span></div>
                <div class="dropdown" title="Filter">
                    <button class="btn btn-link dropdown-toggle p-0" data-bs-toggle="dropdown">
                        <i class="ri-sound-module-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Booking</a></li>
                        <li><a class="dropdown-item" href="#">Revenue</a></li>
                        <li><a class="dropdown-item" href="#">Expence</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lh-card">
            <div class="lh-card-header d-flex justify-content-between align-items-center">
                <h4 class="lh-card-title mb-0">{{ $title }}</h4>
                <div class="header-tools d-flex gap-2">
                    <button class="btn btn-link p-0 lh-full-card" title="Full Screen"><i class="ri-fullscreen-line"></i></button>
                    <div class="lh-date-range"><span></span></div>
                    <a href="{{ route('admin.room_types.create') }}" class="btn btn-primary btn-sm">Tạo mới</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="lh-card-content p-3">
                <div class="table-responsive">
                    <table id="booking_table" class="table table-striped table-hover table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Tên</th>
                                <th>Hình ảnh</th>
                                <th>Mô tả</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($room_types as $index => $item)
                            <tr data-id="{{ $item->id }}">
                                <td class="text-center">{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if ($item->roomTypeImages->isNotEmpty())
                                        <img src="{{ Storage::url($item->roomTypeImages->first()->image) }}"
                                             width="100" height="100" alt="{{ $item->name }}"
                                             class="img-thumbnail">
                                    @else
                                        <small>Chưa có</small>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->description, 30) }}</td>
                                <td>{{ \App\Helpers\FormatHelper::formatPrice($item->price) }}</td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                    </span>
                                </td>
                                <td>
                                    <form class="restore-form" data-id="{{ $item->id }}" action="{{ route('admin.room_types.restore', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm me-1">Khôi phục</button>
                                    </form>
                                    <form class="force-delete-form" data-id="{{ $item->id }}" action="{{ route('admin.room_types.forceDelete', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa vĩnh viễn</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('admin.room_types.index') }}" class="btn btn-primary btn-sm mt-3">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Xử lý khôi phục
    $('.restore-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const roomTypeId = form.data('id');

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: 'Loại phòng này sẽ được khôi phục!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Khôi phục',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: xhr.responseJSON?.message || 'Đã có lỗi xảy ra!'
                        });
                    }
                });
            }
        });
    });

    // Xử lý xóa vĩnh viễn
    $('.force-delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const roomTypeId = form.data('id');

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: 'Loại phòng này sẽ bị xóa vĩnh viễn!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa vĩnh viễn',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                $(`tr[data-id="${roomTypeId}"]`).fadeOut(300, function() {
                                    $(this).remove();
                                    if ($('#booking_table tbody tr').length === 0) {
                                        $('#booking_table tbody').html('<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>');
                                    }
                                });
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: xhr.responseJSON?.message || 'Đã có lỗi xảy ra!'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
