<div class="lh-sidebar-overlay"></div>
<div class="lh-sidebar" data-mode="light">
    <div class="lh-sb-logo">
        <a href="{{ route('admin.dashboard') }}" class="sb-full"><img src="{{ asset('assets/admin/assets/img/logo/full-logo.png') }}"
                alt="logo"></a>
        <a href="{{ route('admin.dashboard') }}" class="sb-collapse"><img src="{{ asset('assets/admin/assets/img/logo/collapse-logo.png') }}"
                alt="logo"></a>
    </div>
    <div class="lh-sb-wrapper">
        <div class="lh-sb-content">
            <ul class="lh-sb-list">
                <li class="lh-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="ri-dashboard-3-line"></i>
                        <span class="condense">Thống kê</span>
                    </a>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Quản lý tài khoản<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.customers.index') }}" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh
                                sách khách hàng</a></li>
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách quản trị viên</a></li>
                        <li><a href="{{ route('admin.roles.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Phân quyền người dùng</a></li>
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
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
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
                        <li><a href="{{ route('admin.rooms.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quy tắc && quy định<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.rule-regulations.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.rule-regulations.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        <li><a href="{{ route('admin.rule-regulations.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <!-- Tách "Dịch vụ & Tiện nghi" thành hai mục riêng -->
                <!-- Quản lý Dịch vụ -->
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-service-line"></i><span class="condense">Quản lý Dịch vụ<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.services.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        <li><a href="{{ route('admin.services.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        <li><a href="{{ route('admin.services.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <!-- Quản lý Tiện nghi -->
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-hotel-line"></i><span class="condense">Quản lý Tiện nghi<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.amenities.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        <li><a href="{{ route('admin.amenities.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        <li><a href="{{ route('admin.amenities.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-user-line"></i><span class="condense">Quản lý Nhân viên<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span>
                    </a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách Nhân viên</a></li>
                        <li><a href="{{ route('admin.staffs.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới Nhân viên</a></li>
                        <li class="sb-drop-item">
                            <a href="javascript:void(0)" class="lh-drop-toggle">
                                <i class="ri-shield-user-line"></i> Vai trò Nhân viên <i
                                    class="drop-arrow ri-arrow-down-s-line"></i>
                            </a>
                            <ul class="lh-sb-drop condense">
                                <li><a href="{{ route('admin.staff_roles.index') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Danh sách Vai trò</a></li>
                                <li><a href="{{ route('admin.staff_roles.create') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Thêm mới Vai trò</a></li>
                            </ul>
                        </li>
                        <li class="sb-drop-item">
                            <a href="javascript:void(0)" class="lh-drop-toggle">
                                <i class="ri-time-line"></i> Ca làm việc <i
                                    class="drop-arrow ri-arrow-down-s-line"></i>
                            </a>
                            <ul class="lh-sb-drop condense">
                                <li><a href="{{ route('admin.staff_shifts.index') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Danh sách Ca làm</a></li>
                                <li><a href="{{ route('admin.staff_shifts.create') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Thêm mới Ca làm</a></li>
                            </ul>
                        </li>
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
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý khuyến mãi <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.promotions.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.promotions.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Khuyến mãi loại phòng <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.sale-room-types.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý Chính Sách <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.policies.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.policies.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm </a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Dịch vụ phát sinh <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.service_plus.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        <li><a href="{{ route('admin.service_plus.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm </a></li>
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
                <li class="lh-sb-item">
                    <a href="{{ route('admin.payments.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Lịch sử thanh
                                toán</span>
                        </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="{{ route('admin.contacts.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Liên hệ</span>
                        </span>
                    </a>
                </li>
                <li class="lh-sb-item">
                    <a href="{{ route('admin.faqs.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Câu hỏi thường
                                gặp</span>
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
                        <li><a href="{{ route('admin.abouts.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Trang Về chúng tôi</a></li>
                        <li><a href="{{ route('admin.introductions.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Trang Giới thiệu</a></li>
                        <li><a href="{{ route('admin.banners.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Quản lý banner</a></li>
                        <li><a href="{{ route('admin.systems.index') }}" class="lh-page-link drop"><i
                                        class="ri-git-commit-line"></i>Quản lý system </a></li>
                    </ul>
                </li>
                <li class="lh-sb-title condense">Settings</li>
            </ul>
        </div>
    </div>
</div>
