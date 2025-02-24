@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>rooms</h5>
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
                <div class="col-xxl-12 col-xl-8 col-md-12">
                    <div class="lh-card" id="bookingtbl">
                        <div class="lh-card-header">
                            <h4 class="lh-card-title">{{ $title }}</h4>
                            <div class="header-tools">
                                <a href="javascript:void(0)" class="lh-full-card"><i class="ri-fullscreen-line"
                                        data-bs-toggle="tooltip" aria-label="Full Screen"
                                        data-bs-original-title="Full Screen"></i></a>
                            </div>
                        </div>
                        <div class="container mt-4">
                            <h2 class="text-center">Chi Tiết Phòng: {{ $room->name }}</h2>
                            <table id="booking_table" class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Tên Quy Tắc && Quy Định </th>
                                        {{-- <th>Trạng thái</th> --}}
                                        <th class="text-center">Hành động</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($room_rars as $rule)
                                   
                                        <tr>

                                            <td class="text-center"> {{$rule->id}} </td>
                                            <td class="text-center">
                                                {{$rule->amenity->name}}
                                                {{-- <td> {{ optional($rule->amenity)->name }} </td> --}}

                                            </td>
                                            <td> {{$rule->id}}</td>
                                            <td>
                                                <div class="btn-group">

                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.amenities.destroy_room', $rule->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bạn có muốn xóa mềm không?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="ri-delete-bin-line"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
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
@endsection