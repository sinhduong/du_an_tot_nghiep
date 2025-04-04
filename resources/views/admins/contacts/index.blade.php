@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Danh sách Liên hệ</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Liên hệ</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách Liên hệ</h4>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="lh-card-content card-default">
                            <div class="table-responsive" style="min-height: 200px">
                                <table id="booking_table" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th>Tiêu đề</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($contacts as $index => $contact)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ Str::limit($contact->title, 30) }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>
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
                                            </td>
                                            <td>{{ $contact->created_at->diffForHumans() }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.contacts.show', $contact->id) }}">
                                                                <i class="ri-eye-line"></i> Chi tiết
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                {{ $contacts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
