@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Khuyến mãi</h5>
                    <ul>
                        <li><a href="index.html">Trang chủ</a></li>
                        <li>Dashboard</li>
                    </ul>
                </div>
                <div class="lh-tools">
                    <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                    <div id="pagedate">
                        <div class="lh-date-range" title="Date">
                            <span></span>
                        </div>
                    </div>
                    <div class="filter">
                        <div class="dropdown" title="Filter">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-sound-module-line"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Đang diễn ra</a></li>
                                <li><a class="dropdown-item" href="#">Sắp diễn ra</a></li>
                                <li><a class="dropdown-item" href="#">Đã kết thúc</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="promotiontbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách mã giảm giá</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                        class="ri-fullscreen-line" title="Full Screen"></i></a>
                                <div class="lh-date-range dots">
                                    <span></span>
                                </div>
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
                        <form method="GET" class="row p-4">
                            <div class="col-md-4 col-sm-12 d-flex">
                                <div class="col-sm-5"><label>Nhập tên chươnh trình :</label></div>
                                <div class="col-sm-7">
                                    <input type="text" name="name" class="form-control" value="{{ $_GET['name'] ?? "" }}"
                                           aria-controls="table_id">
                                </div>
                            </div>
                            <div class="d-flex col-sm-12 col-md-3 gap-2 mt-3 mt-md-0">
                                <label class="mt-2">Xem</label>
                                <select name="size" class="form-select">
                                    <option
                                        {{ isset($_GET['size']) && $_GET['size'] == 20 ? "selected" : "" }} value="20">
                                        20
                                    </option>
                                    <option
                                        {{ isset($_GET['size']) && $_GET['size'] == 50 ? "selected" : "" }} value="50">
                                        50
                                    </option>
                                    <option
                                        {{ isset($_GET['size']) && $_GET['size'] == 100 ? "selected" : "" }} value="100">
                                        100
                                    </option>
                                    <option
                                        {{ isset($_GET['size']) && $_GET['size'] == 200 ? "selected" : "" }} value="200">
                                        200
                                    </option>
                                </select>
                                <label class="mt-2">mục</label>
                            </div>
                            <div class="col-md-2 col-sm-12 d-flex gap-2 mt-md-0 mt-3">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route("admin.promotions.index") }}" class="btn btn-warning">Bỏ lọc</a>
                            </div>
                        </form>
                        <div class="lh-card-content card-default">
                            <div class="promotion-table">
                                <div class="table-responsive" style="min-height: 200px">
                                    <table id="promotion_table" class="table table-striped table-hover">
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
