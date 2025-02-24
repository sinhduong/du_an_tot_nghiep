@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Thêm nhân viên</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Thêm nhân viên</li>
                    </ul>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                                        style="background-image: url(http://du_an_tot_nghiep.test/assets/admin/assets/img/user/thumb.jpg);">
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
                                                    value="{{ old('name') }}" placeholder="Tên nhân viên">
                                            </div>
                                            <div class="form-group p-b-15">
                                                <input type="text" class="form-control" name="phone"
                                                    value="{{ old('phone') }}" placeholder="Số điện thoại">
                                            </div>
                                            <div class="form-group p-b-15">
                                                <input type="text" class="form-control" name="email"
                                                    value="{{ old('email') }}" placeholder="Email nhân viên">
                                            </div>
                                            <div class="form-group">
                                                <textarea type="text" class="form-control" placeholder="Địa chỉ nhân viên" name="address" rows="6">{{ old('address') }}</textarea>
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
                                                        <input type="date" name="birthday" value="{{ old('birthday') }}">
                                                    </div>
                                                </li>
                                                <li><strong>Tiền lương : </strong>
                                                    <div class="form-group">
                                                        <input type="text" name="salary" value="{{ old('salary') }}"
                                                            class="form-control" placeholder="Nhập số tiền">
                                                    </div>
                                                </li>
                                                <li><strong>Trạng thái : </strong>
                                                    <div class="form-group">
                                                        <select name="status" value="{{ old('status') }}">
                                                            <option value="active">Hoạt động</option>
                                                            <option value="inactive">Không hoạt động</option>
                                                            <option value="on_leave">Nghỉ phép</option>
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
                                                            value="{{ old('insurance_number') }}" class="form-control"
                                                            placeholder="Nhập số bảo hiểm">
                                                    </div>
                                                </li>

                                                <li><strong>Kiểu hợp đồng : </strong>
                                                    <div class="form-group">
                                                        <select name="contract_type" value="{{ old('contract_type') }}">
                                                            <option value="Part-time">Part-time</option>
                                                            <option value="Full-time">Full-time</option>
                                                            <option value="Contract">Contract</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li><strong>Ngày bắt đầu hợp đồng : </strong>
                                                    <div class="form-group">
                                                        <input type="date" name="contract_start"
                                                            value="{{ old('contract_start') }}">
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
                                                        <select name="role" value="{{ old('role') }}">
                                                            <option value="employee">Nhân viên</option>
                                                            <option value="manager">Quản lý nhân viên</option>
                                                            <option value="admin">Quản trị viên</option>
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
                                                            value="{{ old('contract_end') }}">
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
                                                            value="{{ old('date_hired') }}">
                                                    </div>
                                                </li>
                                                <li>
                                                    <strong>Ghi chú : </strong>
                                                    <div class="form-group">
                                                        <textarea rows="2" name="notes" class="form-control" placeholder="Ghi chú...">{{ old('notes') }}</textarea>

                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Chọn Phòng Quản Lý : </strong>
                                                    <!-- Thêm dropdown chọn phòng -->
                                                    <select name="room_ids[]" class="form-control select2" multiple="multiple">
                                                        @foreach ($rooms as $room)
                                                            <option value="{{ $room->id }}" {{ $room->manager_id ? 'disabled' : '' }}>
                                                                {{ $room->name }} @if ($room->manager_id)
                                                                    (Đã có người quản lý)
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </li>
                                                <li>
                                                    <button type="submit" class="lh-btn-primary">Thêm Nhân Viên</button>
                                                </li>
                                            </ul>
                                        </div>
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
