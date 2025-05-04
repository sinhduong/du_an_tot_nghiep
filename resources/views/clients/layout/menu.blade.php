<div class="lh-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ $systems->logo ? asset('storage/' . $systems->logo) : asset('assets/client/assets/img/logo/logo-2.png') }}" alt="Logo" class="lh-logo">
            </a>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ri-menu-2-line"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Trang Chủ</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Danh sách loại phòng <i class="ri-arrow-down-s-line"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('room.view') }}">Danh sách phòng</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Giới Thiệu <i class="ri-arrow-down-s-line"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('introductions') }}">Giới thiệu</a></li>
                            {{-- <li><a class="dropdown-item" href="{{ route('abouts') }}">Về chúng tôi</a></li> --}}
                            <li><a class="dropdown-item" href="{{ route('faqs') }}">Câu hỏi thường gặp</a></li>
                            <li><a class="dropdown-item" href="{{ route('services') }}">Dịch vụ</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " href="{{ route('contacts') }}" >
                            Liên hệ với cúng tôi
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
