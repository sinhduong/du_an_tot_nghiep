<header class="lh-header">
    <div class="container-fluid">
        <div class="lh-header-items">
            <div class="left-header">
                <a href="javascript:void(0)" class="lh-toggle-sidebar">
                    <span class="outer-ring">
                        <span class="inner-ring"></span>
                    </span>
                </a>
                {{-- <div class="header-search-box">
                    <div class="header-search-drop">
                        <a href="javascript:void(0)" class="open-search"><i class="ri-search-line"></i></a>
                        <form class="lh-search">
                            <input class="search-input" type="text" placeholder="Search...">
                            <a href="javascript:void(0)" class="search-btn"><i class="ri-search-line"></i>
                            </a>
                        </form>
                    </div>
                </div> --}}
            </div>
            <div class="right-header">
                <div class="lh-right-tool lh-flag-drop language">
                    <div class="lh-hover-drop">
                        <div class="lh-hover-tool">
                            <img class="flag" src="{{ asset('assets/admin/assets/img/flag/vn.png') }}" alt="flag">
                        </div>
                        <div class="lh-hover-drop-panel right">
                            <ul>
                                <li><a href="javascript:void(0)"><img class="flag" src="{{ asset('assets/admin/assets/img/flag/vn.png')}}"
                                    alt="flag">Việt nam</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- <div class="lh-right-tool apps">
                    <div class="lh-hover-drop">
                        <div class="lh-hover-tool">
                            <i class="ri-apps-2-line"></i>
                        </div>
                        <div class="lh-hover-drop-panel right apps">
                            <h6 class="title">Apps</h6>
                            <ul>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/1.png')}}"
                                            alt="flag">Github</a></li>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/2.png')}}"
                                            alt="flag">Dribbble</a></li>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/3.png')}}"
                                            alt="flag">Dropbox</a></li>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/4.png')}}"
                                            alt="flag">Figma</a></li>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/5.png')}}"
                                            alt="flag">Meta</a></li>
                                <li><a href="javascript:void(0)"><img class="app" src="{{ asset('assets/admin/assets/img/apps/6.png')}}"
                                            alt="flag">Adsense</a></li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
                <div class="lh-right-tool display-screen">
                    <a class="lh-screen full" href="javascript:void(0)"><i class="ri-fullscreen-line"></i></a>
                    <a class="lh-screen reset" href="javascript:void(0)"><i
                            class="ri-fullscreen-exit-line"></i></a>
                </div>
                {{-- <div class="lh-right-tool">
                    <a class="lh-notify" href="javascript:void(0)">
                        <i class="ri-notification-2-line"></i>
                        <span class="label"></span>
                    </a>
                </div> --}}
                <div class="lh-right-tool display-dark">
                    <a class="lh-mode dark" href="javascript:void(0)"><i class="ri-moon-clear-line"></i></a>
                    <a class="lh-mode light" href="javascript:void(0)"><i class="ri-sun-line"></i></a>
                </div>
                <div class="lh-right-tool lh-user-drop">
                    <div class="lh-hover-drop">
                        <div class="lh-hover-tool">
                            <img class="user" src="{{ asset('assets/admin/assets/img/user/1.jpg') }}" alt="user">
                        </div>
                        <div class="lh-hover-drop-panel right">
                           
                            <ul class="border-top">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="ri-logout-circle-r-line"></i>Đăng xuất</button>
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
