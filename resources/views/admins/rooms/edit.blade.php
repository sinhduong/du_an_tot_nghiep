@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Room</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Chỉnh sửa phòng</li>
                    </ul>
                </div>
            </div>
            @if (session()->has('success') && !session()->get('success'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-xxl-12 col-xl-8 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Cập nhật phòng</h4>
                        </div>
                        <div class="lh-card-content">
                            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên phòng *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $room->name }}">
                                            @error('name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Room Number -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số phòng *</label>
                                            <input type="text" name="room_number" class="form-control"
                                                value="{{ $room->room_number }}">
                                            @error('room_number')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giá phòng *</label>
                                            <input type="text" name="price" class="form-control"
                                                value="{{ $room->price }}">
                                            @error('price')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Max Capacity -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sức chứa tối đa *</label>
                                            <input type="number" name="max_capacity" class="form-control"
                                                value="{{ $room->max_capacity }}">
                                            @error('max_capacity')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Bed Type -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Loại giường *</label>
                                            <select name="bed_type" class="form-control">
                                                @foreach (['single', 'double', 'queen', 'king', 'bunk', 'sofa'] as $type)
                                                    <option value="{{ $type }}"
                                                        {{ old('bed_type', $room->bed_type) == $type ? 'selected' : '' }}>
                                                        {{ ucfirst($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bed_type')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Children Free Limit -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số trẻ em miễn phí</label>
                                            <input type="number" name="children_free_limit" class="form-control"
                                                value="{{ $room->children_free_limit }}">
                                            @error('children_free_limit')
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
                                                @foreach ($room_types_id as $room_type)
                                                    <option value="{{ $room_type->id }}"
                                                        {{ $room->room_type_id == $room_type->id ? 'selected' : '' }}>
                                                        {{ $room_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room_type_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Manager ID -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nhân viên quản lý *</label>
                                            <select name="manager_id" class="form-control">
                                                <option value="">-- Chọn nhân viên --</option>
                                                @foreach ($staffs_id as $staff)
                                                    <option value="{{ $staff->id }}"
                                                        {{ $room->manager_id == $staff->id ? 'selected' : '' }}>
                                                        {{ $staff->id }} - {{ $staff->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('manager_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mô tả</label>
                                            <textarea name="description" class="form-control">{{ $room->description }}</textarea>
                                            @error('description')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái *</label>
                                            <select name="status" class="form-control">
                                                <option value="available"
                                                    {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="booked" {{ $room->status == 'booked' ? 'selected' : '' }}>
                                                    Booked</option>
                                                <option value="maintenance"
                                                    {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance
                                                </option>
                                            </select>
                                            @error('status')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Cập nhật phòng</button>
                                    </div>

                                </div> <!-- End row -->
                            </form>
                            <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
