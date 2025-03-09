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
                                @method('PUT') <!-- Sử dụng PUT để cập nhật -->

                                <div class="row">

                                    <!-- Tên tài khoản -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên tài khoản *</label>
                                            <select name="user_id" class="form-control">
                                                <option value="">-- Chọn tài khoản --</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $staff->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
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
                                                        {{ $staff->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Ca làm -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ca làm *</label>
                                            <select name="shift_id" class="form-control">
                                                <option value="">-- Chọn ca làm --</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->id }}"
                                                        {{ $staff->shift_id == $shift->id ? 'selected' : '' }}>
                                                        {{ $shift->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Chọn phòng -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phòng quản lý *</label>
                                            <select name="room_ids[]" class="form-control select2" multiple="multiple">
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}"
                                                        {{ in_array($room->id, $staff->rooms->pluck('id')->toArray()) ? 'selected' : '' }}
                                                        {{ $room->manager_id && $room->manager_id !== $staff->id ? 'disabled' : '' }}>
                                                        {{ $room->room_number }}
                                                        @if ($room->manager_id && $room->manager_id !== $staff->id)
                                                            (Đã có {{ $room->manager->name }} quản lý)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Trạng thái -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái *</label>
                                            <select name="status" class="form-control">
                                                <option value="active" {{ $staff->status == 'active' ? 'selected' : '' }}>
                                                    Hoạt động</option>
                                                <option value="inactive"
                                                    {{ $staff->status == 'inactive' ? 'selected' : '' }}>Không hoạt động
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
                                            <textarea name="notes" class="form-control" rows="4" placeholder="Nhập ghi chú...">{{ $staff->notes }}</textarea>
                                            @error('notes')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nút cập nhật -->
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Cập nhật nhân viên</button>
                                        <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">Quay lại</a>
                                    </div>

                                </div> <!-- End row -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
