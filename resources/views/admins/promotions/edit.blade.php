@extends("layouts.admin")

@section("content")
    <div class="lh-main-content">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Khuyến mãi</h5>
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li>Sửa khuyến mãi</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="m-auto">
                            <div class="card">
                                <div class="card-body" data-select2-id="select2-data-32-f94z">
                                    <div class="card-header-2">
                                        <h5>{{ $isEdit ? "Cập nhật" : "Chi tiết" }} mã giảm giá</h5>
                                    </div>

                                    <form class="theme-form theme-form-2 mega-form"
                                          action="{{ route("admin.promotions.update", $promotion->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Tên chương trình giảm
                                                giá <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="name"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{ $promotion->name }}"
                                                       placeholder="Ví dụ: Lễ tình nhân, ...">
                                                @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Mã giảm giá <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="text" name="code"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{$promotion->code}}"
                                                       placeholder="Nhập mã giảm giá">
                                                @error('code')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Giá trị giảm giá <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" step="0.01"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{ $promotion->value }}"
                                                       name="value" placeholder="Nhập giá trị giảm">
                                                @error('value')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Ngày bắt đầu <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="datetime-local"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{$promotion->start_date}}"
                                                       name="start_date">
                                                @error('start_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Ngày kết thúc <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="datetime-local"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{$promotion->end_date}}"
                                                       name="end_date">
                                                @error('end_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Giá trị đơn hàng tối
                                                thiểu <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" name="min_booking_amount"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{$promotion->min_booking_amount}}"
                                                       placeholder="Nhập giá trị tối thiểu">
                                                @error('min_booking_amount')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Giá trị giảm trừ tối
                                                đa <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" name="max_discount_value"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{ $promotion->max_discount_value}}"
                                                       placeholder="Nhập giá trị tối đa">
                                                @error('max_discount_value')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Số lượng mã giảm giá <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input class="form-control" type="number" name="quantity"
                                                       {{ $isEdit ? "" : "disabled" }} value="{{$promotion->quantity}}"
                                                       placeholder="Nhập số lượng mã">
                                                @error('quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Loại giảm giá <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="type">
                                                    <option
                                                        {{ $promotion->type == 'percent' ? 'selected' : "" }} {{ $isEdit ? "" : "disabled" }} value="percent">
                                                        Phần trăm
                                                    </option>
                                                    <option
                                                        {{ $promotion->type == 'fixed' ? 'selected' : "" }} {{ $isEdit ? "" : "disabled" }} value="fixed">
                                                        Số tiền cố định
                                                    </option>
                                                </select>
                                                @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">Trạng thái <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="status">
                                                    <option
                                                        {{ $promotion->status == 'active' ? 'selected' : "" }} {{ $isEdit ? "" : "disabled" }} value="active">
                                                        Hoạt động
                                                    </option>
                                                    <option
                                                        {{ $promotion->status == 'inactive' ? 'selected' : "" }} {{ $isEdit ? "" : "disabled" }} value="inactive">
                                                        Không hoạt động
                                                    </option>
                                                </select>
                                                @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            @if($isEdit)
                                                <button class="btn btn-primary" type="submit">Lưu</button>
                                            @else
                                                <button class="btn btn-info"><a class="text-white"
                                                                                href="{{ route('admin.promotions.index') }}">Trở
                                                        lại</a></button>
                                            @endif
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
