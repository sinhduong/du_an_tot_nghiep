<footer>
    <div class="container">
        <div class="footer-top-section">
        <div class="row">
            <div class="col-lg-3 col-md-6 rs-pb-24 p-991 order-lg-1 order-md-2 order-2">
                <div class="lh-footer-cols-contain">
                    <div class="lh-footer-heading">
                        <h4>Explore</h4>
                    </div>

                    <ul>
                        <li>
                            <code>*</code>
                            <a href="{{route('home')}}">Trang chủ</a>
                        </li>
                        <li>
                            <code>*</code>
                            <a href="{{route('abouts')}}">Về chúng tôi</a>
                        </li>
                        <li>
                            <code>*</code>
                            <a href="{{route('introductions')}}">Giới thiệu</a>
                        </li>
                        <li>
                            <code>*</code>
                            <a href="{{route('faqs')}}">Câu hỏi thường gặp</a>
                        </li>
                        <li>
                            <code>*</code>
                            <a href="restaurant.html">Restaurant</a>
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
                        <p>This is the dolor sit amet consectetur adipisicing elit. Libero voluptatem soluta eaque
                            ullam accusantium animi!</p>
                        <form class="lh-control-footer" role="search">
                            <div class="lh-control-inner-icons">
                                <i class="ri-send-plane-line"></i>
                            </div>
                            <input class="form-control shadow-none me-4" type="search" placeholder="Subscribe..."
                                aria-label="Search">
                        </form>
                    </div>
                    <div class="lh-follow-social">
                        <h4 class="heading">Follow On Social</h4>
                        <div class="footer-logo-image">
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/facebook.png ') }}" alt="facebook"></a>
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/twitter.png ') }}" alt="twitter"></a>
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/instagram.png ') }}" alt="instagram"></a>
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/linkedin.png ') }}" alt="linkedin"></a>
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/dribbble.png ') }}" alt="dribbble"></a>
                            <a href="#"><img src="{{ asset('assets/client/assets/img/logo/pinterest.png ') }}" alt="pinterest"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 rs-pb-24 order-lg-3 order-md-3 order-3">
                <div class="lh-footer-cols-contain ">
                    <div class="lh-footer-heading">
                        <h4>Contact Information</h4>
                    </div>
                    <div class="lh-footer-contact-infoemation">
                        <ul>
                            <li class="lh-information">
                                <h5 class="heading">Location</h5>
                                <span>5 Green st.Huntington stati on, Ny 11746, Libero voluptatem soluta
                                    Bharat.</span>
                            </li>
                            <li class="lh-information">
                                <h5 class="heading">Email</h5>
                                <span>example@ec-email.com</span>
                            </li>
                            <li class="lh-information">
                                <h5 class="heading">Phone No</h5>
                                <span>+91(1234)(567)(890)</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="footer-bottom-copy">
            <span>Copyright @ <span id="copyright_year"></span> <a href="index.html">Luxurious</a> All Rights Reserved</span>
        </div>
    </div>
</footer>
