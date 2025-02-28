@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-breadcrumb">
                <h5 class="mb-0">Loại phòng</h5>
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
                <button class="btn btn-link p-0 lh-full-card" title="Full Screen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            <div class="lh-card-content p-3">
                <form id="roomTypeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Tên loại phòng <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                       placeholder="Tên loại phòng" value="{{ old('name') }}">
                                <small class="text-danger error-text name_error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control form-control-sm"
                                       placeholder="Giá phòng" value="{{ old('price') }}" step="0.01" min="0">
                                <small class="text-danger error-text price_error"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Mô tả</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2"
                                          placeholder="Mô tả loại phòng">{{ old('description') }}</textarea>
                                <small class="text-danger error-text description_error"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-control form-control-sm">
                                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                </select>
                                <small class="text-danger error-text is_active_error"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                                <div id="imageInputs">
                                    <div class="input-group input-group-sm mb-2 image-input-group">
                                        <input type="file" name="images[]" class="form-control" multiple>
                                        <button type="button" class="btn btn-outline-danger remove-image-input">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success btn-sm mt-1" id="addImageInput">
                                    <i class="ri-add-line"></i> Thêm ảnh
                                </button>
                                <small class="text-danger error-text images_error"></small>
                            </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .form-label { margin-bottom: 0.25rem; }
    .form-control-sm { padding: 0.25rem 0.5rem; }
    .image-input-group { max-width: 400px; }
    .error-text { font-size: 0.8rem; }
</style>

<script>
$(document).ready(function() {
    $('#addImageInput').click(function() {
        const newInput = `
            <div class="input-group input-group-sm mb-2 image-input-group">
                <input type="file" name="images[]" class="form-control" multiple>
                <button type="button" class="btn btn-outline-danger remove-image-input">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>`;
        $('#imageInputs').append(newInput);
    });

    $(document).on('click', '.remove-image-input', function() {
        if ($('.image-input-group').length > 1) {
            $(this).closest('.image-input-group').remove();
        }
    });

    $('#roomTypeForm').on('submit', function(e) {
        e.preventDefault();
        $('.error-text').text('');

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
                    for (let field in errors) {
                        $(`.${field}_error`).text(errors[field][0]);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Có lỗi xảy ra, vui lòng thử lại!'
                    });
                }
            }
        });
    });
});
</script>
@endsection
