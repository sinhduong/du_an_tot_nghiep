@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Danh sách khách hàng</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang chủ</a></li>
                    <li>Khách hàng</li>
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

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Danh sách khách hàng</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card">
                                <i class="ri-fullscreen-line" title="Full Screen"></i>
                            </a>
                        </div>
                    </div>
                    <div class="lh-card-content card-default">
                        <div class="table-responsive" style="min-height: 200px">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th>Tên</th>
                                        <th>Số điện thoại</th>
                                        <th>Email</th>
                                        <th>Địa chỉ</th>
                                        <th>Giới tính</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $index => $customer)
                                    <tr>
                                        <td class="text-center">{{ $customers->firstItem() + $index }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->phone ?? 'Chưa cập nhật' }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->address ?? 'Chưa cập nhật' }}</td>
                                        <td>{{ $customer->gender ?? 'Chưa xác định' }}</td>
                                        <td>
                                            <!-- <span class="text-{{ $customer->is_active ? 'success' : 'danger' }}">
                                                {{ $customer->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span> -->

                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" name="is_active" value="{{ $customer->is_active ? 1 : 0 }}"
                                                    id="isActive-{{ $customer->id }}" {{ $customer->is_active ? 'checked' : '' }} onchange="updateCustomerStatus({{ $customer->id }}, this.checked)">
                                            </div>
                                        </td>
                                        <td>{{ $customer->created_at ? $customer->created_at->diffForHumans() : 'Không có' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ri-settings-3-line"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.customers.show', $customer->id) }}">
                                                            <i class="ri-eye-line"></i> Xem chi tiết
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Không có dữ liệu khách hàng</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .form-label {
        margin-bottom: 0.25rem;
    }

    .form-control-sm {
        padding: 0.25rem 0.5rem;
    }

    .image-input-group {
        max-width: 400px;
    }

    .error-text {
        font-size: 0.8rem;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .form-check-input:not(:checked) {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    #statusLabel.active {
        color: #28a745;
    }

    #statusLabel.inactive {
        color: #dc3545;
    }

    .status-container {
        margin-top: 0.5rem;
        /* Khoảng cách phía trên */
        margin-bottom: 0.5rem;
        /* Khoảng cách phía dưới */
        padding-left: 1rem;
        /* Khoảng cách bên trái để tạo độ lùi */
    }
</style>

<script>
    function updateCustomerStatus(customerId, isActive) {
            fetch(`/admin/customers/${customerId}/update-status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        is_active: isActive ? 1 : 0
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Cập nhật trạng thái thành công!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Cập nhật trạng thái thất bại!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Đã xảy ra lỗi khi cập nhật trạng thái!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
        }
    $(document).ready(function() {
        function updateStatusLabel() {
            const $checkbox = $('#isActive');
            const $label = $('#statusLabel');
            if ($checkbox.is(':checked')) {
                $label.text('Hoạt động').removeClass('inactive').addClass('active');
            } else {
                $label.text('Không hoạt động').removeClass('active').addClass('inactive');
            }
        }
        updateStatusLabel(); // Gọi lần đầu khi tải trang
        $('#isActive').on('change', updateStatusLabel);


        
    });
</script>
@endsection