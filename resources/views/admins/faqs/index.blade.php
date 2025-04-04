<!-- resources/views/admin/faqs/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>FAQs</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li>Danh sách FAQs</li>
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
                                <li><a class="dropdown-item" href="#">Hoạt động</a></li>
                                <li><a class="dropdown-item" href="#">Không hoạt động</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card" id="faqtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Danh sách FAQs</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                        class="ri-fullscreen-line" title="Full Screen"></i></a>
                                <div class="lh-date-range dots">
                                    <span></span>
                                </div>
                                <button class="btn btn-primary ms-2"
                                        onclick="window.location.href='{{ route('admin.faqs.create') }}'">
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
                                <div class="col-sm-5"><label>Nhập câu hỏi:</label></div>
                                <div class="col-sm-7">
                                    <input type="text" name="question" class="form-control" value="{{ request('question') ?? '' }}"
                                           aria-controls="table_id">
                                </div>
                            </div>
                            <div class="d-flex col-sm-12 col-md-3 gap-2 mt-3 mt-md-0">
                                <label class="mt-2">Xem</label>
                                <select name="size" class="form-select">
                                    <option {{ request('size') == 20 ? 'selected' : '' }} value="20">20</option>
                                    <option {{ request('size') == 50 ? 'selected' : '' }} value="50">50</option>
                                    <option {{ request('size') == 100 ? 'selected' : '' }} value="100">100</option>
                                    <option {{ request('size') == 200 ? 'selected' : '' }} value="200">200</option>
                                </select>
                                <label class="mt-2">mục</label>
                            </div>
                            <div class="col-md-2 col-sm-12 d-flex gap-2 mt-md-0 mt-3">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('admin.faqs.index') }}" class="btn btn-warning">Bỏ lọc</a>
                            </div>
                        </form>
                        <div class="lh-card-content card-default">
                            <div class="faq-table">
                                <div class="table-responsive" style="min-height: 200px">
                                    <table id="faq_table" class="table table-striped table-hover">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>STT</th>
                                            <th>Câu hỏi</th>
                                            <th>Trạng thái</th>
                                            <th>Hình ảnh</th>
                                            <th>Ngày tạo</th>
                                            <th>Hành động</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($faqs as $index => $faq)
                                            <tr>
                                                <td class="text-center">{{ $index + $faqs->firstItem() }}</td>
                                                <td>{{ $faq->question }}</td>
                                                <td>
                                                    <span class="text-{{ $faq->is_active ? 'success' : 'danger' }}">
                                                        {{ $faq->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                                    </span>
                                                </td>
                                            
                                                <td>
                                                    @php
                                                        // Đảm bảo rằng đường dẫn ảnh có đầy đủ thư mục (nếu cần)
                                                        $imagePath = $faq->image;
                                                        // echo $imagePath ;
                                                    @endphp
                                                    {{-- @if ($faq->image && Storage::disk('public')->exists($faq->image))
                                                        <img src="{{ Storage::url($faq->image) }}" width="100"
                                                            height="100" alt="{{ $faq->name }}"
                                                            class="img-thumbnail">
                                                    @else --}}
                                                    @if (!empty($faq->image))
                                                    <img src="{{ asset('storage/' . $faq->image) }}" width="120px" alt="Product Image">

                                                    {{-- <img src="{{ Storage::url($faq->image) }}"
                                                         width="100" height="100" alt="{{ $faq->name }}"
                                                         class="img-thumbnail"> --}}
                                                    @else
                                                        <small>Chưa có</small>
                                                    @endif
                                                </td>
                                                <td>{{ $faq->created_at->format('d/m/Y H:i') }}</td>
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
                                                                   href="{{ route('admin.faqs.edit', $faq) }}">
                                                                    <i class="ri-edit-line"></i> Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.faqs.destroy', $faq) }}"
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
                        <div class="card-footer">
                            {{ $faqs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
