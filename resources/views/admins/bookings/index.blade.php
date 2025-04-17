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
                                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" style="display:inline;" id="statusForm-{{ $booking->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select" onchange="handleStatusChange(this, '{{ $booking->id }}', {{ $booking->total_guests }})">
                                                        <option value="unpaid" {{ $booking->status == 'unpaid' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('unpaid') }}</option>
                                                        <option value="partial" {{ $booking->status == 'partial' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('partial') }}</option>
                                                        <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('paid') }}</option>
                                                        <option value="check_in" {{ $booking->status == 'check_in' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('check_in') }}</option>
                                                        <option value="check_out" {{ $booking->status == 'check_out' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('check_out') }}</option>
                                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('cancelled') }}</option>
                                                        <option value="refunded" {{ $booking->status == 'refunded' ? 'selected' : '' }}>{{ \App\Helpers\BookingStatusHelper::getStatusLabel('refunded') }}</option>
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
                            <div class="d-flex justify-content-center">
                                {{ $bookings->appends(request()->query())->links() }}
                            </div>
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
        } else {
            form.submit();
        }
    }

    function displayValidationErrors(errors) {
        console.log('Validation errors:', errors); // Debug errors received from server

        for (let field in errors) {
            let fieldName = field; // Ví dụ: guests.0.name
            // Chuyển đổi guests.0.name thành guests[0][name]
            fieldName = fieldName.replace(/\.(\d+)\./g, '[$1][').replace(/\.(\w+)/g, '[$1]');
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
                errorSpan.className = 'error-message text-danger small mt-1 d-block';
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
        errorSpan.className = 'error-message text-danger small mt-1 d-block';
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
</script>
@endsection