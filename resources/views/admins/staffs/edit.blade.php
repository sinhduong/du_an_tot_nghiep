@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Cập nhật nhân viên</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Cập nhật nhân viên</li>
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


            <form action="{{ route('admin.staffs.update', ['staff' => $staff->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="row">
                    <div class="col-xxl-3 col-xl-4 col-md-12">
                        <div class="lh-card-sticky guest-card">
                            <div class="lh-card">
                                <div class="lh-card-content card-default">
                                    <div class="guest-profile">
                                        <div class="lh-team-block-detail lh-profile-add">
                                            <div class="profile-img">
                                                <div class="avatar-preview">
                                                    <div class="t-img" id="imagePreview"
                                                        style="background-image: url({{ Storage::url($staff->avatar) }});">
                                                    </div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="avatar"
                                                        accept=".png, .jpg, .jpeg">
                                                    <label for="imageUpload"></label>
                                                </div>
                                            </div>
                                            <div class="form-group p-b-15">
                                                <input type="text" class="form-control" name="name"
                                                    value="{{ $staff->name }}" placeholder="Tên nhân viên">
                                            </div>
                                            <div class="form-group p-b-15">
                                                <input type="text" class="form-control" name="phone"
                                                    value="{{ $staff->phone }}" placeholder="Số điện thoại">
                                            </div>
                                            <div class="form-group p-b-15">
                                                <input type="text" class="form-control" name="email"
                                                    value="{{ $staff->email }}" placeholder="Email nhân viên">
                                            </div>
                                            <div class="form-group">
                                                <textarea type="text" class="form-control" placeholder="Địa chỉ nhân viên" name="address" rows="6">{{ $staff->address }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-9 col-xl-8 col-md-12">
                        <div class="lh-card" id="bookingtbl">
                            <div class="lh-card-header">
                                <h4 class="lh-card-title">Thêm nhân viên</h4>
                                <div class="header-tools">
                                    <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                            title="Full Screen"></i></a>
                                </div>
                            </div>
                            <div class="lh-card-content card-booking">
                                <div class="row mtb-m-12">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Ngày sinh : </strong>
                                                    <div class="form-group">
                                                        <input type="date" name="birthday"
                                                            value="{{ $staff->birthday }}">
                                                    </div>
                                                </li>
                                                <li><strong>Tiền lương : </strong>
                                                    <div class="form-group">
                                                        <input type="text" name="salary" value="{{ $staff->salary }}"
                                                            class="form-control" placeholder="Nhập số tiền">
                                                    </div>
                                                </li>
                                                <li><strong>Trạng thái : </strong>
                                                    <div class="form-group">
                                                        <select name="status">
                                                            <option value="active"
                                                                {{ $staff->status == 'active' ? 'selected' : '' }}>Hoạt
                                                                động
                                                            </option>
                                                            <option value="inactive"
                                                                {{ $staff->status == 'inactive' ? 'selected' : '' }}>Không
                                                                hoạt động</option>
                                                            <option value="on_leave"
                                                                {{ $staff->status == 'on_leave' ? 'selected' : '' }}>Nghỉ
                                                                phép</option>
                                                        </select>

                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Số bảo hiểm : </strong>
                                                    <div class="form-group">
                                                        <input type="text" name="insurance_number"
                                                            value="{{ $staff->insurance_number }}" class="form-control"
                                                            placeholder="Nhập số bảo hiểm">
                                                    </div>
                                                </li>

                                                <li><strong>Kiểu hợp đồng : </strong>
                                                    <div class="form-group">
                                                        <select name="contract_type">
                                                            <option value="Part-time"
                                                                {{ $staff->contract_type == 'Part-time' ? 'selected' : '' }}>
                                                                Part-time</option>
                                                            <option value="Full-time"
                                                                {{ $staff->contract_type == 'Full-time' ? 'selected' : '' }}>
                                                                Full-time</option>
                                                            <option value="Contract"
                                                                {{ $staff->contract_type == 'Contract' ? 'selected' : '' }}>
                                                                Contract</option>
                                                        </select>

                                                    </div>
                                                </li>
                                                <li><strong>Ngày bắt đầu hợp đồng : </strong>
                                                    <div class="form-group">
                                                        <input type="date" name="contract_start"
                                                            value="{{ $staff->contract_start }}">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Chức vụ : </strong>
                                                    <div class="form-group">
                                                        <select name="role">
                                                            <option value="employee"
                                                                {{ $staff->role == 'employee' ? 'selected' : '' }}>Nhân
                                                                viên</option>
                                                            <option value="manager"
                                                                {{ $staff->role == 'manager' ? 'selected' : '' }}>Quản lý
                                                                nhân viên</option>
                                                            <option value="admin"
                                                                {{ $staff->role == 'admin' ? 'selected' : '' }}>Quản trị
                                                                viên</option>
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Ngày kết thúc hợp đồng : </strong>
                                                    <div class="form-group">
                                                        <input type="date" name="contract_end"
                                                            value="{{ $staff->contract_end }}">
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Ngày bắt đầu đi làm : </strong>
                                                    <div class="form-group">
                                                        <input type="date" name="date_hired"
                                                            value="{{ $staff->date_hired }}">
                                                    </div>
                                                </li>
                                                <li>
                                                    <strong>Ghi chú : </strong>
                                                    <div class="form-group">
                                                        <textarea rows="2" name="notes" class="form-control" placeholder="Ghi chú...">{{ $staff->notes }}</textarea>

                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Chọn Phòng Quản Lý : </strong>
                                                    <select name="room_ids[]" class="form-control select2" multiple
                                                        required>
                                                        @foreach ($rooms as $room)
                                                            <option value="{{ $room->id }}"
                                                                {{ in_array($room->id, $staff->rooms->pluck('id')->toArray()) ? 'selected' : '' }}
                                                                {{ $room->manager_id && $room->manager_id != $staff->id ? 'disabled' : '' }}>
                                                                {{ $room->name }}
                                                                @if ($room->manager_id && $room->manager_id != $staff->id)
                                                                    (Đã có người quản lý)
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </li>

                                                <li>
                                                    <button type="submit" class="lh-btn-primary">Cập nhật Nhân
                                                        Viên</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Quay lại</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
