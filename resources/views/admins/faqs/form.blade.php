<!-- resources/views/admin/faqs/form.blade.php -->
@extends("layouts.admin")

@section("content")
    <div class="lh-main-content">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>{{ isset($faq) ? 'Cập nhật FAQ' : 'Thêm mới FAQ' }}</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li><a href="{{ route('admin.faqs.index') }}">Danh sách FAQ</a></li>
                    <li>{{ isset($faq) ? 'Cập nhật' : 'Thêm mới' }}</li>
                </ul>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header-2">
                                <h5>Thông tin FAQ</h5>
                            </div>

                            <form class="theme-form theme-form-2 mega-form"
                                  action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                @if(isset($faq))
                                    @method('PUT')
                                @endif

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Câu hỏi</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" name="question"
                                               value="{{ old('question', $faq->question ?? '') }}"
                                               placeholder="Nhập câu hỏi">
                                        @error('question')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Câu trả lời</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="answer" rows="5"
                                                  placeholder="Nhập câu trả lời">{{ old('answer', $faq->answer ?? '') }}</textarea>
                                        @error('answer')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Hình ảnh</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="file" name="image">
                                        @if(isset($faq) && $faq->image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $faq->image) }}" alt="{{ $faq->question }}" style="max-width: 200px; height: auto;">
                                                <div class="mt-2">
                                                    <label>
                                                        <input type="checkbox" name="delete_image"> Xóa hình ảnh hiện tại
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row align-items-center">
                                    <label class="form-label-title col-sm-3 mb-0">Trạng thái</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="is_active">
                                            <option value="1" {{ old('is_active', $faq->is_active ?? true) ? 'selected' : '' }}>
                                                Hoạt động
                                            </option>
                                            <option value="0" {{ old('is_active', $faq->is_active ?? true) == 0 ? 'selected' : '' }}>
                                                Không hoạt động
                                            </option>
                                        </select>
                                        @error('is_active')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Quay lại</a>
                                    <button class="btn btn-primary" type="submit">
                                        {{ isset($faq) ? 'Cập nhật' : 'Lưu' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
