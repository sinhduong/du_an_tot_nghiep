@extends('layouts.client')

@section('content')
<!-- Banner -->
<section class="section-banner">
    <div class="row banner-image">
        <div class="banner-overlay"></div>
        <div class="banner-section">
            <div class="lh-banner-contain">
                <h2>Phòng</h2>
                <div class="lh-breadcrumb">
                    <h5>
                        <span class="lh-inner-breadcrumb">
                            <a href="index.html">Trang chủ</a>
                        </span>
                        <span> / </span>
                        <span>
                            <a href="javascript:void(0)">Phòng</a>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rooms -->
<section class="section-rooms bg-gray padding-tb-100">
    <div class="container">
        <div class="banner" data-aos="fade-up" data-aos-duration="2000">
            <h2>Chọn phòng của bạn <span>Room</span></h2>
        </div>
        <div class="row mtb-m-12">


            @foreach ($roomTypes as $roomType)
            <div class="col-xl-6 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                <div class="rooms-card">
                    <img src="{{ $roomType->roomTypeImages->isNotEmpty() ? asset('storage/' . $roomType->roomTypeImages->first()->image) : asset('assets/img/room/default.jpg') }}" alt="{{ $roomType->name }}">
                    <div class="details">
                        <h3>{{ $roomType->name }}</h3>
                        <span>{{ number_format($roomType->price, 0, ',', '.') }} / Đêm</span>
                        <ul>
                            <li><i class="ri-group-line"></i>{{ $roomType->max_capacity }} Người</li>
                            {{-- <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li> <!-- Có thể thêm trường bed_type trong RoomType nếu cần -->
                                <li><i class="ri-restaurant-2-line"></i>Breakfast</li> <!-- Có thể thêm trường has_breakfast nếu cần --> --}}
                            @if($roomType->amenities->contains('name', 'Swimming Pool'))
                            <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                            @endif
                            @if($roomType->amenities->contains('name', 'Free Wifi'))
                            <li><i class="ri-wifi-line"></i>Free Wifi</li>
                            @endif
                        </ul>
                        <a href="{{ route('room.details', $roomType->id) }}" class="lh-buttons-2">Xem thêm <i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
            </div>
            @endforeach


            {{--
            <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
                <div class="rooms-card">
                    <img src="{{ asset('assets/client/assets/img/room/7.jpg')}}" alt="room">
            <div class="details">
                <h3>Family Rooms</h3>
                <span>$600 / Night</span>
                <ul>
                    <li><i class="ri-group-line"></i>2 Persons</li>
                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                    <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                    <li><i class="ri-wifi-line"></i>Free Wifi</li>
                </ul>
                <a href="#" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
        <div class="rooms-card">
            <img src="{{ asset('assets/client/assets/img/room/8.jpg')}}" alt="room">
            <div class="details">
                <h3>Deluxe Rooms</h3>
                <span>$800 / Night</span>
                <ul>
                    <li><i class="ri-group-line"></i>2 Persons</li>
                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                    <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                    <li><i class="ri-wifi-line"></i>Free Wifi</li>
                </ul>
                <a href="#" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
        <div class="rooms-card">
            <img src="{{ asset('assets/client/assets/img/room/9.jpg')}}" alt="room">
            <div class="details">
                <h3>Premium Rooms</h3>
                <span>$1000 / Night</span>
                <ul>
                    <li><i class="ri-group-line"></i>2 Persons</li>
                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                    <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                    <li><i class="ri-wifi-line"></i>Free Wifi</li>
                </ul>
                <a href="#" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
        <div class="rooms-card">
            <img src="{{ asset('assets/client/assets/img/room/10.jpg')}}" alt="room">
            <div class="details">
                <h3>VIP Rooms</h3>
                <span>$1200 / Night</span>
                <ul>
                    <li><i class="ri-group-line"></i>2 Persons</li>
                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                    <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                    <li><i class="ri-wifi-line"></i>Free Wifi</li>
                </ul>
                <a href="#" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-duration="1500">
        <div class="rooms-card">
            <img src="{{ asset('assets/client/assets/img/room/11.jpg')}}" alt="room">
            <div class="details">
                <h3>VVIP Rooms</h3>
                <span>$1600 / Night</span>
                <ul>
                    <li><i class="ri-group-line"></i>2 Persons</li>
                    <li><i class="ri-hotel-bed-line"></i>1 Double Bed</li>
                    <li><i class="ri-restaurant-2-line"></i>Breakfast</li>
                    <li><i class="mdi mdi-pool"></i>Swimming Pool</li>
                    <li><i class="ri-wifi-line"></i>Free Wifi</li>
                </ul>
                <a href="#" class="lh-buttons-2">View More <i class="ri-arrow-right-line"></i></a>
            </div>
        </div>
    </div> --}}
    </div>
    </div>
</section>

<!-- Footer -->

<!-- Tap to top -->
<a href="#" class="back-to-top result-placeholder">
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
                    <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/1.png')}}" alt="skin-1"></span>
                </li>
                <li class="skin-2">
                    <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/2.png')}}" alt="skin-2"></span>
                </li>
                <li class="skin-3 active">
                    <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/3.png')}}" alt="skin-3"></span>
                </li>
            </ul>
            <div class="heading">
                <h2>Border Radius Mode</h2>
            </div>
            <ul class="border-mode">
                <li class="lh-radius radius-mode active-radius">
                    <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/box-1.png')}}" alt="skin-1"></span>
                </li>
                <li class="lh-radius radius-mode-none">
                    <span class="lh-all-color"><img src="{{ asset('assets/client/assets/img/skin/box-2.png')}}" alt="skin-1"></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Plugins JS -->
<script src="{{ asset('assets/client/assets/js/vendor/jquery.min.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/swiper-bundle.min.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/magnific-popup.min.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/aos.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/semantic.min.js')}}"></script>
<script src="{{ asset('assets/client/assets/js/vendor/slick.min.js')}}"></script>

<!-- Main-js -->
<script src="{{ asset('assets/client/assets/js/main.js')}}"></script>

<!-- Code injected by live-server -->
<script>
    // <![CDATA[  <-- For SVG support
    if ('WebSocket' in window) {
        (function() {
            function refreshCSS() {
                var sheets = [].slice.call(document.getElementsByTagName("link"));
                var head = document.getElementsByTagName("head")[0];
                for (var i = 0; i < sheets.length; ++i) {
                    var elem = sheets[i];
                    var parent = elem.parentElement || head;
                    parent.removeChild(elem);
                    var rel = elem.rel;
                    if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
                        var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
                        elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
                    }
                    parent.appendChild(elem);
                }
            }
            var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
            var address = protocol + window.location.host + window.location.pathname + '/ws';
            var socket = new WebSocket(address);
            socket.onmessage = function(msg) {
                if (msg.data == 'reload') window.location.reload();
                else if (msg.data == 'refreshcss') refreshCSS();
            };
            if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
                console.log('Live reload enabled.');
                sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
            }
        })();
    } else {
        console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    }
    // ]]>
</script>
@endsection