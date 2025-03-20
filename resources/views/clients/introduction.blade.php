@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Giới thiệu</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Giới thiệu</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="section-about padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="about">
        <div class="container">
            

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            {!! $introduction->introduction !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
