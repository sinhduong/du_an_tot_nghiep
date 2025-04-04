@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <div class="lh-page-title">
                <h5>Chi tiết Tiện nghi</h5>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="lh-card">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">Thông tin chi tiết</h4>
                        </div>
                        <div class="lh-card-content">
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Tên tiện nghi:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">{{ $amenity->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Loại phòng áp dụng:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">
                                        @if($amenity->roomTypes->isNotEmpty())
                                            @foreach($amenity->roomTypes as $roomType)
                                                {{ $roomType->name }}<br>
                                            @endforeach
                                        @else
                                            Không có loại phòng
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Trạng thái:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0 {{ $amenity->is_active == 0 ? 'text-danger' : '' }}">
                                        {{ $amenity->is_active == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày tạo:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">
                                        {{ $amenity->created_at ? \Carbon\Carbon::parse($amenity->created_at)->format('d/m/Y H:i:s') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0"><strong>Ngày cập nhật:</strong></label>
                                <div class="col-sm-9">
                                    <p class="mb-0">
                                        {{ $amenity->updated_at ? \Carbon\Carbon::parse($amenity->updated_at)->format('d/m/Y H:i:s') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.amenities.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
