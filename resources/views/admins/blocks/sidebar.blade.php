<div class="lh-sidebar-overlay"></div>
<div class="lh-sidebar" data-mode="light">
    <div class="lh-sb-logo">
        <a href="index.html" class="sb-full"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}" alt="logo"></a>
        <a href="index.html" class="sb-collapse"><img src="{{ asset('assets/admin/assets/img/logo/collapse-logo.png') }}" alt="logo"></a>
    </div>
    <div class="lh-sb-wrapper">
        <div class="lh-sb-content">
            <ul class="lh-sb-list">
                <li class="lh-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}">
                    {{-- <a  href="javascript:void(0)" class="lh-drop-toggle"> --}}
                        <i class="ri-dashboard-3-line"></i>
                        <span class="condense">Thống kê</span>
                    </a>
                {{-- </a> --}}
                </li>
                <li class="lh-sb-item-separator"></li>
                {{-- <li class="lh-sb-title condense">Apps</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Staff<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="team-profile.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Team Profile</a></li>
                        <li><a href="team-add.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Team Add</a></li>
                        <li><a href="team-update.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Team Update</a></li>
                        <li><a href="team-list.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Team List</a></li>
                    </ul>
                </li> --}}
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Hotel</li>

                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Hotel<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.hotels.index') }}" class="lh-page-link drop"><i
                                class="ri-file-list-3-fill"></i>list</a></li>
                        <li><a href="{{ route('admin.hotels.create') }}" class="lh-page-link drop"><i
                                class="ri-git-repository-commits-line"></i>Thêm</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Tài khoản</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-user-search-line"></i><span class="condense">Quản trị<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="signin.html" class="lh-page-link drop"><i
                                class="mdi mdi-account-circle"></i>Tài khoản quản trị</a></li>
                        <li><a href="signup.html" class="lh-page-link drop"><i
                                class="mdi mdi-account-circle-outline"></i>Tài khoản khách hàng</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item">
                    <a href="menu.html" class="lh-page-link">
                        <i class="ri-restaurant-2-line"></i><span class="condense"><span
                                class="hover-title">Menu</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="menu-add.html" class="lh-page-link">
                        <i class="ri-restaurant-2-line"></i><span class="condense"><span class="hover-title">Add
                                Menu</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="orders.html" class="lh-page-link">
                        <i class="ri-bookmark-3-line"></i><span class="condense"><span
                                class="hover-title">Orders</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Extra</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-pages-line"></i><span class="condense">Authentication<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="signin.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Login</a></li>
                        <li><a href="signup.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Signup</a></li>
                        <li><a href="forgot.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Forgot password</a></li>
                        <li><a href="reset-password.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Reset password</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-service-line"></i><span class="condense">Service pages<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="404-error-page.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>404 error</a></li>
                        <li><a href="maintenance.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Maintenance</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Elements</li>
                <li class="lh-sb-item">
                    <a href="remix-icons.html" class="lh-page-link">
                        <i class="ri-remixicon-line"></i><span class="condense"><span class="hover-title">remix
                                icons</span></span></a>
                </li>
                <li class="lh-sb-item">
                    <a href="material-icons.html" class="lh-page-link">
                        <i class="mdi mdi-material-ui"></i><span class="condense"><span
                                class="hover-title">Material icons</span></span></a>
                </li>
                <li class="lh-sb-item">
                    <a href="alert-popup.html" class="lh-page-link">
                        <i class="ri-file-warning-line"></i><span class="condense"><span
                                class="hover-title">Alert Popup</span></span></a>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Settings</li>
                <li class="lh-sb-item">
                    <a href="role.html" class="lh-page-link">
                        <i class="ri-magic-line"></i><span class="condense"><span
                                class="hover-title">Role</span></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
