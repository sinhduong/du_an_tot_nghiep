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
                            <input type="text" name="name" class="form-control form-control-sm"
                                   placeholder="Tên loại phòng" value="{{ old('name', $roomType->name) }}">
                            <small class="text-danger error-text name_error"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control form-control-sm"
                                   placeholder="Giá phòng" value="{{ old('price', $roomType->price) }}"
                                   step="0.01" min="0">
                            <small class="text-danger error-text price_error"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Mô tả</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2"
                                      placeholder="Mô tả loại phòng">{{ old('description', $roomType->description) }}</textarea>
                            <small class="text-danger error-text description_error"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Trạng thái <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control form-control-sm">
                                <option value="1" {{ old('is_active', $roomType->is_active) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active', $roomType->is_active) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            <small class="text-danger error-text is_active_error"></small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Hình ảnh hiện tại</label>
                            <div id="currentImages" class="d-flex flex-wrap gap-2">
                                @forelse ($roomType->roomTypeImages as $image)
                                    <div class="image-container" data-image-id="{{ $image->id }}" style="width: 120px;">
                                        <img src="{{ asset('storage/' . $image->image) }}" class="img-thumbnail"
                                             style="height: 100px; object-fit: cover;" alt="Room Image">
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
    .image-container { transition: opacity 0.3s; }
    .error-text { font-size: 0.8rem; }
</style>

<script>
$(document).ready(function() {
    let deletedImages = [];
    let updatedImages = {};
    let updatedFiles = {};

    $('#addImageInput').click(function() {
        const newInput = `
            <div class="input-group input-group-sm mb-2" style="max-width: 400px;">
                <input type="file" name="images[]" class="form-control" multiple>
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
                    for (let field in errors) $(`.${field}_error`).text(errors[field][0]);
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: xhr.responseJSON?.message || 'Có lỗi xảy ra!' });
                }
            }
        });
    });
});
</script>
@endsection
