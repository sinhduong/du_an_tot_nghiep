@extends('layouts.admin')
<style>

</style>
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-breadcrumb">
                <h5 class="mb-0">Loại phòng</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 bg-transparent">
                    </ol>
                </nav>
            </div>
        </div>

        <div class="lh-card">
            <div class="lh-card-header d-flex justify-content-between align-items-center">
                <h4 class="lh-card-title mb-0">{{ $title }}</h4>
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <button class="btn btn-link p-0 lh-full-card" title="Full Screen"><i class="ri-fullscreen-line"></i></button>
                    </div>
                    <div>
                        <a href="{{ route('admin.room_types.create') }}" class="btn btn-primary btn-sm">Tạo mới</a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="lh-card-content">
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
                                <td class="text-center">{{ $index + 1 }}</td>
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
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ri-settings-3-line"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.room_types.edit', $item->id) }}">
                                                    <i class="ri-edit-line"></i> Sửa
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.room_types.show', $item->id) }}">
                                                    <i class="ri-eye-line"></i> Chi tiết
                                                </a>
                                            </li>
                                            <li>
                                                <form class="delete-form" data-id="{{ $item->id }}" action="{{ route('admin.room_types.destroy', $item->id) }}" method="POST">
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
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .header-tools .btn-primary {
        padding: 0.375rem 0.75rem; /* Đảm bảo kích thước hợp lý */
        font-size: 0.875rem; /* Kích thước chữ nhỏ hơn */
    }
    .header-tools .btn-link {
        line-height: 1; /* Căn chỉnh icon cho đồng đều */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const roomTypeId = form.data('id');

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: 'Loại phòng này sẽ được xóa mềm!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
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
});
</script>
@endsection
