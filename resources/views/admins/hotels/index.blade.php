@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Hotel</h5>
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
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
                            <li><a class="dropdown-item" href="#">Expence</a></li>
                        </ul>
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
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                class="ri-fullscreen-line" title="Full Screen"></i></a>
                                <div class="lh-date-range dots">
                                    <span></span>
                                </div>
                                <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.hotels.hotel.create') }}'">
                                    Tạo mới
                                </button>

                        </div>
                    </div>

                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table">
                                    <thead>

                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Description</th>
                                            <th>Price form</th>
                                            <th>Price to</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listHotel as $index=>$item)
                                        <tr>
                                            <td class="token">{{ $index+1 }}</td>
                                            <td><span class="name">{{ $item->name }}</span>
                                            </td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->city }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td class="active">$ {{ $item->price_form }}</td>
                                            <td>${{ $item->price_to }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

