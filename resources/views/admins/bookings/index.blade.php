@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Đặt phòng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    <div class="lh-date-range" title="Date">
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">{{ $title }}</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i class="ri-fullscreen-line" title="Full Screen"></i></a>
                        </div>
                    </div>

                    <!-- Form lọc -->
                    <div class="lh-card-content">
                        <form method="GET" action="{{ route('admin.bookings.index') }}" id="filterForm">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Ngày bắt đầu</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $filterData['start_date'] ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Ngày kết thúc</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $filterData['end_date'] ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Lọc nhanh</label>
                                    <select name="filter" class="form-control" onchange="clearDateInputs()">
                                        <option value="">Chọn khoảng thời gian</option>
                                        <option value="today" {{ $filterData['filter'] == 'today' ? 'selected' : '' }}>Hôm nay</option>
                                        <option value="this_week" {{ $filterData['filter'] == 'this_week' ? 'selected' : '' }}>Tuần này</option>
                                        <option value="this_month" {{ $filterData['filter'] == 'this_month' ? 'selected' : '' }}>Tháng này</option>
                                        <option value="last_month" {{ $filterData['filter'] == 'last_month' ? 'selected' : '' }}>Tháng trước</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Trạng thái</label>
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="confirmed" {{ $filterData['status'] == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="paid" {{ $filterData['status'] == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="check_in" {{ $filterData['status'] == 'check_in' ? 'selected' : '' }}>Đã check in</option>
                                        <option value="check_out" {{ $filterData['status'] == 'check_out' ? 'selected' : '' }}>Đã checkout</option>
                                        <option value="cancelled" {{ $filterData['status'] == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        <option value="refunded" {{ $filterData['status'] == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Áp dụng</button>
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Xóa bộ lọc</a>
                                </div>
                            </div>
                        </form>

                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Mã</th>
                                            <th>Khách hàng</th>
                                            <th>Phòng</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Tổng giá</th>
                                            <th>Đã trả</th>
                                            <th>Hoàn tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $index => $booking)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $booking->booking_code }}</td>
                                            <td>
                                                <small> Người đặt : {{ $booking->user->name ?? 'Không xác định' }}</small>
                                                @if ($booking->guests->isNotEmpty())
                                                <br>
                                                <small>
                                                    Người ở:
                                                    @foreach ($booking->guests as $key => $guest)
                                                    {{ $guest->name }}{{ $key < count($booking->guests) - 1 ? ', ' : '' }}
                                                    @endforeach
                                                </small>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($booking->rooms as $keyI => $room)
                                                <span>{{ $room->room_number }}</span>
                                                @if ($keyI < count($booking->rooms) - 1)
                                                    ,
                                                    @endif
                                                    @endforeach
                                            </td>
                                            <td>{{ \App\Helpers\FormatHelper::formatDate($booking->check_in) }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatDate($booking->check_out) }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatPrice($booking->total_price) }}</td>
                                            <td>{{ \App\Helpers\FormatHelper::formatPrice($booking->paid_amount) }}</td>
                                            <td>
                                                @if($booking->refund && $booking->refund->status === 'pending')
                                                    <button type="button" class="btn btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#processRefundRequestModal"
                                                            data-refund-id="{{ $booking->refund->id }}">
                                                        Chờ xử lý
                                                    </button>
                                                @elseif($booking->refund && $booking->refund->status === 'approved' && $booking->refund->amount > 0)
                                                    <span class="badge bg-success">Đã hoàn tiền</span>
                                                @elseif($booking->refund && $booking->refund->status === 'rejected')
                                                    <span class="badge bg-danger">Đã từ chối</span>
                                                @else
                                                    <span class="badge bg-secondary">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" style="display:inline;" id="statusForm-{{ $booking->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <select name="status" class="form-select" onchange="handleStatusChange(this, '{{ $booking->id }}', {{ $booking->total_guests }})">
                                                        @php
                                                        $allowedTransitions = [
                                                        'unpaid' => ['cancelled'],
                                                        'partial' => ['paid', 'cancelled'],
                                                        'paid' => ['check_in', 'cancelled'],
                                                        'check_in' => ['check_out'],
                                                        'check_out' => [],
                                                        'cancelled' => [],
                                                        'cancelled_without_refund' => [],
                                                        'refunded' => []
                                                        ];
                                                        $currentStatus = $booking->status;
                                                        $allowedStatuses = $allowedTransitions[$currentStatus] ?? [];
                                                        @endphp

                                                        <option value="unpaid" {{ $booking->status == 'unpaid' ? 'selected' : '' }} {{ !in_array('unpaid', $allowedStatuses) && $booking->status != 'unpaid' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('unpaid') }}
                                                        </option>
                                                        <option value="partial" {{ $booking->status == 'partial' ? 'selected' : '' }} {{ !in_array('partial', $allowedStatuses) && $booking->status != 'partial' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('partial') }}
                                                        </option>
                                                        <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }} {{ !in_array('paid', $allowedStatuses) && $booking->status != 'paid' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('paid') }}
                                                        </option>
                                                        <option value="check_in" {{ $booking->status == 'check_in' ? 'selected' : '' }} {{ !in_array('check_in', $allowedStatuses) && $booking->status != 'check_in' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('check_in') }}
                                                        </option>
                                                        <option value="check_out" {{ $booking->status == 'check_out' ? 'selected' : '' }} {{ !in_array('check_out', $allowedStatuses) && $booking->status != 'check_out' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('check_out') }}
                                                        </option>
                                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }} {{ !in_array('cancelled', $allowedStatuses) && $booking->status != 'cancelled' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('cancelled') }}
                                                        </option>
                                                        <option value="cancelled_without_refund" {{ $booking->status == 'cancelled_without_refund' ? 'selected' : '' }} {{ !in_array('cancelled_without_refund', $allowedStatuses) && $booking->status != 'cancelled_without_refund' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('cancelled_without_refund') }}
                                                        </option>
                                                        <option value="refunded" {{ $booking->status == 'refunded' ? 'selected' : '' }} {{ !in_array('refunded', $allowedStatuses) && $booking->status != 'refunded' ? 'disabled' : '' }}>
                                                            {{ \App\Helpers\BookingStatusHelper::getStatusLabel('refunded') }}
                                                        </option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.bookings.show', $booking->id) }}">
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

                            <!-- Phân trang -->
{{--                            <div class="d-flex justify-content-center">--}}
{{--                                {{ $bookings->appends(request()->query())->links() }}--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Check-in Modal -->
<div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkInModalLabel">Nhập thông tin người ở</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkInForm" method="POST" action="{{ route('admin.bookings.checkin.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" id="booking_id">
                <div class="modal-body">
                    <div id="guestForms">
                        <!-- Guest forms will be dynamically added here -->
                    </div>
                    <div class="text-danger" id="guestError" style="display:none;">Bạn đã nhập đủ số lượng người ở tối đa.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Thanh toán nốt tiền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" method="POST" action="{{ route('admin.bookings.paid.store') }}">
                    @csrf
                    <input type="hidden" name="id_booking" id="id_booking">
                    <div class="mb-3">
                        <label for="remaining_amount" class="form-label">Số tiền còn lại</label>
                        <input type="text" class="form-control" name="remaining_amount" id="remaining_amount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="cash">Tiền mặt</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_note" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="payment_note" name="payment_note" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for processing refund requests -->
<div class="modal fade" id="processRefundRequestModal" tabindex="-1" aria-labelledby="processRefundRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processRefundRequestModalLabel">Phê duyệt yêu cầu hoàn tiền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Thông tin đặt phòng</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Mã đặt phòng:</th>
                                <td id="modalBookingCode"></td>
                            </tr>
                            <tr>
                                <th>Khách hàng:</th>
                                <td id="modalCustomerName"></td>
                            </tr>
                            <tr>
                                <th>Ngày nhận phòng:</th>
                                <td id="modalCheckIn"></td>
                            </tr>
                            <tr>
                                <th>Ngày trả phòng:</th>
                                <td id="modalCheckOut"></td>
                            </tr>
                            <tr>
                                <th>Tổng tiền:</th>
                                <td id="modalTotalAmount"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Thông tin hoàn tiền</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Chính sách hoàn tiền:</th>
                                <td id="modalRefundPolicy"></td>
                            </tr>
                            <tr>
                                <th>Tiền đã thanh toán:</th>
                                <td id="modalPaidAmount"></td>
                            </tr>
                            <tr>
                                <th>Số tiền hoàn:</th>
                                <td id="modalRefundAmount"></td>
                            </tr>
                            <tr>
                                <th>Phí hủy:</th>
                                <td id="modalCancellationFee"></td>
                            </tr>
                            <tr>
                                <th>Lý do hoàn tiền:</th>
                                <td id="modalRefundReason"></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <form id="processRefundForm" method="POST">
                    @csrf
                    <input type="hidden" name="refund_id" id="modalRefundId">
                    
                    <div class="mb-3">
                        <label for="refund_method" class="form-label">Phương thức hoàn tiền</label>
                        <select class="form-select" id="refund_method" name="refund_method" required>
                            <option value="">Chọn phương thức</option>
                            <option value="vnpay">VNPay</option>
                        </select>
                    </div>

                    <!-- <div class="mb-3">
                        <label for="transaction_id" class="form-label">Mã giao dịch</label>
                        <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                    </div> -->

                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger">Từ chối</button>
                        <button type="submit" name="action" value="approve" class="btn btn-success">Phê duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function clearDateInputs() {
        document.querySelector('input[name="start_date"]').value = '';
        document.querySelector('input[name="end_date"]').value = '';
    }

    function handleStatusChange(selectElement, bookingId, maxGuests) {
        const selectedStatus = selectElement.value;
        const form = document.getElementById(`statusForm-${bookingId}`);

        if (selectedStatus === 'check_in') {
            const modal = new bootstrap.Modal(document.getElementById('checkInModal'));
            document.getElementById('booking_id').value = bookingId;

            const guestForms = document.getElementById('guestForms');
            guestForms.innerHTML = '';
            addGuestForm(guestForms, maxGuests, 0);

            if (maxGuests > 1) {
                const addButton = document.createElement('button');
                addButton.type = 'button';
                addButton.className = 'btn btn-sm btn-info mb-3';
                addButton.innerText = 'Thêm người ở';
                addButton.onclick = function() {
                    const currentForms = guestForms.getElementsByClassName('guest-form').length;
                    if (currentForms < maxGuests) {
                        addGuestForm(guestForms, maxGuests, currentForms);
                    } else {
                        document.getElementById('guestError').style.display = 'block';
                    }
                };
                guestForms.insertBefore(addButton, guestForms.firstChild);
            }

            modal.show();

            document.getElementById('checkInForm').onsubmit = function(e) {
                e.preventDefault();
                // Xóa các thông báo lỗi cũ
                document.querySelectorAll('.error-message').forEach(el => el.remove());

                fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    })
                    .then(response => {
                        console.log('Response status:', response.status); // Debug response status
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.log('Response text:', text); // Debug response text
                                try {
                                    const data = JSON.parse(text);
                                    if (response.status === 422) {
                                        // Hiển thị lỗi validation
                                        displayValidationErrors(data.errors);
                                    } else if (response.status === 419) {
                                        // Lỗi CSRF token
                                        displayGeneralError('Phiên làm việc đã hết hạn. Vui lòng tải lại trang.');
                                    } else {
                                        displayGeneralError(data.message || 'Lỗi không xác định từ server.');
                                    }
                                } catch (e) {
                                    console.error('Phản hồi không phải JSON:', text);
                                    displayGeneralError('Lỗi hệ thống: Không thể xử lý phản hồi từ server.');
                                }
                                throw new Error('Validation or server error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            form.submit();
                        } else {
                            displayGeneralError(data.message);
                        }
                    })
                    .catch(error => {
                        if (error.message !== 'Validation or server error') {
                            console.error('Lỗi:', error);
                            displayGeneralError('Lỗi hệ thống: Không thể kết nối đến server.');
                        }
                    });
            };
        } else if (selectedStatus === 'paid') {
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            document.getElementById('id_booking').value = bookingId;

            // Lấy số tiền còn lại
            fetch(`/admin/bookings/${bookingId}/get-remaining-amount`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('remaining_amount').value = data.remaining_amount.toLocaleString('vi-VN') + ' VNĐ';
                    modal.show();
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                    alertDiv.innerHTML = `
                    Không thể lấy thông tin số tiền còn lại. Vui lòng thử lại sau.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                    document.querySelector('.lh-card-content').insertBefore(alertDiv, document.querySelector('.booking-table'));
                });
        } else {
            form.submit();
        }
    }

    function displayValidationErrors(errors) {
        console.log('Validation errors:', errors); // Debug errors received from server

        for (let field in errors) {
            let fieldName = field; // Ví dụ: guests.0.name
            // Chuyển đổi guests.0.name thành guests[0][name]
            fieldName = fieldName.replace(/\.(\d+|\w+)/g, '[$1]');
            // fieldName = fieldName.replace(/\.(\d+)\./g, '[$1][').replace(/\.(\w+)/g, '[$1]');
            console.log('Converted field name:', fieldName); // Debug converted field name

            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                console.log('Found input:', input); // Debug found input

                // Xóa thông báo lỗi cũ nếu có
                const existingError = input.parentElement.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }

                // Tạo và hiển thị thông báo lỗi mới
                const errorSpan = document.createElement('span');
                errorSpan.className = 'error-message text-danger small mt-1 d-block mb-2';
                errorSpan.style.display = 'block'; // Đảm bảo span hiển thị
                errorSpan.style.color = 'red'; // Đảm bảo màu đỏ
                errorSpan.style.fontSize = '0.875rem'; // Kích thước chữ nhỏ
                errorSpan.style.marginTop = '0.25rem'; // Khoảng cách phía trên
                errorSpan.innerText = errors[field].join(', ');
                input.parentElement.appendChild(errorSpan);

                // Debug DOM sau khi thêm span
                console.log('Error span added:', errorSpan);
                console.log('Parent element after adding error:', input.parentElement);
            } else {
                console.log(`Không tìm thấy input với name: ${fieldName}`); // Debug nếu không tìm thấy input
            }
        }
    }

    function displayGeneralError(message) {
        const guestForms = document.getElementById('guestForms');
        const errorSpan = document.createElement('span');
        errorSpan.className = 'error-message text-danger small mt-1 d-block mb-2';
        errorSpan.style.display = 'block'; // Đảm bảo span hiển thị
        errorSpan.style.color = 'red'; // Đảm bảo màu đỏ
        errorSpan.style.fontSize = '0.875rem'; // Kích thước chữ nhỏ
        errorSpan.style.marginTop = '0.25rem'; // Khoảng cách phía trên
        errorSpan.innerText = message;
        guestForms.insertBefore(errorSpan, guestForms.firstChild);
    }

    function addGuestForm(container, maxGuests, index) {
        const guestForm = document.createElement('div');
        guestForm.className = 'guest-form mb-3';
        guestForm.innerHTML = `
            <h6>Người ở #${index + 1}</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label>Tên <span class="text-danger">*</span></label>
                    <input type="text" name="guests[${index}][name]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Số CCCD <span class="text-danger">*</span></label>
                    <input type="text" name="guests[${index}][id_number]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Ảnh CCCD</label>
                    <input type="file" name="guests[${index}][id_photo]" class="form-control" accept="image/*" onchange="previewImage(this, 'preview-${index}')">
                    <img id="preview-${index}" style="max-width: 200px; height: auto; margin-top: 10px; display: none;" alt="Ảnh CCCD">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Ngày sinh <span class="text-danger">*</span></label>
                    <input type="date" name="guests[${index}][birth_date]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Giới tính <span class="text-danger">*</span></label>
                    <select name="guests[${index}][gender]" class="form-control">
                        <option value="">Chọn giới tính</option>
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label>Số điện thoại</label>
                    <input type="text" name="guests[${index}][phone]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Email</label>
                    <input type="email" name="guests[${index}][email]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Quốc gia</label>
                    <input type="text" name="guests[${index}][country]" class="form-control">
                </div>
                <div class="col-md-6 mb-2">
                    <label>Mối quan hệ</label>
                    <input type="text" name="guests[${index}][relationship]" class="form-control">
                </div>
            </div>
        `;
        container.appendChild(guestForm);
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('processRefundRequestModal');
        if (modal) {
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const refundId = button.getAttribute('data-refund-id');
                console.log(refundId);
                
                // Fetch refund details
                fetch(`/admin/refunds/${refundId}/details`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        
                        // Fill modal with data
                        document.getElementById('modalRefundId').value = data.id;
                        document.getElementById('modalBookingCode').textContent = data.booking.booking_code;
                        document.getElementById('modalCustomerName').textContent = data.booking.user.name;
                        document.getElementById('modalCheckIn').textContent = data.booking.check_in;
                        document.getElementById('modalCheckOut').textContent = data.booking.check_out;
                        document.getElementById('modalTotalAmount').textContent = formatCurrency(data.booking.total_price);
                        document.getElementById('modalRefundPolicy').textContent = data.refund_policy.name;
                        document.getElementById('modalPaidAmount').textContent = formatCurrency(data.booking.paid_amount);
                        document.getElementById('modalRefundAmount').textContent = formatCurrency(data.amount);
                        document.getElementById('modalCancellationFee').textContent = formatCurrency(data.cancellation_fee);
                        document.getElementById('modalRefundReason').textContent = data.reason;
                        
                        // Set form action
                        document.getElementById('processRefundForm').action = `/admin/refunds/${refundId}/approve`;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi tải thông tin hoàn tiền: ' + error.message);
                    });
            });
        }
    });

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
</script>
@endsection
