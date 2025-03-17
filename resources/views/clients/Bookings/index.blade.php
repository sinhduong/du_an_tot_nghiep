@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Danh sách đặt phòng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Danh sách đặt phòng</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="booking-section py-5" style="background-color: #fff;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="booking-header mb-4">
                    <p class="text-muted">Đã đặt</p>
                    <p>18 tháng 2 – 19 tháng 2</p>
                </div>

                <!-- Danh sách đặt phòng -->
                <div class="room-list">
                    <!-- Đặt phòng 1 -->
                    <a href="#" class="room-item card shadow-sm mb-3" style="display: block; text-decoration: none; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                        <div class="card-body d-flex align-items-center">
                            <div class="room-image" style="flex: 0 0 120px;">
                                <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/249146036.jpg?k=88cdb9fcaffdce6a2769a17da8e3941e1823a15726b16da4a34fc600bdcc97d2&o=" alt="Room Image" style="width: 100%; height: 90px; object-fit: cover; border-radius: 5px 0 0 5px;">
                            </div>
                            <div class="room-details flex-grow-1 px-3" style="color: #333;">
                                <h5>Newsyte Apartment Tran Duy Hung</h5>
                                <p style="color: #555;">18 tháng 2 – 19 tháng 2 · Hà Nội</p>
                                <p style="color: #555;">Đã hoàn thanh</p>
                            </div>
                            <div class="room-price d-flex align-items-center pe-3" style="min-width: 120px; color: #555;">
                                <div style="display: flex; align-items: center;" class="mb-5">
                                    <h4  style="color: #3b3733; margin: 0 5px 0 0; font-size: 20px;">VND 2,827,550</h4>
                                    <span class="vertical-dots mx-4" style="color: #555; font-size: 30px; font-weight: 800;">⋮</span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Đặt phòng 2 -->
                    <a href="#" class="room-item card shadow-sm mb-3" style="display: block; text-decoration: none; border: 1px solid #ddd; border-radius: 10px; overflow: hidden;">
                        <div class="card-body d-flex align-items-center">
                            <div class="room-image" style="flex: 0 0 120px;">
                                <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/249146036.jpg?k=88cdb9fcaffdce6a2769a17da8e3941e1823a15726b16da4a34fc600bdcc97d2&o=" alt="Room Image" style="width: 100%; height: 90px; object-fit: cover; border-radius: 5px 0 0 5px;">
                            </div>
                            <div class="room-details flex-grow-1 px-3" style="color: #333;">
                                <h5>Newsyte Apartment Tran Duy Hung</h5>
                                <p style="color: #555;">18 tháng 2 – 19 tháng 2 · Hà Nội</p>
                                <p style="color: #555;">Đã hủy</p>
                            </div>
                            <div class="room-price d-flex align-items-center pe-3" style="min-width: 120px; color: #555;">
                                <div style="display: flex; align-items: center;" class="mb-5">
                                    <h4  style="color: #3b3733; margin: 0 5px 0 0; font-size: 20px;">VND 2,827,550</h4>
                                    <span class="vertical-dots mx-4" style="color: #555; font-size: 30px; font-weight: 800;">⋮</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
