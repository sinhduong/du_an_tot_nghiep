@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>{{ isset($about) ? 'Chỉnh sửa trang Về chúng tôi' : 'Tạo trang Về chúng tôi' }}</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                        <li><a href="{{ route('admin.abouts.index') }}">Danh sách trang Về chúng tôi</a></li>
                        <li>{{ isset($about) ? 'Chỉnh sửa' : 'Tạo mới' }}</li>
                    </ul>
                </div>
                <div class="lh-tools">
                    <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                    <div id="pagedate">
                        <div class="lh-date-range" title="Date"><span></span></div>
                    </div>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">{{ isset($about) ? 'Chỉnh sửa trang Về chúng tôi' : 'Tạo trang Về chúng tôi' }}</h4>
                        </div>
                        <div class="lh-card-content card-default">
                            <form method="POST" action="{{ isset($about) ? route('admin.abouts.update', $about->id) : route('admin.abouts.store') }}" class="p-4">
                                @csrf
                                @if(isset($about))
                                    @method('PUT')
                                @endif
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-md-2 col-12 mb-0">Nội dung <span class="text-danger">*</span></label>
                                    <div class="col-md-10 col-12">
                                        <textarea class="form-control" name="about" rows="5" placeholder="Nhập nội dung trang Về chúng tôi" id="editor" required>{{ old('about', $about->about ?? '') }}</textarea>
                                        @error('about')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-md-2 col-12 mb-0">Trạng thái <span class="text-danger">*</span></label>
                                    <div class="col-md-10 col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_use" id="isUse1" value="1" {{ old('is_use', $about->is_use ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isUse1">Đang sử dụng</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_use" id="isUse0" value="0" {{ !old('is_use', $about->is_use ?? 0) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isUse0">Không sử dụng</label>
                                        </div>
                                        @error('is_use')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.abouts.index') }}" class="btn btn-warning">Trở lại</a>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
