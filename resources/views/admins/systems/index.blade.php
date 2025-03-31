@extends('layouts.admin')
@section('content')
<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                {{-- <h5>Loại </h5> --}}
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            {{-- <div class="lh-tools">
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
            </div> --}}
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
                            <button class="btn btn-primary ms-2" onclick="window.location.href='{{ route('admin.systems.create') }}'">
                                Tạo mới
                            </button>
                        </div>
                    </div>
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên Tiện Ích</th>
                                            <th>Logo</th>
                                            <th>Địa Chỉ  </th>                
                                            <th>Email </th>                
                                            <th>Phone</th>
                                            <th>Map</th>
                                            <th>Trạng Thái </th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($systems as $index => $item)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @php
                                                    // Đảm bảo rằng đường dẫn ảnh có đầy đủ thư mục (nếu cần)
                                                    $imagePath = $item->logo;
                                                    // echo $imagePath ;
                                                @endphp
                                                {{-- @if ($item->image && Storage::disk('public')->exists($item->image))
                                                    <img src="{{ Storage::url($item->image) }}" width="100"
                                                        height="100" alt="{{ $item->name }}"
                                                        class="img-thumbnail">
                                                @else --}}
                                                @if (!empty($item->logo))
                                                <img src="{{ asset('storage/' . $item->logo) }}" width="120px" alt="Product Image">

                                                {{-- <img src="{{ Storage::url($item->image) }}"
                                                     width="100" height="100" alt="{{ $item->name }}"
                                                     class="img-thumbnail"> --}}
                                                @else
                                                    <small>Chưa có</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->address }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td><a href="{{ $item->map }}" target="_blank">
                                                {{ \Illuminate\Support\Str::limit($item->map, 30, '...') }}
                                            </a></td>
                                            <td>
                                                <span class="badge {{ $item->is_use ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->is_use ? 'Hoạt động' : 'Không hoạt động' }}
                                                </span>
                                            </td>  
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="ri-settings-3-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.systems.edit', $item->id) }}">
                                                                <i class="ri-edit-line"></i> Sửa
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.systems.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa mềm không?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="ri-delete-bin-line"></i> Xóa
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
    </div>
</div>
@endsection
