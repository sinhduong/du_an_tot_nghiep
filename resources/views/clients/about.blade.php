@extends('layouts.client')

@section('content')
    <section class="section-about padding-tb-100" data-aos="fade-up" data-aos-duration="2000" id="about">
        <div class="container">
            <div class="banner text-center mb-5">
                <h2>Giới thiệu <span>về chúng tôi</span></h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0" style="background-color: #f8f9fa;">
                        <div class="card-body">
                            {!! $about->about !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
