@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Nhân viên</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Cập nhật nhân viên</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12 col-xl-8 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Cập nhật Nhân Viên</h4>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="lh-card-content">
                            <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Tên nhân viên -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên nhân viên *</label>
                                            <input class="form-control" type="text" name="name" placeholder="Ví dụ: Nguyen Van A"
                                                   value="{{ $staff->user->name ?? old('name') }}">
                                            @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input class="form-control" type="email" name="email" placeholder="Ví dụ: nhanvien@gmail.com"
                                                   value="{{ $staff->user->email ?? old('email') }}">
                                            @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" type="text" name="password"
                                                   value="{{ old('password') }}" placeholder="Nhập mật khẩu mới (nếu muốn thay đổi)">
                                            @error('password')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Vai trò -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vai trò *</label>
                                            <select name="role_id" class="form-control">
                                                <option value="">-- Chọn vai trò --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ $staff->role_id == $role->id ? 'selected' : '' }}
                                                        {{ $role->user_id && $role->user_id !== $staff->user_id ? 'disabled' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Trạng thái -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái *</label>
                                            <select name="status" class="form-control">
                                                <option value="active" {{ $staff->user->status == 'active' ? 'selected' : '' }}>
                                                    Hoạt động</option>
                                                <option value="inactive"
                                                    {{ $staff->user->status == 'inactive' ? 'selected' : '' }}>Không hoạt động
                                                </option>
                                            </select>
                                            @error('status')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Ghi chú -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Ghi chú</label>
                                            <textarea name="notes" class="form-control" rows="4"
                                                      placeholder="Nhập ghi chú...">{{ $staff->notes ?? old('notes') }}</textarea>
                                            @error('notes')
                                            <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nút cập nhật -->
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Cập nhật nhân viên</button>
                                        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Quay lại</a>
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
