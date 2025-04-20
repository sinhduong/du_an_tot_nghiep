@extends('admins.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Phê duyệt yêu cầu hoàn tiền</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Thông tin đặt phòng</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Mã đặt phòng:</th>
                                    <td>{{ $refund->booking->booking_code }}</td>
                                </tr>
                                <tr>
                                    <th>Khách sạn:</th>
                                    <td>{{ $refund->booking->hotel->name }}</td>
                                </tr>
                                <tr>
                                    <th>Phòng:</th>
                                    <td>{{ $refund->booking->room->name }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày nhận phòng:</th>
                                    <td>{{ $refund->booking->check_in_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày trả phòng:</th>
                                    <td>{{ $refund->booking->check_out_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td>{{ number_format($refund->booking->total_amount) }} VNĐ</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Thông tin hoàn tiền</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Lý do hoàn tiền:</th>
                                    <td>{{ $refund->reason }}</td>
                                </tr>
                                <tr>
                                    <th>Tiền đã thanh toán:</th>
                                    <td>{{ $refund->booking->paid_amount }}</td>
                                </tr>
                                <tr>
                                    <th>Chính sách hoàn tiền:</th>
                                    <td>{{ $refund->policy->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tỷ lệ hoàn tiền:</th>
                                    <td>{{ $refund->policy->refund_percentage }}%</td>
                                </tr>
                                <tr>
                                    <th>Số tiền hoàn:</th>
                                    <td>{{ number_format($refund->refund_amount) }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <span class="badge badge-warning">Đang chờ phê duyệt</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('refunds.approve', $refund->id) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="form-group">
                            <label for="admin_note">Ghi chú của quản trị viên</label>
                            <textarea name="admin_note" id="admin_note" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" name="action" value="approve">
                                <i class="fas fa-check"></i> Phê duyệt
                            </button>
                            <button type="submit" class="btn btn-danger" name="action" value="reject">
                                <i class="fas fa-times"></i> Từ chối
                            </button>
                            <a href="{{ route('admins.bookings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 