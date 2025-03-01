@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Loại Tiện Ích</h5>
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
                        <form action="{{ route('admin.amenities.update', $amenity->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mtb-m-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Tên Tiện Ích *</label>
                                        <input type="text" name="name" id="name" placeholder="Tên Tiện Ích" value="{{ $amenity->name }}" class="form-control">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="room_type_id">Chọn Loại Phòng</label>
                                        <select name="room_type_id[]" id="room_type_id" class="form-control select2" multiple>
                                            @foreach($roomTypes as $id => $name)
                                                <option value="{{ $id }}" {{ in_array($id, $amenity->roomTypes->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <button type="submit" class="lh-btn-primary">Lưu</button>
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
