@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Nhân viên đã xóa</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Danh sách nhân viên đã xóa</li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách nhân viên đã xóa</h4>
                            <div class="header-tools">
                                <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.staffs.index') }}'">
                                    Quay lại danh sách
                                </button>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="lh-card-content card-default">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Điện thoại</th>
                                            <th>Email</th>
                                            <th>Chức vụ</th>
                                            <th>Ca làm</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staffs as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->user->phone }}</td>
                                                <td>{{ $item->user->email }}</td>
                                                <td>{{ $item->role->name }}</td>
                                                <td>{{ $item->shift->name }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <!-- Khôi phục -->
                                                        <form action="{{ route('admin.staffs.restore', $item->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </button>
                                                        </form>

                                                        <!-- Xóa vĩnh viễn -->
                                                        <form action="{{ route('admin.staffs.forceDelete', $item->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?');">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($staffs->isEmpty())
                            <p class="text-center mt-3">Không có nhân viên nào bị xóa.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
