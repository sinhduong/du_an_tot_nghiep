<!-- resources/views/admin/dashboard/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Tiêu đề trang & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Trang Tổng Quan</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Trang Chủ</a></li>
                        <li>Trang Tổng Quan</li>
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
                    <!-- <div id="pagedate">
                        <div class="lh-date-range" title="Ngày">
                            <span></span>
                        </div>
                    </div> -->
                    <!-- <div class="filter">
                        <div class="dropdown" title="Lọc">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-sound-module-line"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Đặt Phòng</a></li>
                                <li><a class="dropdown-item" href="#">Doanh Thu</a></li>
                                <li><a class="dropdown-item" href="#">Chi Phí</a></li>
                            </ul>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="lh-card lh-card-2">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Đặt Phòng</h4>
                                    <h5>{{ number_format($bookingCount) }}</h5>
                                </div>
                            </div>
                            <p class="card-groth up">
                                <span></span>
                            </p>
                            <div class="mini-chart">
                                <div id="bookingNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="lh-card lh-card-3">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Doanh Thu</h4>
                                    <h5>{{ number_format($revenueTotal) }} VND</h5>
                                </div>
                            </div>
                            <p class="card-groth up">
                                <span></span>
                            </p>
                            <div class="mini-chart">
                                <div id="revenueNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="lh-card lh-card-4">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Phòng</h4>
                                    <h5><span data-bs-toggle="tooltip" aria-label="Sẵn Có"
                                              data-bs-original-title="Sẵn Có">{{ $roomsAvailable }}</span>/{{ $roomsTotal }}</h5>
                                </div>
                                {{-- <span class="icon"><i class="ri-exchange-vnd-line"></i></span> --}}
                            </div>
                            <p class="card-groth up">
                                <span></span>
                            </p>
                            <div class="mini-chart">
                                <div id="expensesNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="lh-card revenue-overview">
                        <div class="lh-card-header header-575">
                            <h4 class="lh-card-title">Tổng Quan Doanh Thu</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card">
                                    <i class="ri-fullscreen-line" title="Toàn Màn Hình"></i></a>
                            </div>
                        </div>
                        <div class="lh-card-content">
                            <div class="lh-chart-header">
                                <div class="block">
                                    <h6>Đặt Phòng</h6>
                                    <h5>{{ array_sum($overviewData['bookings']) }}
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Doanh Thu</h6>
                                    <h5>{{ number_format(array_sum($overviewData['revenue'])) }} VND
                                    </h5>
                                </div>
                            </div>
                            <div class="lh-chart-content">
                                <div id="overviewChart"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo date range picker
            $('.date-range-picker').daterangepicker({
                autoUpdateInput: true,
                maxDate: moment(), // Không cho phép chọn ngày trong tương lai
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
                },
                alwaysShowCalendars: true,
                showCustomRangeLabel: true,
                opens: 'right',
                drops: 'auto'
            }, function(start, end, label) {
                $('.date-range-picker').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            });

            // Khởi tạo giá trị mặc định cho date range
            var dateRange = '{{ $dateRange }}';
            if (dateRange) {
                $('.date-range-picker').val(dateRange);
            }

            // Xử lý counter dropdown
            $('#counter_summary').on('click', function() {
                $('.counter-dropdown-content').toggleClass('show');
            });

            $('.counter-btn').on('click', function() {
                var target = $(this).data('target');
                var input = $('input[name="' + target + '"]');
                var value = parseInt(input.val());
                if ($(this).hasClass('plus')) {
                    input.val(value + 1);
                } else if (value > 0) {
                    input.val(value - 1);
                }
                updateCounterSummary();
            });
        });
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const miniChartOptions = {
            type: 'line',
            data: {
                labels: @json($miniChartData['labels']),
                datasets: [{
                    borderColor: '#ffffff',
                    data: [],
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { x: { display: false }, y: { display: false } }
            }
        };

        new Chart(document.getElementById('bookingNumbers'), {
            ...miniChartOptions,
            data: { ...miniChartOptions.data, datasets: [{ ...miniChartOptions.data.datasets[0], data: @json($miniChartData['booking']) }] }
        });
        new Chart(document.getElementById('revenueNumbers'), {
            ...miniChartOptions,
            data: { ...miniChartOptions.data, datasets: [{ ...miniChartOptions.data.datasets[0], data: @json($miniChartData['revenue']) }] }
        });
        new Chart(document.getElementById('expensesNumbers'), {
            ...miniChartOptions,
            data: { ...miniChartOptions.data, datasets: [{ ...miniChartOptions.data.datasets[0], data: @json($miniChartData['rooms']) }] }
        });

        new Chart(document.getElementById('overviewChart'), {
            type: 'line',
            data: {
                labels: @json($overviewData['labels']),
                datasets: [
                    { label: 'Đặt Phòng', data: @json($overviewData['bookings']), borderColor: '#4e73df', fill: false },
                    { label: 'Doanh Thu', data: @json($overviewData['revenue']), borderColor: '#1cc88a', fill: false },
                    { label: 'Chi Phí', data: @json($overviewData['expense']), borderColor: '#e74a3b', fill: false },
                    { label: 'Lợi Nhuận', data: @json($overviewData['profit']), borderColor: '#36b9cc', fill: false }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: { x: { display: true }, y: { display: true } }
            }
        });
    </script>
@endpush
