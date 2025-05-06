@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Loại phòng</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Thêm mới loại phòng</li>
                    </ul>
                </div>
            </div>
            <!-- <div class="lh-tools d-flex gap-2">
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
            </div> -->
        </div>

        <div class="lh-card">
            <div class="lh-card-header d-flex justify-content-between align-items-center">
                <h4 class="lh-card-title mb-0">{{ $title }}</h4>
                <button class="btn btn-link p-0 lh-full-card" title="Full Screen"><i class="ri-fullscreen-line"></i></button>
            </div>

            <div class="lh-card-content p-3">
                <form id="roomTypeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên loại phòng <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="Nhập tên loại phòng">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-sm @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" placeholder="Nhập giá phòng" step="0.01" min="0">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số người tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="max_capacity" class="form-control form-control-sm @error('max_capacity') is-invalid @enderror"
                                   value="{{ old('max_capacity') }}" placeholder="Nhập số người tối đa" min="1">
                            @error('max_capacity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loại giường <span class="text-danger">*</span></label>
                            <select name="bed_type" class="form-control form-control-sm @error('bed_type') is-invalid @enderror">
                                <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>Giường đơn</option>
                                <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>Giường đôi</option>
                                <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>Giường cỡ lớn(3m²)</option>
                                <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>Giường siêu lớn(4m²)</option>
                                <option value="bunk" {{ old('bed_type') == 'bunk' ? 'selected' : '' }}>Giường tầng</option>
                                <option value="sofa" {{ old('bed_type') == 'sofa' ? 'selected' : '' }}>Giường sofa</option>
                            </select>
                            @error('bed_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kích thước (m²) <span class="text-danger">*</span></label>
                            <input type="number" name="size" class="form-control form-control-sm @error('size') is-invalid @enderror"
                                   value="{{ old('size') }}" placeholder="Nhập kích thước phòng" step="0.1" min="0">
                            @error('size')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số trẻ em miễn phí <span class="text-danger">*</span></label>
                            <input type="number" name="children_free_limit" class="form-control form-control-sm @error('children_free_limit') is-invalid @enderror"
                                   value="{{ old('children_free_limit') }}" placeholder="Nhập số trẻ em miễn phí" min="0">
                            @error('children_free_limit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      rows="2" placeholder="Nhập mô tả loại phòng">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input status-toggle" type="checkbox" name="is_active" value="1"
                                       id="isActive" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive" id="statusLabel">
                                    {{ old('is_active') ? 'Hoạt động' : 'Không hoạt động' }}
                                </label>
                            </div>
                            @error('is_active')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                            <div id="imageInputs">
                                <div class="input-group input-group-sm mb-2" style="max-width: 400px;">
                                    <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple>
                                    <button type="button" class="btn btn-outline-danger remove-image-input">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm mt-1" id="addImageInput">
                                <i class="ri-add-line"></i> Thêm ảnh
                            </button>
                            @error('images.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-sm px-4" id="submitBtn">Thêm mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label { margin-bottom: 0.25rem; }
    .form-control-sm { padding: 0.25rem 0.5rem; }
    .image-input-group { max-width: 400px; }
    .error-text { font-size: 0.8rem; }
    .form-check-input:checked { background-color: #28a745; border-color: #28a745; }
    .form-check-input:not(:checked) { background-color: #dc3545; border-color: #dc3545; }
    #statusLabel.active { color: #28a745; }
    #statusLabel.inactive { color: #dc3545; }
    .status-container {
        margin-top: 0.5rem; /* Khoảng cách phía trên */
        margin-bottom: 0.5rem; /* Khoảng cách phía dưới */
        padding-left: 1rem; /* Khoảng cách bên trái để tạo độ lùi */
    }
    .form-check-input {
        margin-right: 0.5rem; /* Khoảng cách giữa checkbox và label */
    }
    .form-check-label {
        margin-top: 0.1rem; /* Điều chỉnh nhẹ để label cân bằng */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    function updateStatusLabel() {
        const $checkbox = $('#isActive');
        const $label = $('#statusLabel');
        if ($checkbox.is(':checked')) {
            $label.text('Hoạt động').removeClass('inactive').addClass('active');
        } else {
            $label.text('Không hoạt động').removeClass('active').addClass('inactive');
        }
    }
    updateStatusLabel(); // Gọi lần đầu khi tải trang
    $('#isActive').on('change', updateStatusLabel);

    $('#addImageInput').click(function() {
        const newInput = `
            <div class="input-group input-group-sm mb-2" style="max-width: 400px;">
                <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple>
                <button type="button" class="btn btn-outline-danger remove-image-input">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>`;
        $('#imageInputs').append(newInput);
    });

    $(document).on('click', '.remove-image-input', function() {
        if ($('.input-group.mb-2').length > 1) $(this).closest('.input-group').remove();
    });

    $('#roomTypeForm').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("admin.room_types.store") }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $('.is-invalid').removeClass('is-invalid');
                    for (let field in errors) {
                        $(`[name="${field}"]`).addClass('is-invalid');
                        $(`[name="${field}"]`).closest('.form-group').find('.text-danger').text(errors[field][0]);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: xhr.responseJSON?.message || 'Đã có lỗi xảy ra, vui lòng thử lại!'
                    });
                }
            }
        });
    });
});
</script>
@endsection
