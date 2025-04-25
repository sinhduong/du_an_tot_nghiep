@extends('layouts.admin')
<style>
    .header-tools .btn-primary {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .header-tools .btn-link {
        line-height: 1;
    }

    .filter-form .form-control {
        height: 35px;
        font-size: 0.875rem;
    }

    .filter-form .btn {
        height: 35px;
        font-size: 0.875rem;
    }
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
            text: 'Loại phòng này sẽ được xóa mềm và các dữ liệu liên quan cũng sẽ bị xóa mềm!',
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
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        if (response && response.require_action) {
                            // Hiển thị thông báo với nút Tùy chọn
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message,
                                showDenyButton: true,
                                confirmButtonText: 'Đóng',
                                denyButtonText: 'Tùy chọn',
                                showCancelButton: false
                            }).then((result) => {
                                if (result.isDenied) {
                                    // Tạo HTML cho các options
                                    const optionsHtml = Object.entries(response.options).map(([value, text]) => `
                                        <div class="custom-control custom-radio mb-2 d-flex align-items-center">
                                            <input type="radio" id="${value}" name="deleteAction" class="custom-control-input me-3" value="${value}">
                                            <label class="custom-control-label" for="${value}">${text}</label>
                                        </div>
                                    `).join('');

                                    // Tạo HTML cho select box
                                    const selectHtml = `
                                        <div class="form-group mt-3" id="roomTypeSelect" style="display: none;">
                                            <label for="targetRoomType">Chọn loại phòng đích:</label>
                                            <select id="targetRoomType" class="form-control">
                                                <option value="">Chọn loại phòng</option>
                                                @foreach($room_types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    `;

                                    // Hiển thị SweetAlert2 với form tùy chỉnh
                                    Swal.fire({
                                        title: 'Chọn cách xử lý',
                                        html: `
                                            <div class="text-left">
                                                <p>${response.message}</p>
                                                <div id="deleteOptions">
                                                    ${optionsHtml}
                                                </div>
                                                ${selectHtml}
                                            </div>
                                        `,
                                        showCancelButton: true,
                                        confirmButtonText: 'Xác nhận',
                                        cancelButtonText: 'Hủy',
                                        showLoaderOnConfirm: true,
                                        preConfirm: () => {
                                            const action = $('input[name="deleteAction"]:checked').val();
                                            if (!action) {
                                                Swal.showValidationMessage('Vui lòng chọn cách xử lý');
                                                return false;
                                            }

                                            if (action === 'move_to_another' && !$('#targetRoomType').val()) {
                                                Swal.showValidationMessage('Vui lòng chọn loại phòng đích');
                                                return false;
                                            }

                                            return {
                                                action: action,
                                                target_room_type_id: action === 'move_to_another' ? $('#targetRoomType').val() : null
                                            };
                                        },
                                        allowOutsideClick: () => !Swal.isLoading()
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const data = {
                                                _token: '{{ csrf_token() }}',
                                                _method: 'DELETE',
                                                action: result.value.action
                                            };

                                            if (result.value.target_room_type_id) {
                                                data.target_room_type_id = result.value.target_room_type_id;
                                            }

                                            // Gọi API xóa
                                            $.ajax({
                                                url: form.attr('action'),
                                                method: 'POST',
                                                data: data,
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
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Lỗi',
                                                            text: response.message
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

                                    // Xử lý sự kiện khi chọn radio button
                                    $(document).on('change', 'input[name="deleteAction"]', function() {
                                        if ($(this).val() === 'move_to_another') {
                                            $('#roomTypeSelect').show();
                                        } else {
                                            $('#roomTypeSelect').hide();
                                        }
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response?.message || 'Đã có lỗi xảy ra!'
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
@endsection