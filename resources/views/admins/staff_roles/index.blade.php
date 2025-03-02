@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title d-flex justify-content-between align-items-center mb-3">
                <div class="lh-breadcrumb">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                    <h1>Danh sách Vai Trò Nhân Viên</h1>
                    <a href="{{ route('admin.staff_roles.create') }}" class="btn btn-primary">Thêm Vai Trò</a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Vai Trò</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.staff_roles.edit', $role->id) }}"
                                            class="btn btn-warning">Sửa</a>
                                        <form action="{{ route('admin.staff_roles.destroy', $role->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
