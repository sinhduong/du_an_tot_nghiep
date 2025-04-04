@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Khuyến mãi</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Danh sách Khuyến mãi</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách mã giảm giá</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                        class="ri-fullscreen-line" title="Full Screen"></i></a>
                                <button class="btn btn-primary ms-2"
                                        onclick="window.location.href='{{ route('admin.promotions.create') }}'">
                                    Tạo mới
                                </button>
                            </div>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="lh-card-content card-default">
                            <div class="booking-table">
                                <div class="table-responsive">
                                    <table id="booking_table" class="table table-striped table-hover">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên khuyến mãi</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Hành động</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($promotions as $index => $promotion)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $promotion->name }}</td>
                                                <td>
                                                    <span
                                                        class="text-{{$promotion->status ==='active' ? 'success' : 'danger'}}">{{ $promotion->status == 'active' ? 'Hoạt động' : 'Không hoạt động'}}</span>
                                                </td>
                                                <td>{{ $promotion->start_date }}</td>
                                                <td>{{ $promotion->end_date }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                            <i class="ri-settings-3-line"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.promotions.edit', $promotion->id) }}">
                                                                    <i class="ri-edit-line"></i> Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   href="{{ route('admin.promotions.show', $promotion->id) }}">
                                                                    <i class="ri-eye-line"></i> Chi tiết
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.promotions.destroy', $promotion->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Bạn có muốn xóa mềm không?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="dropdown-item text-danger">
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
    </div>
@endsection
