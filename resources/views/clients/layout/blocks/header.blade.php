<header>
    <div class="lh-top-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-12 lh-top-social">
                    <div class="lh-mail">
                        <a href="mailto:{{ $systems->email ?? '' }}">
                            <i class="ri-mail-line"></i>
                        </a>
                        {{ $systems->email ?? 'Email chưa được thiết lập' }}
                    </div>
                    <div class="lh-location">
                        <i class="ri-map-pin-line"></i>
                        {{ $systems->address ?? 'Địa chỉ chưa được thiết lập' }}
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 lh-top-social">
                    <div class="lh-phone">
                        <i class="ri-phone-line"></i>
                        {{ $systems->phone ?? 'Số điện thoại chưa được thiết lập' }}
                    </div>
                    <div class="lh-header-icons">
                        <a href="javascript:void(0)"><i class="ri-facebook-box-line facebook"></i></a>
                        <a href="javascript:void(0)"><i class="ri-twitter-x-line twitter"></i></a>
                        <a href="javascript:void(0)"><i class="ri-linkedin-box-line linkedin"></i></a>
                        <a href="javascript:void(0)"><i class="ri-instagram-line instagram"></i></a>
                        @if (Route::has('login'))
                            @auth
                                <a href="#" class="dropdown-toggle" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                                    <i class="ri-user-2-fill"></i> Tài khoản
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="mdi mdi-account"></i> Hồ sơ</a></li>
                                    <li><a class="dropdown-item" href="{{ route('bookings.index') }}"><i class="ri-contacts-book-line"></i> Đơn đặt phòng</a></li>
                                    <li><a class="dropdown-item" href="{{ route('payments.lists') }}"><i class="ri-bank-card-2-line"></i> Lịch sử giao dịch</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Đăng xuất</button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ route('login') }}"><i class="ri-user-2-fill"></i> Đăng nhập</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"><i class="ri-user-2-fill"></i> Đăng ký</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
   @include('clients.layout.menu')
</header>
