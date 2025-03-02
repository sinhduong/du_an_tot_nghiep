@extends('layouts.admin')

@section('title', 'Thêm Vai Trò Nhân Viên')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
                <div class="lh-breadcrumb">
                    <div class="container">
                        <h1 class="mt-4">Thêm Vai Trò Nhân Viên</h1>
                        <form action="{{ route('admin.staff_roles.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Vai Trò</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.staff_roles.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
