@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Dịch vu khách sạn</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Dịch vu khách sạn</a>
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
@endsection
