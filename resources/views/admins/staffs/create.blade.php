@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Nhân viên</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Dashboard</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12 col-xl-8 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            {{-- <h4 class="lh-card-title">{{ $title }}</h4> --}}
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="lh-card-content">
                            <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">




                                    <!-- Room Type ID -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên tài khoản *</label>
                                            <select name="user_id" class="form-control">
                                                <option value="">-- Chọn tài khoản --</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Vai trò *</label>
                                            <select name="role_id" class="form-control">
                                                <option value="">-- Chọn vai trò --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id') == $role->id ? 'selected' : '' }}
                                                        {{ $role->user_id ? 'disabled' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ca làm *</label>
                                            <select name="shift_id" class="form-control">
                                                <option value="">-- Chọn ca làm --</option>
                                                @foreach ($shifts as $shift)
                                                    <option value="{{ $shift->id }}"
                                                        {{ old('shift_id') == $shift->id ? 'selected' : '' }}
                                                        {{ $shift->shift_id ? 'disabled' : '' }}>
                                                        {{ $shift->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- chọn phòng --}}
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Chọn Phòng Quản Lý : </strong>
                                                    <!-- Thêm dropdown chọn phòng -->
                                                    <select name="room_ids[]" class="form-control select2"
                                                        multiple="multiple">
                                                        @foreach ($rooms as $room)
                                                            <option value="{{ $room->id }}"
                                                                {{ $room->manager_id ? 'disabled' : '' }}>
                                                                {{ $room->room_number }} @if ($room->manager_id)
                                                                    (Đã có {{ $room->name }} quản lý)
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái *</label>
                                            <select name="status" class="form-control">
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                    Hoạt động</option>
                                                <option value="inactive"
                                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                    Không hoạt động</option>
                                            </select>
                                            @error('status')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Ghi chú</label>
                                            <textarea name="notes" class="form-control" rows="4" placeholder="Nhập ghi chú...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Thêm nhân viên</button>
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
