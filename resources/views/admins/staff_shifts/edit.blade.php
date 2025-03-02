@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Ca Làm Việc')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
            <div class="lh-breadcrumb">
    <div class="container">
        <h1 class="mt-4">Chỉnh Sửa Ca Làm Việc</h1>
        <form action="{{ route('admin.staff_shifts.update', $staffShift->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên Ca</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $staffShift->name }}" required>
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Giờ Bắt Đầu</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $staffShift->start_time }}" required>
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">Giờ Kết Thúc</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $staffShift->end_time }}" required>
            </div>
            <button type="submit" class="btn btn-success">Cập Nhật</button>
            <a href="{{ route('admin.staff_shifts.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
    </div>
    </div>
    </div>
</div>
@endsection
