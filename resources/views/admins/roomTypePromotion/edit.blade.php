@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <h2>Sửa mối quan hệ giữa Loại phòng và Khuyến mãi</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.room_types_promotion.update', $relationship->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="room_type_id">Chọn Loại phòng</label>
                    <select name="room_type_id" id="room_type_id" class="form-control @error('room_type_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Loại phòng --</option>
                        @foreach ($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ old('room_type_id', $relationship->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="promotion_id">Chọn Khuyến mãi</label>
                    <select name="promotion_id" id="promotion_id" class="form-control @error('promotion_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Khuyến mãi --</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion->id }}" {{ old('promotion_id', $relationship->promotion_id) == $promotion->id ? 'selected' : '' }}>
                                {{ $promotion->name }} (Mã: {{ $promotion->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('promotion_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success mt-3">Cập nhật mối quan hệ</button>
                <a href="{{ route('admin.room_types_promotion.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
            </form>
        </div>
    </div>
@endsection
