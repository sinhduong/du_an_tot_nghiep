@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Phòng</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12 col-xl-8 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">{{ $title }}</h4>
                        </div>
                        <div class="lh-card-content">
                            <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">



                                    <!-- Room Number -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số phòng *</label>
                                            <input type="text" name="room_number" class="form-control"
                                                value="{{ old('room_number') }}" placeholder="Nhập số phòng">
                                            @error('room_number')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Room Type ID -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Loại phòng *</label>
                                            <select name="room_type_id" class="form-control">
                                                <option value="">-- Chọn loại phòng --</option>
                                                @foreach ($room_types_id as $room)
                                                    <option value="{{ $room->id }}"
                                                        {{ old('room_type_id') == $room->id ? 'selected' : '' }}
                                                        {{ $room->manager_id ? 'disabled' : '' }}>
                                                        {{ $room->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room_type_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nhân viên quản lý *</label>
                                            <select name="manager_id" class="form-control">
                                                <option value="">-- Chọn nhân viên --</option>
                                                @foreach ($staffs_id as $staff)
                                                    <option value="{{ $staff->id }}"
                                                        {{ old('manager_id') == $staff->id ? 'selected' : '' }}
                                                        {{ $staff->manager_id ? 'disabled' : '' }}>
                                                        {{ $staff->id }} - {{ $staff->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('manager_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái *</label>
                                            <select name="status" class="form-control">
                                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                            </select>
                                            @error('status')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="col-md-12 text-center mt-4">
                                        <button type="submit" class="btn btn-primary">Thêm phòng</button>
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
