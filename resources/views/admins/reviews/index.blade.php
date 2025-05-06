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
                        <li>Đánh giá</li>
                    </ul>
                </div>

            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">{{ $title }}</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                    class="ri-fullscreen-line" title="Full Screen"></i></a>


                            </div>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="lh-card-content card-default">
                            <div class="booking-table">
                                <div class="table-responsive">
                                    <table id="booking_table" class="table">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Người dùng</th>
                                                <th>Đơn đặt phòng</th>
                                                <th>Số sao</th>
                                                <th>Nội dung</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviews as $review)
                                                <tr>
                                                    <td>{{ $review->id }}</td>
                                                    <td>{{ $review->user->name }}</td>
                                                    <td>
                                                        @if ($review->booking)
                                                            Mã đặt phòng: {{ $review->booking->id }} <br>
                                                            Ngày đặt: {{ $review->booking->created_at->format('d/m/Y') }}
                                                        @else
                                                            Không có thông tin đơn đặt phòng
                                                        @endif
                                                    </td>
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
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                <i class="ri-settings-3-line"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="{{ route('admin.reviews.show', $review->id) }}"
                                                                        class="dropdown-item">
                                                                        <i class="ri-eye-line"></i> Xem
                                                                    </a>
                                                                </li>
                                                                {{-- <li>
                                                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="ri-delete-bin-line"></i> Xóa
                                                                        </button>
                                                                    </form>
                                                                </li> --}}
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
