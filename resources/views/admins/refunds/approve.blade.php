@extends('admins.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Phê duyệt hoàn tiền</div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Thông tin đặt phòng</h5>
                            <p>Mã đặt phòng: {{ $refund->booking->booking_code }}</p>
                            <p>Ngày check-in: {{ $refund->booking->check_in }}</p>
                            <p>Ngày check-out: {{ $refund->booking->check_out }}</p>
                            <p>Số tiền đã thanh toán: {{ number_format($refund->booking->paid_amount) }} VNĐ</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Thông tin hoàn tiền</h5>
                            <p>Chính sách: {{ $refund->policy->name }}</p>
                            <p>Tỷ lệ hoàn tiền: {{ $refund->policy->refund_percentage }}%</p>
                            <p>Phí hủy phòng: {{ $refund->policy->cancellation_fee_percentage }}%</p>
                            <p>Số tiền hoàn trả: {{ number_format($refund->amount) }} VNĐ</p>
                            <p>Phí hủy phòng: {{ number_format($refund->cancellation_fee) }} VNĐ</p>
                        </div>
                    </div>

                    <form action="{{ route('refunds.approve', $refund->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="refund_method">Phương thức hoàn tiền</label>
                            <select name="refund_method" id="refund_method" class="form-control @error('refund_method') is-invalid @enderror" required>
                                <option value="">Chọn phương thức</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="cash">Tiền mặt</option>
                            </select>
                            @error('refund_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="transaction_id">Mã giao dịch</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" required>
                            @error('transaction_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="admin_notes">Ghi chú</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror"></textarea>
                            @error('admin_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary">Phê duyệt hoàn tiền</button>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
