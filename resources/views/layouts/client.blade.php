<!--
    Item Name: Luxurious - Hotel Booking HTML Template + Admin Dashboard.
    Author: ashishmaraviya
    Version: 2.2.0
    Copyright 2024
	Author URI: https://themeforest.net/user/ashishmaraviya
-->
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from maraviyainfotech.com/projects/luxurious-html-v22/luxurious-html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Dec 2024 10:26:54 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Best Luxurious Hotel Booking Template.">
    <meta name="keywords"
        content="hotel, booking, business, restaurant, spa, resort, landing, agency, corporate, start up, site design, new business site, business template, professional template, classic, modern">
    <title>Luxurious - Hotel Booking HTML Template + Admin Dashboard</title>

    <link rel="icon" href="{{ asset('assets/client/assets/img/favicons/favicon.png ') }}" type="image/x-icon">

    <!-- Css All Plugins Files -->
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/bootstrap.min.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/magnific-popup.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/aos.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/remixicon.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/materialdesignicons.min.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/swiper-bundle.min.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/semantic.min.css ') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/vendor/slick.min.css ') }}">

    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('assets/client/assets/css/style.css ') }}">

</head>

<body>
    <!-- Overlay -->
    <div class="overlay"></div>
    <div class="lh-loader">
        <span class="loader"></span>
    </div>

    <!-- Header -->
    @include('clients.layout.blocks.header')

    <!-- Mobile-menu -->
    @include('clients.layout.menu')
    {{-- @include('clients.layout.blocks.slider') --}}
    <!-- Hero -->


    <main>
        @yield('content')
    </main>


    <!-- Tìm kiếm -->
   {{-- @include('clients.layout.search') --}}
    <!-- Room -->
    {{-- @include('clients.room.room') --}}

    <!-- About -->
    {{-- @include('clients.layout.about') --}}

    <!-- Tiện nghi - Amenities-->
    {{-- @include('clients.layout.Amenities') --}}

    <!-- Giá tốt nhất -->
    {{-- @include('clients.room.room-price-hot') --}}

    <!-- Lời chứng thực - Testimonials -->
   {{-- @include('clients.layout.Testimonials') --}}

    <!-- Blog -->
   {{-- @include('clients.layout.blog') --}}

    <!-- Footer -->
    @include('clients.layout.blocks.footer')

    <!-- Tap to top -->
    {{-- <a href="#" class="back-to-top result-placeholder">
        <i class="ri-arrow-up-double-line"></i>
    </a>

    <!-- Side-tool -->
    <div class="tool-overlay"></div>
    <div class="lh-tool">
        <div class="lh-tool-btn">
            <button type="button" class="btn-lh-tool result-placeholder close-tool">
                <i class="ri-settings-line"></i>
            </button>
            <div class="color-variant">
                <div class="tool-header">
                    <h5>Tools</h5>
                </div>
                <div class="heading">
                    <h2>Select Color</h2>
                </div>
                <ul class="lh-color">
                    <li class="colors c1 active-colors">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c2">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c3">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c4">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c5">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c6">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c7">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c8">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c9">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="colors c10">
                        <span class="lh-all-color"></span>
                    </li>
                </ul>
                <div class="heading">
                    <h2>Dark mode</h2>
                </div>
                <ul class="dark-mode">
                    <li class="dark">
                        <span class="lh-all-color"></span>
                    </li>
                    <li class="white active-dark-mode">
                        <span class="lh-all-color"></span>
                    </li>
                </ul>
                <div class="heading">
                    <h2>Skin mode</h2>
                </div>
                <ul class="skin-mode">
                    <li class="skin-1">
                        <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/1.png ') }}" alt="skin-1"></span>
                    </li>
                    <li class="skin-2">
                        <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/2.png ') }}" alt="skin-2"></span>
                    </li>
                    <li class="skin-3 active">
                        <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/3.png ') }}" alt="skin-3"></span>
                    </li>
                </ul>
                <div class="heading">
                    <h2>Border Radius Mode</h2>
                </div>
                <ul class="border-mode">
                    <li class="lh-radius radius-mode active-radius">
                        <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/box-1.png ') }}" alt="skin-1"></span>
                    </li>
                    <li class="lh-radius radius-mode-none">
                        <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/box-2.png ') }}" alt="skin-1"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}

    <!-- Plugins JS -->
    <script src="{{ asset('assets/client/assets/js/vendor/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/swiper-bundle.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/magnific-popup.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/aos.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/semantic.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/slick.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/particles.min.js')}}"></script>
    <script src="{{ asset('assets/client/assets/js/vendor/app.js')}}"></script>

    <!-- Main-js -->
    <script src="{{ asset('assets/client/assets/js/main.js')}}"></script>

</body>


<!-- Mirrored from maraviyainfotech.com/projects/luxurious-html-v22/luxurious-html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Dec 2024 10:27:00 GMT -->
</html>
