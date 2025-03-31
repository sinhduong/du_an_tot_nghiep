@extends('layouts.client')

@section('content')
    <section class="section-banner">
        <div class="row banner-image">
            <div class="banner-overlay"></div>
            <div class="banner-section">
                <div class="lh-banner-contain">
                    <h2>Dịch vụ khách sạn</h2>
                    <div class="lh-breadcrumb">
                        <h5>
                            <span class="lh-inner-breadcrumb">
                                <a href="{{ route('home') }}">Trang chủ</a>
                            </span>
                            <span> / </span>
                            <span>
                                <a href="javascript:void(0)">Dịch vụ khách sạn</a>
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-room padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="rooms">
        <div class="container">
            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow-lg" style="background-color: #f8f9fa; border: 1px solid #e0e0e0;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary fw-bold">{{ $service->name }}</h5>
                                <p class="card-text fw-bold text-success fs-5">{{ number_format($service->price, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Room Types Section -->
    <section class="section-room padding-tb-100" data-aos="fade-up" data-aos-duration="2000">
        <div class="container">
            <div class="row g-4">
                @forelse ($roomTypes as $roomType)
                    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                        <div class="rooms-card">
                            <img src="{{ $roomType->roomTypeImages->isNotEmpty() ? asset('storage/' . $roomType->roomTypeImages->first()->image) : asset('assets/img/room/default.jpg') }}" alt="{{ $roomType->name }}">
                            <div class="details">
                                <h3>{{ $roomType->name }}</h3>
                                <span>{{ number_format($roomType->price, 0, ',', '.') }} / Night</span>
                                <ul>
                                    <li><i class="ri-group-line"></i>{{ $roomType->max_capacity }} Persons</li>
                                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                                    @if($roomType->amenities->contains('name', 'Swimming Pool'))
                                        <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                                    @endif
                                    @if($roomType->amenities->contains('name', 'Free Wifi'))
                                        <li><i class="ri-wifi-line"></i>Free Wifi</li>
                                    @endif
                                </ul>
                                <a href="{{ route('room.details', $roomType->id) }}" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>Không có loại phòng nào để hiển thị.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
