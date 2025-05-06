@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Banner </h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Tạo Banner</li>
                </ul>
            </div>
            {{-- <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    <div class="lh-date-range" title="Date">
                        <span></span>
                    </div>
                </div>
                <div class="filter">
                    <div class="dropdown" title="Filter">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-sound-module-line"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Booking</a></li>
                            <li><a class="dropdown-item" href="#">Revenue</a></li>
                            <li><a class="dropdown-item" href="#">Expence</a></li>
                        </ul>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                    data-bs-toggle="tooltip" aria-label="Full Screen"
                                    data-bs-original-title="Full Screen"></i></a>
                        </div>
                    </div>
                    <div class="lh-card-content card-booking">
                        <form action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>Tên Banner <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="name" placeholder="Tên Banner"
                                                        class="form-control" value="{{ old('name') }}">
                                                    @error('name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li> 
                                            <li><strong>Trạng thái <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                    <select name="is_use" class="form-control">
                                                        <option value="1" {{ old('is_use') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="0" {{ old('is_use') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                                    </select>
                                                    @error('is_use')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Link <span class="text-danger">*</span>: </strong>
                                                <div class="form-group">
                                                   
                                                    <input type="text" name="link" placeholder="Link Banner"
                                                    class="form-control" value="{{ old('link') }}">
                                               
                                                    @error('link')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                    
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">Hình ảnh <span class="text-danger">*</span></label>
                                                    <div id="imageInputs">
                                                        <div class="input-group input-group-sm mb-2" style="max-width: 400px;">
                                                            <input type="file" name="image" class="form-control">
                                                            <button type="button" class="btn btn-outline-danger remove-image-input">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('image')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li>
                                                <button type="submit" class="lh-btn-primary">Lưu</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('select[name="room_type_id[]"]').select2({
        placeholder: "Chọn loại phòng",
        allowClear: true
    });
});
</script>
