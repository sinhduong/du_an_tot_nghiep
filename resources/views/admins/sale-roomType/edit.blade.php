@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <h5>Chỉnh sửa mối quan hệ Loại phòng - Khuyến mãi</h5>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Form chỉnh sửa</h4>
                        </div>
                        <div class="lh-card-content">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('admin.sale-room-types.update', $saleRoomType) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Tên khuyến mãi</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $saleRoomType->name) }}" placeholder="Tên khuyến mãi">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Giá trị</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="value" class="form-control" value="{{ old('value', $saleRoomType->value) }}" placeholder="Giá trị khuyến mãi">
                                        @error('value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Loại</label>
                                    <div class="col-sm-9">
                                        <select name="type" class="form-control">
                                            <option {{ old('type', $saleRoomType->type) == 'percent' ? 'selected' : '' }} value="percent">Phần trăm</option>
                                            <option {{ old('type', $saleRoomType->type) == 'fixed' ? 'selected' : '' }} value="fixed">Số tiền cố định</option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Loại phòng</label>
                                    <div class="col-sm-9">
                                        <select name="room_type_ids[]" class="form-control" multiple>
                                            @foreach ($roomTypes as $roomType)
                                                <option value="{{ $roomType->id }}"
                                                    {{ in_array($roomType->id, old('room_type_ids', $relatedSaleRoomTypes)) ? 'selected' : '' }}>
                                                    {{ $roomType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('room_type_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @error('room_type_ids.*')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Ngày giờ bắt đầu</label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $saleRoomType->start_date ? \Carbon\Carbon::parse($saleRoomType->start_date)->format('Y-m-d\TH:i') : '') }}">
                                        @error('start_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Ngày giờ kết thúc</label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $saleRoomType->end_date ? \Carbon\Carbon::parse($saleRoomType->end_date)->format('Y-m-d\TH:i') : '') }}">
                                        @error('end_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Trạng thái</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="status">
                                            <option {{ old('status', $saleRoomType->status) == 'active' ? 'selected' : '' }} value="active">Hoạt động</option>
                                            <option {{ old('status', $saleRoomType->status) == 'inactive' ? 'selected' : '' }} value="inactive">Không hoạt động</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <a href="{{ route('admin.sale-room-types.index') }}" class="btn btn-secondary">Quay lại</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
