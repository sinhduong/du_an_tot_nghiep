@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Phần trăm đặt cọc</h5>
                <form action="{{ route('admin.payment-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="deposit_percentage">Phần trăm đặt cọc (%)</label>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <input type="number" class="form-control" id="deposit_percentage"
                                name="deposit_percentage"
                                value="{{ $setting->deposit_percentage ?? 50 }}"
                                min="1" max="99" step="1">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection