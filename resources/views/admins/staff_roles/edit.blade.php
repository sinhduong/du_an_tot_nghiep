@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Vai Trò Nhân Viên')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
                <div class="lh-breadcrumb">
                    <div class="container">
                        <h1 class="mt-4">Chỉnh Sửa Vai Trò Nhân Viên</h1>
                        <form action="{{ route('admin.staff_roles.update', $staffRole->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Vai Trò</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $staffRole->name }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">Cập Nhật</button>
                            <a href="{{ route('admin.staff_roles.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
