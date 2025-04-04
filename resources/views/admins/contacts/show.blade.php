@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Chi tiết Liên hệ</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.contacts.index') }}">Danh sách Liên hệ</a></li>
                        <li>Chi tiết</li>
                    </ul>
                </div>
                
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Chi tiết Liên hệ</h4>
                        </div>
                        <div class="lh-card-content card-default">
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
                            <div class="p-4">
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Tiêu đề:</label>
                                    <div class="col-md-10 col-12">{{ $contact->title }}</div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Email:</label>
                                    <div class="col-md-10 col-12">{{ $contact->email }}</div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Số điện thoại:</label>
                                    <div class="col-md-10 col-12">{{ $contact->phone }}</div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Nội dung:</label>
                                    <div class="col-md-10 col-12">{!! $contact->content !!}</div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Trạng thái:</label>
                                    <div class="col-md-10 col-12">
                                        <span class="text-{{ $contact->status == 'approved' ? 'success' : ($contact->status == 'rejected' ? 'danger' : 'warning') }}">
                                            @if ($contact->status == 'pending')
                                                Đang chờ
                                            @elseif ($contact->status == 'approved')
                                                Đã duyệt
                                            @elseif ($contact->status == 'rejected')
                                                Đã từ chối
                                            @else
                                                {{ ucfirst($contact->status) }} <!-- Giữ nguyên nếu có trạng thái khác -->
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-12 fw-bold">Ngày tạo:</label>
                                    <div class="col-md-10 col-12">{{ $contact->created_at->diffForHumans() }}</div>
                                </div>

                                @if ($contact->status == 'pending')
                                    <form method="POST" action="{{ route('admin.contacts.reply', $contact->id) }}" class="mt-4">
                                        @csrf
                                        <div class="row mb-3">
                                            <label class="col-md-2 col-12 fw-bold">Phản hồi:</label>
                                            <div class="col-md-10 col-12">
                                                <textarea class="form-control" name="reply_content" rows="5" placeholder="Nhập nội dung phản hồi" required></textarea>
                                                @error('reply_content')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.contacts.index') }}" class="btn btn-warning">Trở lại</a>
                                            <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
