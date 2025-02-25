@extends('layouts.admin')
@section('content')
    <div class="lh-main-content">
        <div class="container-fluid">
            <!-- Page title & breadcrumb -->
            <div class="lh-page-title">
                <div class="lh-breadcrumb">
                    <h5>Thêm Quy Định Vào Phòng</h5>
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
                        <div class="lh-card-content card-booking">
                            <form action="{{ route('admin.rule-regulations.room_store') }}" method="POST" enctype="multipart/form-data">                                @csrf
                                <div class="row mtb-m-12">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li><strong>Tên Phòng: </strong>
                                                    <div class="form-group">
                                                        {{-- <input type="text" name="name" placeholder="Tên Quy Tắc"
                                                            class="form-control" value="{{ old('name') }}"> --}}
                                                        <select name="room_ids[]" id="room_ids" class="form-control"
                                                            multiple>
                                                            @foreach ($room as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }} </option>
                                                            @endforeach
                                                        </select>
                                                        @error('name')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </li>
                                                <li><strong>Tên Các Quy Định : </strong>
                                                    <div class="form-group">
                                                        {{-- <input type="text" name="name" placeholder="Tên Quy Tắc"
                                                            class="form-control" value="{{ old('name') }}"> --}}
                                                        <select name="rule_ids[]" id="rule_ids" class="form-control"
                                                            multiple>
                                                            @foreach ($rule as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('name')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>

                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="lh-user-detail">
                                            <ul>
                                                <li>
                                                    <button type="submit" class="lh-btn-primary">Submit</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
