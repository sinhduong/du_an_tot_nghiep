@extends('layouts.client')

@section('content')
    <section class="section-room padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="rooms">
        <div class="container">
            <div class="banner text-center mb-5">
                <h2>Dịch vụ <span>của khách sạn</span></h2>
            </div>

            <div class="row g-4">
                @foreach($services as $service)
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow-lg" style="background-color: #f8f9fa; border: 1px solid #e0e0e0;">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary fw-bold">{{ $service->name }}</h5>
                                <p class="card-text fw-bold text-success fs-5">{{ number_format($service->price, 0, ',', '.') }} VNĐ</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <small class="text-muted">Dịch vụ đang hoạt động</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
