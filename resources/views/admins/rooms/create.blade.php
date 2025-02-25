@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Room</h5>
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
                            <h4 class="lh-card-title">{{ $title }}</h4>
                        </div>
                        <div class="lh-card-content">
                            <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên phòng *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Manager ID -->
                                    {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ID Nhân viên quản lý (nếu có)</label>
                                        <input type="text" name="manager_id" class="form-control" value="{{ old('manager_id') }}">
                                        @error('manager_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div> --}}

                                    <!-- Room Number -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Số phòng *</label>
                                            <input type="text" name="room_number" class="form-control"
                                                value="{{ old('room_number') }}">
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
                                                value="{{ old('price') }}">
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
                                                value="{{ old('max_capacity') }}">
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
                                                <option value="single" {{ old('bed_type') == 'single' ? 'selected' : '' }}>
                                                    Single</option>
                                                <option value="double" {{ old('bed_type') == 'double' ? 'selected' : '' }}>
                                                    Double</option>
                                                <option value="queen" {{ old('bed_type') == 'queen' ? 'selected' : '' }}>
                                                    Queen</option>
                                                <option value="king" {{ old('bed_type') == 'king' ? 'selected' : '' }}>
                                                    King</option>
                                                <option value="bunk" {{ old('bed_type') == 'bunk' ? 'selected' : '' }}>
                                                    Bunk</option>
                                                <option value="sofa" {{ old('bed_type') == 'sofa' ? 'selected' : '' }}>
                                                    Sofa</option>
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
                                                value="{{ old('children_free_limit') }}">
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


                                    <div class="col-md-6">
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
                                            @error('room_type_id')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mô tả</label>
                                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
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
                                                    {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>
                                                    Booked</option>
                                                <option value="maintenance"
                                                    {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance
                                                </option>
                                            </select>
                                            @error('status')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>



                                    <!-- Submit Button -->
                                    <div class="col-md-12 text-center">
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
