@extends('layouts.client')

@section('content')
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>về chúng tôi</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">về chúng tôi</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="section-contact padding-tb-100">
        <div class="container">
            <div class="row mb-24">
                <div class="col-lg-4 rs-pb-24">
                    <div class="lh-contact" data-aos="fade-up" data-aos-duration="1000">
                        <div class="lh-contact-image">
                            <img src="{{ asset('assets/client/assets/img/contact/call-1.svg ')}}" class="svg-img" alt="call">
                        </div>
                        <div class="lh-contact-detalis">
                            <h4 class="lh-contact-detalis-heading">Gọi cho chúng tôi </h4>
                            <p>Bạn cần hỗ trợ ngay? Hãy liên hệ với chúng tôi qua số điện thoại dưới đây. Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7!</p>
                            <div class="lh-contact-detalis-buttons">
                                <a href="#">{{$systems->phone}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 rs-pb-24">
                    <div class="lh-contact" data-aos="fade-up" data-aos-duration="1500">
                        <div class="lh-contact-image">
                            <img src="{{ asset('assets/client/assets/img/contact/email-1.svg')}}" class="svg-img" alt="contact">
                        </div>
                        <div class="lh-contact-detalis">
                            <h4 class="lh-contact-detalis-heading">Gửi Mail Cho Chúng Tôi</h4>
                            <p>Có thắc mắc hoặc cần thêm thông tin? Đừng ngần ngại gửi email cho chúng tôi. Chúng tôi sẽ phản hồi bạn trong vòng 24 giờ!</p>
                            <div class="lh-contact-detalis-buttons">
                                <a href="#">{{$systems->email}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 rs-pb-24">
                    <div class="lh-contact" data-aos="fade-up" data-aos-duration="2000">
                        <div class="lh-contact-image">
                            <img src="{{ asset('assets/client/assets/img/contact/support-1.svg')}}" class="svg-img" alt="contact">
                        </div>
                        <div class="lh-contact-detalis">
                            <h4 class="lh-contact-detalis-heading">Địa chỉ</h4>
                            <p> <a href="#">{{$systems->address}}</a></p>
                            <div class="lh-contact-detalis-buttons">
                                <p class="main">Việt Nam</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lh-contact-touch" data-aos="fade-up" data-aos-duration="2000">
                <div class="row">
                <div class="col-lg-6 rs-pb-24">
                    <div class="lh-contact-touch-inner">
                        <div class="lh-contact-touch-contain">
                            <h4 class="lh-contact-touch-contain-heading">Liên hệ với chúng tôi. Hãy thoải mái viết thư cho chúng tôi</h4>
                            {{-- <p>This is the dolor consectetur adpisicing elit. Deleniti quam exercitationem a
                                expedita natus quisquam. Deleniti Facere exercitationem ratione nihil Deleniti delectus
                                possimus!</p> --}}
                        </div>
                        <div class="lh-contact-touch-inner-form">
                            <form action="#">
                                <div class="lh-contact-touch-inner-form-warp">
                                    <input type="text" name="firstname" placeholder="Your Name*"
                                        class="lh-form-control mr-30" required="">
                                    <input type="email" name="email" placeholder="Your Email*" class="lh-form-control"
                                        required="">
                                </div>
                                <div class="lh-contact-touch-inner-form-warp">
                                    <input type="text" name="firstname" placeholder="Your Subject*"
                                        class="lh-form-control" required="">
                                </div>
                                <div class="lh-contact-touch-inner-form-warp">
                                    <textarea class="lh-form-control" placeholder="Message*"></textarea>
                                </div>
                                <div class="lh-contact-touch-inner-form-button">
                                    <button class="lh-buttons result-placeholder" type="submit">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 rs-pb-24">
                    <div class="lh-contact-touch-ifrem">
                        <iframe
                            src="{{$systems->map}} "
                            style="border:0;"></iframe>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
