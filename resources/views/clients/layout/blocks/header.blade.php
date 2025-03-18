<header>
    <div class="lh-top-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-12  lh-top-social">
                   <div class="lh-mail">
                    <a href="">
                        <i class="ri-mail-line"></i>
                    </a>
                        sinh.duongvan24@gmail.com
                    </div>

                    <div class="lh-location">
                        <div class="custom-select">
                            <i class="ri-map-pin-line"></i>
                            <select>
                                <option value="option1">Hà Nội</option>
                                <option value="option2">Bắc Giang</option>
                                <option value="option3">Bắc Ninh</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 lh-top-social">
                    <div class="lh-phone">
                        <i class="ri-phone-line"></i>
                        +84(865)(642)(497)
                    </div>
                    <div class="lh-header-icons">
                        <a href="javascript:void(0)"><i class="ri-facebook-box-line facebook"></i></a>
                        <a href="javascript:void(0)"><i class="ri-twitter-x-line twitter"></i></a>
                        <a href="javascript:void(0)"><i class="ri-linkedin-box-line linkedin"></i></a>
                        <a href="javascript:void(0)"><i class="ri-instagram-line instagram"></i></a>
                        @if (Route::has('login'))
                            @auth
                            <a href="" class="dropdown-toggle " role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-user-2-fill">Tài khoản</i>

                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="mdi mdi-account"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('bookings.index') }}"><i class="ri-contacts-book-line"></i>Đơn đặt phòng</a></li>
                                <li>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-center">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>

                                @else
                                    <a href="{{ route('login') }}"><i class="ri-user-2-fill"></i>Đăng nhập </a>
                                    @if (Route::has('register'))
                                    <a href="{{ route('register') }}"><i class="ri-user-2-fill"></i>Đăng ký </a>
                                    @endif
                            @endauth
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lh-header">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('assets/client/assets/img/logo/logo.png ') }}" alt="logo" class="lh-logo">
                    </a>
                    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ri-menu-2-line"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button"
                                    data-bs-toggle="dropdown">
                                    Trang Chủ
                                    <i class="ri-arrow-down-s-line"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="index.html">Home Layout 1</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="demo-2.html">Home Layout 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button"
                                    data-bs-toggle="dropdown">
                                    Danh Sách Khách Sạn
                                    <i class="ri-arrow-down-s-line"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="gallery.html">Danh sách 1</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="gallery-2.html">Danh sách 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button"
                                    data-bs-toggle="dropdown">
                                    Phòng
                                    <i class="ri-arrow-down-s-line"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="room.html">Rooms</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="room-2.html">Rooms 2</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="room-details.html">Rooms details</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button"
                                    data-bs-toggle="dropdown">
                                    Giới Thiệu
                                    <i class="ri-arrow-down-s-line"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="about.html">Về chúng tôi</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="contact.html">Liên hệ</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('faqs') }}">Câu hỏi thường gặp</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="prices.html">Dịch vụ</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button"
                                    data-bs-toggle="dropdown">
                                    Tin Tức
                                    <i class="ri-arrow-down-s-line"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="blog.html">Blog</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="blog-details.html">Blog Details</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="restaurant.html">
                                    Nhà hàng
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
