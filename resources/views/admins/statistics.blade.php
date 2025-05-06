@extends('layouts.admin')

@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Tiêu đề trang & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Thống Kê Chi Tiết</h5>
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Trang Chủ</a></li>
                    <li>Thống Kê Chi Tiết</li>
                </ul>
            </div>
            <div class="lh-tools">
                <form action="" method="get">
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" name="date_range" class="form-control form-control-sm date-range-picker"
                            value="{{ $dateRange }}" placeholder="Chọn khoảng thời gian">
                        <button type="submit" class="btn btn-primary btn-sm me-2">Lọc</button>
                    </div>
                </form>
                <a href="javascript:void(0)" title="Làm mới" class="refresh"><i class="ri-refresh-line"></i></a>
            </div>
        </div>

        <div class="row">
            <!-- Thống kê theo loại phòng -->
            <div class="col-xl-6 col-md-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Thống Kê Theo Loại Phòng</h4>
                    </div>
                    <div class="lh-card-content">
                        <canvas id="roomTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top dịch vụ được sử dụng -->
            <div class="col-xl-6 col-md-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Top Dịch Vụ Được Sử Dụng</h4>
                    </div>
                    <div class="lh-card-content">
                        <canvas id="topServicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thống kê đặt phòng theo ngày trong tuần -->
            <div class="col-xl-6 col-md-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Đặt Phòng Theo Ngày Trong Tuần</h4>
                    </div>
                    <div class="lh-card-content">
                        <canvas id="bookingByDayChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Thống kê doanh thu theo giờ -->
            <div class="col-xl-6 col-md-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Doanh Thu Theo Giờ Trong Ngày</h4>
                    </div>
                    <div class="lh-card-content">
                        <canvas id="revenueByHourChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tỷ lệ hủy đặt phòng -->
        <div class="row">
            <div class="col-xl-12">
                <div class="lh-card">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Tỷ Lệ Hủy Đặt Phòng</h4>
                    </div>
                    <div class="lh-card-content">
                        <div class="text-center">
                            <h2 class="mb-0">{{ $cancellationRate }}%</h2>
                            <p class="text-muted">Tỷ lệ đặt phòng bị hủy</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thêm thư viện date range picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Khởi tạo date range picker
    $('.date-range-picker').daterangepicker({
        autoUpdateInput: true,
        maxDate: moment(),
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Áp dụng',
            cancelLabel: 'Hủy',
            customRangeLabel: 'Tùy chọn',
            daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            firstDay: 1
        },
        ranges: {
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 ngày qua': [moment().subtract(6, 'days'), moment()],
            '30 ngày qua': [moment().subtract(29, 'days'), moment()],
            'Tháng này': [moment().startOf('month'), moment()],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Năm này': [moment().startOf('year'), moment()]
        }
    });

    // Biểu đồ thống kê theo loại phòng
    new Chart(document.getElementById('roomTypeChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($roomTypeStats->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($roomTypeStats->pluck('total')) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Biểu đồ top dịch vụ
    new Chart(document.getElementById('topServicesChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topServices->pluck('name')) !!},
            datasets: [{
                label: 'Số lần sử dụng',
                data: {!! json_encode($topServices->pluck('bookings_count')) !!},
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ đặt phòng theo ngày
    const daysOfWeek = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
    new Chart(document.getElementById('bookingByDayChart'), {
        type: 'line',
        data: {
            labels: daysOfWeek,
            datasets: [{
                label: 'Số đặt phòng',
                data: {!! json_encode($bookingByDay->pluck('total')) !!},
                borderColor: '#4e73df',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ doanh thu theo giờ
    new Chart(document.getElementById('revenueByHourChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueByHour->pluck('hour')->map(function($hour) { return $hour . 'h'; })) !!},
            datasets: [{
                label: 'Doanh thu (VND)',
                data: {!! json_encode($revenueByHour->pluck('total')) !!},
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VND';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection 