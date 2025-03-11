@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Dịch vụ bổ sung</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Quản lý dịch vụ bổ sung</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.service_plus.create') }}'">
                    Tạo mới
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="servicestbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                    </div>
                    <!-- Thêm SweetAlert để hiển thị thông báo -->
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: '{{ session('success') }}',
                                confirmButtonText: 'OK'
                            });
                        </script>
                    @endif
                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: '{{ session('error') }}',
                                confirmButtonText: 'OK'
                            });
                        </script>
                    @endif
                    <div class="lh-card-content card-default">
                        <div class="table-responsive" style="min-height: 200px">
                            <table id="service_table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên dịch vụ</th>
                                        <th>Giá dịch vụ</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $index => $service)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ $service->price }}</td>
                                        <td>
                                            <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $service->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ri-settings-3-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.service_plus.edit', $service->id) }}">
                                                            <i class="ri-edit-line"></i> Sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.service_plus.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa không?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ri-delete-bin-line"></i> Xóa
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
