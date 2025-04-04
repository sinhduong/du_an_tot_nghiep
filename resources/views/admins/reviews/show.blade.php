@extends('layouts.admin')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
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
    <div class="lh-main-content">
        <div class="container-fluid">
            <h2>Chi tiết đánh giá</h2>

            <p><strong>Người gửi:</strong> {{ $review->user->name }}</p>
            <p><strong>Đơn đặt phòng:</strong>
                @if ($review->booking)
                    Mã đặt phòng: {{ $review->booking->id }} <br>
                    Ngày đặt: {{ $review->booking->created_at->format('d/m/Y H:i:s') }}
                @else
                    Không có thông tin đơn đặt phòng
                @endif
            </p>
            <p><strong>Số sao:</strong> {{ $review->rating }} ⭐</p>
            <p><strong>Nội dung:</strong> {{ $review->comment ?? 'Không có bình luận' }}</p>

            @if ($review->response)
                <p><strong>Phản hồi từ Admin:</strong> {{ $review->response }}</p>
            @else
                <form action="{{ route('admin.reviews.response', $review->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Nhập phản hồi</label>
                        <textarea name="response" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Gửi phản hồi</button>
                </form>
            @endif

            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
@endsection
