    <footer>
    <div class="container">
        <div class="footer-top-section">
            <div class="row">
                <div class="col-lg-3 col-md-6 rs-pb-24 p-991 order-lg-1 order-md-2 order-2">
                    <div class="lh-footer-cols-contain">
                        <div class="lh-footer-heading">
                            <h4>Khám phá</h4>
                        </div>
                        <ul>
                            <li>
                                <code>*</code>
                                <a href="{{route('home')}}">Trang chủ</a>
                            </li>
                            <li>
                                <code>*</code>
                                <a href="{{route('contacts')}}">Liên hệ</a>
                            </li>
                            <li>
                                <code>*</code>
                                <a href="{{route('introductions')}}">Giới thiệu</a>
                            </li>
                            <li>
                                <code>*</code>
                                <a href="{{route('faqs')}}">Câu hỏi thường gặp</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 rs-pb-24 order-lg-2 order-md-1 order-1">
                    <div class="lh-social-media">
                        <div class="footer-logos">
                            <img src="{{ $systems->logo ? asset('storage/' . $systems->logo) : asset('assets/client/assets/img/logo/logo-2.png') }}" alt="Logo" class="lh-logo">
                        </div>
                        <div class="lh-footer-social">
                            <p>Đặt phòng khách sạn dễ dàng, nhanh chóng cùng dịch vụ chuyên nghiệp và nhiều ưu đãi hấp dẫn!</p>
                            <form class="lh-control-footer" role="search">
                                <div class="lh-control-inner-icons">
                                    <i class="ri-send-plane-line"></i>
                                </div>
                                <input class="form-control shadow-none me-4" type="email" placeholder="Đăng ký nhận ưu đãi..." aria-label="Subscribe">
                            </form>
                        </div>
                        <div class="lh-follow-social">
                            <h4 class="heading">Theo dõi chúng tôi</h4>
                            <div class="footer-logo-image">
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/facebook.png') }}" alt="Facebook"></a>
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/twitter.png') }}" alt="Twitter"></a>
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/instagram.png') }}" alt="Instagram"></a>
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/linkedin.png') }}" alt="LinkedIn"></a>
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/dribbble.png') }}" alt="Dribbble"></a>
                                <a href="#"><img src="{{ asset('assets/client/assets/img/logo/pinterest.png') }}" alt="Pinterest"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 rs-pb-24 order-lg-3 order-md-3 order-3">
                    <div class="lh-footer-cols-contain">
                        <div class="lh-footer-heading">
                            <h4>Thông tin liên hệ</h4>
                        </div>
                        <div class="lh-footer-contact-infoemation">
                            <ul>
                                <li class="lh-information">
                                    <h5 class="heading">Địa chỉ</h5>
                                    <span>Cầu Giấy, Hà Nội, Việt Nam</span>
                                </li>
                                <li class="lh-information">
                                    <h5 class="heading">Email</h5>
                                    <span>sinhduong1508@gmail.com</span>
                                </li>
                                <li class="lh-information">
                                    <h5 class="heading">Số điện thoại</h5>
                                    <span>+84 865642497</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-copy">
            <span>Bản quyền © <span id="copyright_year"></span> <a href="{{route('home')}}">Luxurious Hotel</a> thuộc về ước mơ ra trường.</span>
        </div>
    </div>
</footer>
