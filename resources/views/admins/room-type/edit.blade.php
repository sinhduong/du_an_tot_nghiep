@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Sửa loại phòng</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Chỉnh sửa loại phòng</li>
                    </ul>
                </div>
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
                <button class="btn btn-link p-0" title="Full Screen"><i class="ri-fullscreen-line"></i></button>
            </div>

            <div class="lh-card-content p-3">
                <form id="roomTypeForm" action="{{ route('admin.room_types.update', $roomType->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="deleted_images" id="deletedImages" value="[]">
                    <input type="hidden" name="updated_images" id="updatedImages" value="{}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tên loại phòng <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="Tên loại phòng" value="{{ old('name', $roomType->name) }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-danger error-text name_error"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-sm @error('price') is-invalid @enderror"
                                   placeholder="Giá phòng" value="{{ old('price', $roomType->price) }}"
                                   step="0.01" min="0">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-danger error-text price_error"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số người tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="max_capacity" class="form-control form-control-sm @error('max_capacity') is-invalid @enderror"
                                   value="{{ old('max_capacity', $roomType->max_capacity) }}" placeholder="Nhập số người tối đa" min="1">
                            @error('max_capacity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loại giường <span class="text-danger">*</span></label>
                            <select name="bed_type" class="form-control form-control-sm @error('bed_type') is-invalid @enderror">
                                <option value="single" {{ old('bed_type', $roomType->bed_type) == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ old('bed_type', $roomType->bed_type) == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="queen" {{ old('bed_type', $roomType->bed_type) == 'queen' ? 'selected' : '' }}>Queen</option>
                                <option value="king" {{ old('bed_type', $roomType->bed_type) == 'king' ? 'selected' : '' }}>King</option>
                                <option value="bunk" {{ old('bed_type', $roomType->bed_type) == 'bunk' ? 'selected' : '' }}>Bunk</option>
                                <option value="sofa" {{ old('bed_type', $roomType->bed_type) == 'sofa' ? 'selected' : '' }}>Sofa</option>
                            </select>
                            @error('bed_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kích thước (m²) <span class="text-danger">*</span></label>
                            <input type="number" name="size" class="form-control form-control-sm @error('size') is-invalid @enderror"
                                   value="{{ old('size', $roomType->size) }}" placeholder="Nhập kích thước phòng" step="0.1" min="0">
                            @error('size')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số trẻ em miễn phí <span class="text-danger">*</span></label>
                            <input type="number" name="children_free_limit" class="form-control form-control-sm @error('children_free_limit') is-invalid @enderror"
                                   value="{{ old('children_free_limit', $roomType->children_free_limit) }}" placeholder="Nhập số trẻ em miễn phí" min="0">
                            @error('children_free_limit')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      rows="2" placeholder="Mô tả loại phòng">{{ old('description', $roomType->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="text-danger error-text description_error"></small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <div class="form-check form-switch ">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input status-toggle" type="checkbox" name="is_active" value="1"
                                       id="isActive" {{ old('is_active', $roomType->is_active) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive" id="statusLabel">
                                    {{ old('is_active', $roomType->is_active) == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                </label>
                            </div>
                            @error('is_active')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Hình ảnh hiện tại</label>
                            <div id="currentImages" class="d-flex flex-wrap gap-2">
                                @forelse ($roomType->roomTypeImages as $image)
                                    <div class="image-container" data-image-id="{{ $image->id }}" style="width: 120px;">
                                        <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail"
                                             style="height: 100px; object-fit: cover;" alt="Ảnh phòng">
                                        <div class="mt-1 text-center">
                                            <button type="button" class="btn btn-sm btn-danger delete-image px-1"
                                                    data-image-id="{{ $image->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary edit-image px-1"
                                                    data-image-id="{{ $image->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editImageModal">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <small class="text-muted">Chưa có ảnh</small>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Thêm hình ảnh mới</label>
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
                            <small class="text-danger error-text images_error"></small>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm px-4" id="submitBtn">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa ảnh -->
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="editImageModalLabel">Chỉnh sửa ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editImageId">
                <input type="file" class="form-control form-control-sm mb-2" id="editImageFile" name="image"
                       onchange="previewImage(event)">
                <img id="editImagePreview" src="" class="img-thumbnail" style="max-width: 150px;">
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveImageChanges">Lưu</button>
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
    .is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
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

<script>
// const initialIsActive = {{ $roomType->is_active ? 'true' : 'false' }};
$(document).ready(function() {
    let deletedImages = [];
    let updatedImages = {};
    let updatedFiles = {};
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
    // $('#isActive').prop('checked', initialIsActive);
    // updateStatusLabel();

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

    $(document).on('click', '.delete-image', function() {
        const imageId = $(this).data('image-id');
        const $imageContainer = $(this).closest('.image-container');

        deletedImages.push(imageId);
        $('#deletedImages').val(JSON.stringify(deletedImages));

        $imageContainer.fadeOut(300, function() {
            $(this).remove();
            if (!$('#currentImages').find('.image-container').length) {
                $('#currentImages').html('<small class="text-muted">Chưa có ảnh</small>');
            }
        });

        Swal.fire({ icon: 'success', title: 'Đã xóa', text: 'Ảnh sẽ bị xóa khi cập nhật!', timer: 1500, showConfirmButton: false });
    });

    window.previewImage = function(event) {
        const file = event.target.files[0];
        if (file) $('#editImagePreview').attr('src', URL.createObjectURL(file));
    };

    $(document).on('click', '.edit-image', function() {
        const imageId = $(this).data('image-id');
        const $image = $(this).closest('.image-container').find('img');
        $('#editImageId').val(imageId);
        $('#editImagePreview').attr('src', $image.attr('src'));
        $('#editImageFile').val('');
    });

    $('#saveImageChanges').click(function() {
        const imageId = $('#editImageId').val();
        const file = $('#editImageFile')[0].files[0];
        const $imageContainer = $(`.image-container[data-image-id="${imageId}"]`);

        if (!file) {
            Swal.fire({ icon: 'warning', title: 'Chưa chọn ảnh', text: 'Vui lòng chọn ảnh mới!' });
            return;
        }

        const newImageUrl = URL.createObjectURL(file);
        $imageContainer.find('img').attr('src', newImageUrl);
        updatedImages[imageId] = 'temp_' + Date.now() + '_' + file.name;
        updatedFiles[imageId] = file;
        $('#updatedImages').val(JSON.stringify(updatedImages));

        $('#editImageModal').modal('hide');
        Swal.fire({ icon: 'success', title: 'Thành công', text: 'Ảnh đã thay đổi, bấm Cập nhật để lưu!', timer: 1500, showConfirmButton: false });
    });

    $('#roomTypeForm').on('submit', function(e) {
        e.preventDefault();
        $('.error-text').text('');
        $('.is-invalid').removeClass('is-invalid');

        let formData = new FormData(this);
        for (let imageId in updatedFiles) {
            formData.append(`updated_files[${imageId}]`, updatedFiles[imageId]);
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({ icon: 'success', title: 'Thành công', text: response.message, timer: 1500, showConfirmButton: false })
                        .then(() => { window.location.href = response.redirect; });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        $(`[name="${field}"]`).addClass('is-invalid');
                        $(`[name="${field}"]`).closest('.form-group').find('.error-text').text(errors[field][0]);
                    }

                    // Reset trạng thái checkbox nếu có lỗi cho is_active
                    if (errors['is_active']) {
                        $('#isActive').prop('checked', initialIsActive);
                        updateStatusLabel();
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: xhr.responseJSON?.message || 'Có lỗi xảy ra!' });
                }
            }
        });
    });

    function updateStatusLabel() {
        const $checkbox = $('#isActive');
        const $label = $('#statusLabel');
        if ($checkbox.is(':checked')) {
            $label.text('Hoạt động').removeClass('inactive').addClass('active');
        } else {
            $label.text('Không hoạt động').removeClass('active').addClass('inactive');
        }
    }
});
</script>
@endsection
