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
                <li class="lh-sb-title condense">Apps</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Quản lý tài khoản<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">

                        <li><a href="team-add.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Tài khoản khách hàng</a></li>
                        <li><a href="team-update.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Tài khoản quản trị</a></li>
                        <li><a href="team-profile.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Tài khoản cá nhân</a></li>
                        <li><a href="team-list.html" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách nhân viên</a></li>
                    </ul>
                </li>

                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Khách sạn | KHU NGHỈ MÁT</li>
                <li class="lh-sb-item">
                    <a href="guest.html" class="lh-page-link">
                        <i class="ri-group-line"></i><span class="condense"><span class="hover-title">Khách hàng</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="guest-details.html" class="lh-page-link">
                        <i class="ri-user-search-line"></i><span class="condense"><span class="hover-title">chi tiết khách hàng</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Quản lý Loại phòng<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">

                        <li><a href="{{ route('admin.room_types.index') }}" class="lh-page-link drop"><i
                                    class="ri-home-8-line"></i>Danh sách Loại phòng</a></li>
                        <li><a href="{{ route('admin.room_types.create') }}" class="lh-page-link drop"><i
                                    class="ri-home-8-line"></i>Thêm mới loại phòng</a></li>
                        <li><a href="{{ route('admin.room_types.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-home-8-line"></i>Danh sách loại phòng đã xóa mềm</a></li>
                    </ul>
                </li>

                <li class="lh-sb-item">
                    <a href="{{ route('admin.rooms.index') }}" class="lh-page-link">
                        <i class="ri-home-8-line"></i><span class="condense"><span class="hover-title">Phòng</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="bookings.html" class="lh-page-link">
                        <i class="ri-contacts-book-line"></i><span class="condense"><span class="hover-title">Đặt phòng</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="bookings.html" class="lh-page-link">
                        <i class="ri-barcode-line"></i><span class="condense"><span class="hover-title">Khuyến mãi</span> </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="invoice.html" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Hóa đơn</span> </span>
                    </a>
                </li>


                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Settings</li>
                <li class="lh-sb-item">
                    <a href="role.html" class="lh-page-link">
                        <i class="ri-magic-line"></i><span class="condense"><span
                                class="hover-title">Quyền</span></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
