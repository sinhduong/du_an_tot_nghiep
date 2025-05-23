<section class="section-hero">
    <div class="container-fulid">
        {{-- <div class="row hero-image" style="background-image: url('storage/{{ $banner->image }}')"> --}}
            <div class="row hero-image" style="background-image: url('storage/{{ $banner->image ?? asset('assets/client/assets/img/hero/hero-section.jpg') }}')">
        <div class="hero-section">
                <div class="particles-bg" id="particles-js"></div>
                <div class="lh-hero-contain container">
                    <h4 data-aos="fade-up" data-aos-duration="1000">Khách sạn sang trọng & khu nghỉ dưỡng tốt nhất</h4>
                    <h1 data-aos="fade-up" data-aos-duration="1500">Bản giao hưởng của sự thoải mái và tiện lợi.</h1>
                    <a class="lh-buttons result-placeholder" href="#rooms">
                        Room & suites
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
