@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Chính sách</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Chính sách</a>
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
                    <div class="card shadow-lg border-0 mb-4" style="background-color: #f8f9fa;">
                    @if($policies->count() > 0)
                        @foreach($policies as $policy)
                        <div class="card-body">
                        <i class="fas fa-exclamation-circle text-warning me-2"></i>
                            {!! $policy->policy !!}
                        </div>
                        @endforeach
                        @else
                        <div class="card shadow-lg border-0" style="background-color: #f8f9fa;">
                            <div class="card-body">
                                <p>Chưa có chính sách nào được thiết lập.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
