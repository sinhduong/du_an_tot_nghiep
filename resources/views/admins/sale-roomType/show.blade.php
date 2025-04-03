@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <h5>Chi tiết mối quan hệ Loại phòng - Khuyến mãi</h5>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Thông tin chi tiết</h4>
                        </div>
                        <div class="lh-card-content">
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Loại phòng:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ $saleRoomType->roomType->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Khuyến mãi:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ $saleRoomType->name }} ({{ $saleRoomType->value }}{{ $saleRoomType->type == 'percent' ? '%' : 'VND' }})</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày giờ bắt đầu:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ $saleRoomType->start_date ? \Carbon\Carbon::parse($saleRoomType->start_date)->format('d/m/Y H:i:s') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày giờ kết thúc:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ $saleRoomType->end_date ? \Carbon\Carbon::parse($saleRoomType->end_date)->format('d/m/Y H:i:s') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Trạng thái:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0 {{ $saleRoomType->status == 'inactive' ? 'text-danger' : '' }}">
                                        {{ $saleRoomType->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày tạo:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ \App\Helpers\FormatHelper::formatDateTime($saleRoomType->created_at) }}</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày cập nhật:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ \App\Helpers\FormatHelper::formatDateTime($saleRoomType->updated_at) }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.sale-room-types.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
