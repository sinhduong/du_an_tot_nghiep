@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <h2>Chi tiết mối quan hệ</h2>

            <p><strong>Loại phòng:</strong> {{ $relationship->room_type_name }}</p>
            <p><strong>Khuyến mãi:</strong> {{ $relationship->promotion_name }}</p>
            <p><strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($relationship->created_at)->format('d/m/Y H:i:s') }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ \Carbon\Carbon::parse($relationship->updated_at)->format('d/m/Y H:i:s') }}</p>

            <a href="{{ route('admin.room_types_promotion.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
@endsection
