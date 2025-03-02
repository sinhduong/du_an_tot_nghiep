@extends('layouts.admin')

@section('title', 'Quản Lý Ca Làm Việc')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
                <div class="lh-breadcrumb">
                    <div class="container">
                        <h1 class="mt-4">Quản Lý Ca Làm Việc</h1>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <a href="{{ route('admin.staff_shifts.create') }}" class="btn btn-primary mb-3">Thêm Ca Mới</a>

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Ca</th>
                                    <th>Giờ Bắt Đầu</th>
                                    <th>Giờ Kết Thúc</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff_shifts as $shift)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $shift->name }}</td>
                                        <td>{{ $shift->start_time }}</td>
                                        <td>{{ $shift->end_time }}</td>
                                        <td>
                                            <a href="{{ route('admin.staff_shifts.edit', $shift->id) }}"
                                                class="btn btn-warning btn-sm">Sửa</a>

                                            <form action="{{ route('admin.staff_shifts.destroy', $shift->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa ca này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
