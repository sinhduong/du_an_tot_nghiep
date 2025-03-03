<!-- resources/views/admin/dashboard/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Dashboard</h5>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
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
                    <div class="filter">
                        <div class="dropdown" title="Filter">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-sound-module-line"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Booking</a></li>
                                <li><a class="dropdown-item" href="#">Revenue</a></li>
                                <li><a class="dropdown-item" href="#">Expense</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="lh-card lh-card-1">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Visitor</h4>
                                    <h5>{{ number_format($visitorCount / 1000, 1) }}k</h5>
                                </div>
                                <span class="icon"><i class="ri-shield-user-line"></i></span>
                            </div>
                            <p class="card-groth {{ $visitorGrowth >= 0 ? 'up' : 'down' }}">
                                <i class="ri-arrow-{{ $visitorGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ abs(round($visitorGrowth, 1)) }}%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="userNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="lh-card lh-card-2">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Bookings</h4>
                                    <h5>{{ number_format($bookingCount / 1000, 2) }}k</h5>
                                </div>
                                <span class="icon"><i class="ri-shopping-bag-3-line"></i></span>
                            </div>
                            <p class="card-groth {{ $bookingGrowth >= 0 ? 'up' : 'down' }}">
                                <i class="ri-arrow-{{ $bookingGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ abs(round($bookingGrowth, 1)) }}%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="bookingNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="lh-card lh-card-3">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Revenue</h4>
                                    <h5>${{ number_format($revenueTotal) }}</h5>
                                </div>
                                <span class="icon"><i class="ri-money-dollar-circle-line"></i></span>
                            </div>
                            <p class="card-groth {{ $revenueGrowth >= 0 ? 'up' : 'down' }}">
                                <i class="ri-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}-line"></i>
                                {{ abs(round($revenueGrowth, 1)) }}%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="revenueNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="lh-card lh-card-4">
                        <div class="lh-card-content label-card">
                            <div class="title">
                                <div class="growth-numbers">
                                    <h4>Rooms</h4>
                                    <h5><span data-bs-toggle="tooltip" aria-label="Available"
                                              data-bs-original-title="Available">{{ $roomsAvailable }}</span>/{{ $roomsTotal }}</h5>
                                </div>
                                <span class="icon"><i class="ri-exchange-dollar-line"></i></span>
                            </div>
                            <p class="card-groth up">
                                <i class="ri-arrow-up-line"></i>
                                9%
                                <span>Last Month</span>
                            </p>
                            <div class="mini-chart">
                                <div id="expensesNumbers"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12"> <!-- Thay đổi từ col-xl-8 thành col-xl-12 để chiếm toàn bộ chiều rộng -->
                    <div class="lh-card revenue-overview">
                        <div class="lh-card-header header-575">
                            <h4 class="lh-card-title">Revenue Overview</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="m-r-10 lh-full-card">
                                    <i class="ri-fullscreen-line" title="Full Screen"></i></a>
                                <div class="lh-date-range date" title="Date">
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="lh-card-content">
                            <div class="lh-chart-header">
                                <div class="block">
                                    <h6>Bookings</h6>
                                    <h5>{{ array_sum($overviewData['bookings']) }}
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Revenue</h6>
                                    <h5>${{ number_format(array_sum($overviewData['revenue'])) }}
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Expense</h6>
                                    <h5>${{ number_format(array_sum($overviewData['expense'])) }}
                                        <span class="down"><i class="ri-arrow-down-line"></i>24%</span>
                                    </h5>
                                </div>
                                <div class="block">
                                    <h6>Profit</h6>
                                    <h5>${{ number_format(array_sum($overviewData['profit'])) }}
                                        <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
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

        new Chart(document.getElementById('userNumbers'), {
            ...miniChartOptions,
            data: { ...miniChartOptions.data, datasets: [{ ...miniChartOptions.data.datasets[0], data: @json($miniChartData['visitor']) }] }
        });
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
                    { label: 'Bookings', data: @json($overviewData['bookings']), borderColor: '#4e73df', fill: false },
                    { label: 'Revenue', data: @json($overviewData['revenue']), borderColor: '#1cc88a', fill: false },
                    { label: 'Expense', data: @json($overviewData['expense']), borderColor: '#e74a3b', fill: false },
                    { label: 'Profit', data: @json($overviewData['profit']), borderColor: '#36b9cc', fill: false }
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
