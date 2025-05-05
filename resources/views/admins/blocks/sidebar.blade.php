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
                @can('dashboard')
                <li class="lh-sb-item sb-drop-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="ri-dashboard-3-line"></i>
                        <span class="condense">Thống kê</span>
                        </a>
                    </li>
                    <li class="lh-sb-item-separator"></li>
                @endcan

                @canany(['customers_list', 'staffs_list', 'roles_list'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-shield-user-line"></i><span class="condense">Quản lý tài khoản<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('customers_list')
                        <li><a href="{{ route('admin.customers.index') }}" class="lh-page-link drop"><i class="ri-git-commit-line"></i>Danh
                                sách khách hàng</a></li>
                        @endcan
                        @can('staffs_list')
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách quản trị viên</a></li>
                        @endcan
                        @can('roles_list')
                        <li><a href="{{ route('admin.roles.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Phân quyền người dùng</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan


                @canany(['room_types_list', 'room_types_create', 'room_types_trashed'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý Loại phòng<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('room_types_list')
                        <li><a href="{{ route('admin.room_types.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        @endcan
                        @can('room_types_create')
                        <li><a href="{{ route('admin.room_types.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        @endcan
                        @can('room_types_trashed')
                        <li><a href="{{ route('admin.room_types.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan


                @canany(['rooms_list', 'rooms_create', 'rooms_trashed'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quản lý phòng<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('rooms_list')
                        <li><a href="{{ route('admin.rooms.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        @endcan
                        @can('rooms_create')
                        <li><a href="{{ route('admin.rooms.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        @endcan
                        @can('rooms_trashed')
                        <li><a href="{{ route('admin.rooms.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan


                @canany(['rules_and_regulations_list', 'rules_and_regulations_create', 'rules_and_regulations_trashed'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-home-8-line"></i><span class="condense">Quy tắc && quy định<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('rules_and_regulations_list')
                        <li><a href="{{ route('admin.rule-regulations.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        @endcan
                        @can('rules_and_regulations_create')
                        <li><a href="{{ route('admin.rule-regulations.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        @endcan
                        @can('rules_and_regulations_trashed')
                        <li><a href="{{ route('admin.rule-regulations.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan
                <!-- Tách "Dịch vụ & Tiện nghi" thành hai mục riêng -->
                <!-- Quản lý Dịch vụ -->
                @canany(['services_list', 'services_create', 'services_trashed'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-service-line"></i><span class="condense">Quản lý Dịch vụ<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('services_list')
                        <li><a href="{{ route('admin.services.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        @endcan
                        @can('services_create')
                        <li><a href="{{ route('admin.services.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        @endcan
                        @can('services_trashed')
                        <li><a href="{{ route('admin.services.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan
                <!-- Quản lý Tiện nghi -->
                @canany(['amenities_list', 'amenities_create', 'amenities_trashed'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-hotel-line"></i><span class="condense">Quản lý Tiện nghi<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('amenities_list')
                        <li><a href="{{ route('admin.amenities.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        @endcan
                        @can('amenities_create')
                        <li><a href="{{ route('admin.amenities.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        @endcan
                        @can('amenities_trashed')
                        <li><a href="{{ route('admin.amenities.trashed') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thùng rác</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                @canany(['staffs_list', 'staffs_create', 'staffs_trashed', 'staff_roles_list', 'staff_roles_create', 'staff_shifts_list', 'staff_shifts_create'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-user-line"></i><span class="condense">Quản lý Nhân viên<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span>
                    </a>
                    <ul class="lh-sb-drop condense">
                        @can('staffs_list')
                        <li><a href="{{ route('admin.staffs.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách Nhân viên</a></li>
                        @endcan
                        @can('staffs_create')
                        <li><a href="{{ route('admin.staffs.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới Nhân viên</a></li>
                        @endcan

                        @canany(['staff_roles_list', 'staff_roles_create'])
                        <li class="sb-drop-item">
                            <a href="javascript:void(0)" class="lh-drop-toggle">
                                <i class="ri-shield-user-line"></i> Vai trò Nhân viên <i
                                    class="drop-arrow ri-arrow-down-s-line"></i>
                            </a>
                            <ul class="lh-sb-drop condense">
                                @can('staff_roles_list')
                                <li><a href="{{ route('admin.staff_roles.index') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Danh sách Vai trò</a></li>
                                @endcan
                                @can('staff_roles_create')
                                <li><a href="{{ route('admin.staff_roles.create') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Thêm mới Vai trò</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        @canany(['staff_shifts_list', 'staff_shifts_create'])
                        <li class="sb-drop-item">
                            <a href="javascript:void(0)" class="lh-drop-toggle">
                                <i class="ri-time-line"></i> Ca làm việc <i
                                    class="drop-arrow ri-arrow-down-s-line"></i>
                            </a>
                            <ul class="lh-sb-drop condense">
                                @can('staff_shifts_list')
                                <li><a href="{{ route('admin.staff_shifts.index') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Danh sách Ca làm</a></li>
                                @endcan
                                @can('staff_shifts_create')
                                <li><a href="{{ route('admin.staff_shifts.create') }}" class="lh-page-link drop"><i
                                            class="ri-git-commit-line"></i>Thêm mới Ca làm</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                @can('reviews_list')
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
                @endcan

                @canany(['promotions_list', 'promotions_create'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý khuyến mãi <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('promotions_list')
                        <li><a href="{{ route('admin.promotions.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        @endcan
                        @can('promotions_create')
                        <li><a href="{{ route('admin.promotions.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới </a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                
                @can('sale_room_types_list')
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
                @endcan

               
                @canany(['policies_list', 'policies_create'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý Chính Sách <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('policies_list')
                        <li><a href="{{ route('admin.policies.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        @endcan
                        @can('policies_create')
                        <li><a href="{{ route('admin.policies.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm </a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                
                @canany(['service_plus_list', 'service_plus_create'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Dịch vụ phát sinh <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('service_plus_list')
                        <li><a href="{{ route('admin.service_plus.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách </a></li>
                        @endcan
                        @can('service_plus_create')
                        <li><a href="{{ route('admin.service_plus.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm </a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                
                @can('payment_settings_list')
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý tỷ lệ đặt cọc <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        <li><a href="{{ route('admin.payment-settings.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách tỷ lệ</a></li>
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan
               
                @canany(['refund_policies_list', 'refund_policies_create'])
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-contacts-book-line"></i><span class="condense">Quản lý chính sách hoàn tiền <i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('refund_policies_list')
                        <li><a href="{{ route('admin.refund-policies.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Danh sách</a></li>
                        @endcan
                        @can('refund_policies_create')
                        <li><a href="{{ route('admin.refund-policies.create') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Thêm mới</a></li>
                        @endcan
                    </ul>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan

                @can('bookings_list')
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
                @endcan


                @can('payments_list')
                <li class="lh-sb-item">
                    <a href="{{ route('admin.payments.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Lịch sử thanh
                                toán</span>
                        </span>
                    </a>
                </li>
                @endcan

                @can('contacts_list')
                <li class="lh-sb-item">
                    <a href="{{ route('admin.contacts.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Liên hệ</span>
                        </span>
                    </a>
                </li>
                @endcan

                @can('faqs_list')
                <li class="lh-sb-item">
                    <a href="{{ route('admin.faqs.index') }}" class="lh-page-link">
                        <i class="ri-bill-line"></i><span class="condense"><span class="hover-title">Câu hỏi thường
                                gặp</span>
                        </span>
                    </a>
                </li>
                <li class="lh-sb-item-separator"></li>
                @endcan


                @canany(['abouts_list', 'introductions_list', 'banners_list', 'systems_list'])
                <li class="lh-sb-title condense">Pages</li>
                <li class="lh-sb-item sb-drop-item">
                    <a href="javascript:void(0)" class="lh-drop-toggle">
                        <i class="ri-pages-fill"></i><span class="condense">Quản lý page<i
                                class="drop-arrow ri-arrow-down-s-line"></i></span></a>
                    <ul class="lh-sb-drop condense">
                        @can('abouts_list')
                        <li><a href="{{ route('admin.abouts.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Trang Về chúng tôi</a></li>
                        @endcan
                        @can('introductions_list')
                        <li><a href="{{ route('admin.introductions.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Trang Giới thiệu</a></li>
                        @endcan
                        @can('banners_list')
                        <li><a href="{{ route('admin.banners.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Quản lý banner</a></li>
                        @endcan
                        @can('systems_list')
                        <li><a href="{{ route('admin.systems.index') }}" class="lh-page-link drop"><i
                                    class="ri-git-commit-line"></i>Quản lý system </a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                <!-- <li class="lh-sb-title condense">Settings</li> -->
                
            </ul>
        </div>
    </div>
</div>