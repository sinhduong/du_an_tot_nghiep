<div class="lh-sidebar-overlay"></div>
<div class="lh-sidebar" data-mode="light">
    <div class="lh-sb-logo">
        <a href="index.html" class="sb-full"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}"
                alt="logo"></a>
        <a href="index.html" class="sb-collapse"><img src="{{ asset('assets/admin/assets/img/logo/collapse-logo.png') }}"
                alt="logo"></a>
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
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Quản lý tài khoản<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="team-add.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh
                                sách tài khản </a></li>
                        <li><a href="team-update.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Tài
                                khoản quản trị</a></li>
                        <li><a href="team-profile.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Tài
                                khoản Khác</a></li>
<<<<<<< HEAD
                        <li><a href="team-list.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh
                                sách nhân viên</a></li>
=======
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách nhân viên</a></li>
                        <li><a href="{{ route('admin.staffs.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách đã xóa mềm</a></li>
>>>>>>> 97bdc70b8bb5462aa09108e4d7e3c0cab57782c6
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý Loại phòng<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.room_types.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        <li><a href="{{ route('admin.room_types.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        <li><a href="{{ route('admin.room_types.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách đã xóa mềm</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý phòng<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.rooms.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.rooms.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        {{-- <li><a href="{{ route('admin.rooms.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách phòng đã xóa mềm</a></li> --}}
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý nhân viên<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.staffs.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        <li><a href="{{ route('admin.staffs.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách đã xóa mềm</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý đánh giá<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.reviews.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quy tắc && quy định<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.rule-regulations.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        <li><a href="{{ route('admin.rule-regulations.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        <li><a href="{{ route('admin.rule-regulations.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách xóa mềm</a></li>
                        <li class="lh-sb-item sb-drop-item">
                            <a href="javascript:void(0)" class="lh-drop-toggle">
                                <i class="ri-home-8-line"></i><span class="condense">Thêm QUy Định Vào Phòng<i
                                        class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                            <ul class="lh-sb-drop condense">
                                <li><a href="{{ route('admin.rule-regulations.room_index') }}"
                                        class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh sách</a></li>
                                <li><a href="{{ route('admin.rule-regulations.create') }}"
                                        class="lh-page-link drop"><i class="ri-git-commit-line"></i>Thêm mới</a></li>
                                <li><a href="{{ route('admin.rule-regulations.trashed_room') }}"
                                        class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh sách xóa
                                        mềm</a></li>
                                <li><a href="{{ route('admin.rule-regulations.create_room') }}"
                                        class="lh-page-link drop"><i class="ri-git-commit-line"></i>Thêm Vào Phòng
                                    </a>

                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý tiện nghi<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.amenities.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        <li><a href="{{ route('admin.amenities.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        <li><a href="{{ route('admin.amenities.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách xóa mềm</a></li>
                        <li><a href="{{ route('admin.amenities.room_index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách Tiện Ích Phòng</a></li>
                        <li><a href="{{ route('admin.amenities.trashed_room') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách Tiện Ích xóa mềm</a></li>
                        <li><a href="{{ route('admin.amenities.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách xóa mềm</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý đặt phòng <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.bookings.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách đặt phòng</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý khuyến mãi <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.promotions.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách khuyến</a></li>
                        <li><a href="{{ route('admin.promotions.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mã khuyến mãi </a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item">
                    <a href="invoice.html" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Hóa đơn</span>
                        </span>
                    </a>
                </li>

                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Pages</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-pages-fill"></i><span class="condense">Quản lý page<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">

                        <li><a href="team-add.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Trang
                                liên hệ</a></li>
                        <li><a href="team-add.html" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Trang
                                liên hệ</a></li>

                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-title condense">Settings</li>
                <li class="lh-sb-item">
                    <a href="role.html" class="lh-page-link">
                        <i class="ri-magic-line"></i><span class="condense"><span
                                class="hover-title"></span></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
