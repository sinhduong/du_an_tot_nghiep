@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                {{-- <h5>Loại Tiện Ích</h5> --}}
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12 col-xl-8 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                    </div>
                    <div class="lh-card-content card-booking">
                        <form action="{{ route('admin.systems.update', $systems->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>Tên dịch vụ *: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="name" placeholder="Enter name" value="{{ $systems->name }}">
                                                    @error('name')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            
                                            <li><strong>Địa Chỉ: </strong>
                                                <div class="form-group">
                                                    <input type="text" name="address" placeholder="Địa Chỉ"
                                                        class="form-control" value="{{ $systems->address  }}">
                                                    @error('address')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Số Điện Thoại : </strong>
                                                <div class="form-group">
                                                    <input type="phone" name="phone" placeholder="Số Điện Thoại"
                                                        class="form-control" value="{{ $systems->phone }}">
                                                    @error('phone')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Map </strong>
                                                <div class="form-group">
                                                    <input type="text" name="map" placeholder="Map"
                                                        class="form-control" value="{{ $systems->map }}">
                                                    @error('map')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Email : </strong>
                                                <div class="form-group">
                                                    <input type="email" name="email" placeholder="Nhập Email"
                                                        class="form-control" value="{{ $systems->email  }}">
                                                    @error('email')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li><strong>Trạng thái *: </strong>
                                                <div class="form-group">
                                                    <select name="is_use" class="form-control">
                                                        <option value="1"
                                                            {{ old('is_use', $systems->is_use) == 1 ? 'selected' : '' }}>
                                                            Hoạt động</option>
                                                        <option value="0"
                                                            {{ old('is_use', $systems->is_use) == 0 ? 'selected' : '' }}>
                                                            Không hoạt động</option>
                                                    </select>
                                                    @error('is_use')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </li>
                                            <li>
                                                <label class="form-label fw-bold">Hình ảnh <span
                                                        class="text-danger">*</span></label>
                                                
                                                <div id="imageInputs">
                                                    <div class="input-group input-group-sm mb-2"
                                                        style="max-width: 400px;">
                                                        <img src="{{ asset('storage/' . $systems->logo) }}" width="120px"
                                                    alt="Product Image">
                                                        <input type="file" name="logo"
                                                            class="form-control @error('images.*') is-invalid @enderror"
                                                            multiple>
                                                        <button type="button"
                                                            class="btn btn-outline-danger remove-image-input">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>City : </strong>
                                                <div class="form-group">
                                                    <input name="city" type="text" class="form-control" placeholder="Enter city" value="{{ $rooms->city }}">
                                                </div>
                                            </li>
                                            <li><strong>Description : </strong>
                                                <input name="description" type="text" class="form-control" placeholder="Enter description" value="{{ $rooms->description }}">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>Price form *: </strong>
                                                <input name="price_min" type="text" class="form-control" placeholder="Enter price form" value="{{ $rooms->price_min }}">
                                                @error('price_min')
                                                <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="lh-user-detail">
                                        <ul>
                                            <li><strong>Price to *: </strong>
                                                <input name="price_max" type="text" class="form-control" placeholder="Enter price to" value="{{ $rooms->price_max }}">
                                                @error('price_max')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                            </li>
                                        </ul>
                                    </div>
                                </div> --}}
                                <div class="col-md-6 col-sm-12">
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#room_type_id').select2({
        placeholder: "Chọn loại phòng",
        allowClear: true
    });
});
</script>
@endpush
