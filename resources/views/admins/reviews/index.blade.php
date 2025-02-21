@extends('layouts.admin')
@section('content')


    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Đánh giá</h5>
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
                                <li><a class="dropdown-item" href="#">Booking</a></li>
                                <li><a class="dropdown-item" href="#">Revenue</a></li>
                                <li><a class="dropdown-item" href="#">Expence</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i class="ri-fullscreen-line"
                                        title="Full Screen"></i></a>
                                <div class="lh-date-range dots">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="lh-card-content card-default">
                            <div class="booking-table">
                                <div class="table-responsive">
                                    <table id="booking_table" class="table">
                                        <thead>

                                            <tr>
                                                <th>ID</th>
                                                <th>Người dùng</th>
                                                <th>Phòng</th>
                                                <th>Số sao</th>
                                                <th>Bình luận</th>
                                                <th>Phản hồi</th>
                                                <th>Chức năng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>{{ $review->user->name }}</td>
                                        <td>{{ $review->room->name }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="ri-star-fill text-warning"></i>
                                                @else
                                                    <i class="ri-star-line text-muted"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>{{ $review->comment }}</td>
                                        <td>
                                            @if ($review->response)
                                                {{ $review->response }}
                                            @else
                                                <form action="{{ route('admin.reviews.response', $review->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="text" name="response" class="form-control" required>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary mt-1">Gửi</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-settings-3-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.reviews.show', $review->id) }}"
                                                            class="btn btn-primary">Xem</a>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.reviews.destroy', $review->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
    </div>




{{-- <div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-card">
            <div class="lh-card-header">

            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="lh-card-content card-default">
                <div class="booking-table">
                    <div class="table-responsive">
                        <table id="review_table" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người dùng</th>
                                    <th>Phòng</th>
                                    <th>Số sao</th>
                                    <th>Bình luận</th>
                                    <th>Phản hồi</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>{{ $review->user->name }}</td>
                                        <td>{{ $review->room->name }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="ri-star-fill text-warning"></i>
                                                @else
                                                    <i class="ri-star-line text-muted"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>{{ $review->comment }}</td>
                                        <td>
                                            @if ($review->response)
                                                {{ $review->response }}
                                            @else
                                                <form action="{{ route('admin.reviews.response', $review->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="text" name="response" class="form-control" required>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary mt-1">Gửi</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="ri-settings-3-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.reviews.show', $review->id) }}"
                                                            class="btn btn-primary">Xem</a>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('admin.reviews.destroy', $review->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
</div> --}}
@endsection
