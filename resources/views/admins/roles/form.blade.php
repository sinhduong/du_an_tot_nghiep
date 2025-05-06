@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>{{ isset($role) ? 'Chỉnh sửa vai trò' : 'Tạo vai trò' }}</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li><a href="{{ route('admin.roles.index') }}">Danh sách vai trò</a></li>
                        <li>{{ isset($role) ? 'Chỉnh sửa' : 'Tạo mới' }}</li>
                    </ul>
                </div>
                <div class="lh-tools">
                    <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                    <div id="pagedate">
                        <div class="lh-date-range" title="Date">
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">{{ isset($role) ? 'Chỉnh sửa vai trò' : 'Tạo vai trò' }}</h4>
                        </div>
                        <div class="lh-card-content card-default">
                            <form method="POST" action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}" class="p-4">
                                @csrf
                                @if(isset($role))
                                    @method('PUT')
                                @endif
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-md-2 col-12 mb-0">Tên vai trò <span class="text-danger">*</span></label>
                                    <div class="col-md-10 col-12">
                                        <input class="form-control" type="text" name="name" placeholder="Nhập tên vai trò" value="{{ old('name', $role->name ?? '') }}" required>
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h4 class="form-label-title">Quyền hạn</h4>
                                </div>

                                <div class="row g-sm-4 g-2">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-check p-0">
                                                    <input class="form-check-input checkall" type="checkbox" id="selectAll">
                                                    <label class="form-check-label" for="selectAll">Chọn tất cả</label>
                                                </div>
                                            </div>
                                            @foreach ($permission_groups as $groupIndex => $permission_group)
                                                <div class="col-12 mt-3 permission-group">
                                                    <div class="form-check group-header ">
                                                        <input class="form-check-input checkall-group" type="checkbox" data-group="{{ $groupIndex }}" id="group{{ $groupIndex }}">
                                                        <label class="form-check-label" for="group{{ $groupIndex }}">{{ __('permissions.section.' . $permission_group[0]['section']) }} - Tất cả</label>
                                                    </div>
                                                    <div class="permission-items mt-2 ms-4">
                                                        @foreach ($permission_group as $permission)
                                                            <div class="form-check permission-item">
                                                                <input class="form-check-input check-it" type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-group="{{ $groupIndex }}" id="perm{{ $permission->id }}" {{ isset($role) && $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="perm{{ $permission->id }}">{{__($permission->name)}}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.roles.index') }}" class="btn btn-warning">Trở lại</a>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .permission-group {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        
        .group-header {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
        }
        
        .group-header .form-check-label {
            font-weight: 600;
            color: #495057;
        }
        
        .permission-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 10px;
        }
        
        .permission-item {
            padding: 5px;
            background: white;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .permission-item:hover {
            background: #f1f3f5;
        }
        
        .permission-item .form-check-label {
            color: #6c757d;
        }
        
        .form-check-input:checked + .form-check-label {
            color: #0d6efd;
        }
        
        .form-check-input {
            cursor: pointer;
            padding: 8px !important;
        }
        
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check .form-check-input{
            margin-left: 0 !important;
            float: unset !important;
        }
    </style>
    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            document.querySelectorAll('.check-it, .checkall-group').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.querySelectorAll('.checkall-group').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function () {
                const groupIndex = this.getAttribute('data-group');
                document.querySelectorAll(`.check-it[data-group="${groupIndex}"]`).forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        });
    </script>
@endsection
