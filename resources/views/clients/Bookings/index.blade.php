@extends('layouts.client')

@section('content')
    <section class="section-banner">
        <div class="row banner-image">
            <div class="banner-overlay"></div>
            <div class="banner-section">
                <div class="lh-banner-contain">
                    <h2>{{ $title }}</h2>
                    <div class="lh-breadcrumb">
                        <h5>
                            <span class="lh-inner-breadcrumb">
                                <a href="{{ route('home') }}">Trang chủ</a>
                            </span>
                            <span> / </span>
                            <span>
                                <a href="javascript:void(0)">Danh sách đặt phòng</a>
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="booking-section py-5" style="background-color: #fff; position: relative;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="booking-header mb-4">
                        <p class="text-muted">Đã đặt</p>
                    </div>

                    <!-- Danh sách đặt phòng -->
                    <div class="room-list">
                        @forelse ($bookings as $booking)
                            <a href="{{ route('bookings.show', $booking->id) }}" class="room-item-link text-decoration-none">
                                <div class="room-item card shadow-sm mb-4 position-relative" style="border: 1px solid #e0e0e0; border-radius: 12px; overflow: visible; transition: all 0.3s ease; position: relative;">
                                    <div class="card-body d-flex align-items-center p-3">
                                        <div class="room-image" style="flex: 0 0 130px;">
                                            @if ($booking->rooms->isNotEmpty() && $booking->rooms->first()->roomType && $booking->rooms->first()->roomType->roomTypeImages->isNotEmpty())
                                                @php
                                                    $mainImage = $booking->rooms->first()->roomType->roomTypeImages->where('is_main', true)->first();
                                                    $imageUrl = $mainImage ?
                                                    Storage::url($mainImage->image) : 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/249146036.jpg';
                                                @endphp
                                                <img src="{{ $imageUrl }}" alt="Room Image" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px 0 0 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                            @else
                                                <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/249146036.jpg" alt="Room Image" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px 0 0 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                            @endif
                                        </div>
                                        <div class="room-details flex-grow-1 px-4">
                                            <h5 class="mb-1 text-dark">{{ $booking->rooms->first()->roomType->name ?? 'Chưa có loại phòng' }}</h5>
                                            <p class="mb-1 text-muted">{{ \App\Helpers\FormatHelper::formatDateVI($booking->check_in) }} – {{ \App\Helpers\FormatHelper::formatDateVI($booking->check_out) }}</p>
                                            <span class="status-badge {{ \App\Helpers\BookingStatusHelper::getStatusClass($booking->status) }}">
                                                {{ \App\Helpers\BookingStatusHelper::getStatusLabel($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="room-price d-flex align-items-center pe-3 position-relative" style="min-width: 180px; color: #555;">
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-5 text-dark" style="font-size: 22px;">VND {{ number_format($booking->total_price, 0, ',', '.') }}</h4>
                                                <span class="vertical-dots ms-4 mb-5" style="color: #555; font-size: 30px; font-weight: 800; cursor: pointer; transition: color 0.3s ease;" data-booking-id="{{ $booking->id }}">⋮</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Dropdown menu bên trong card -->
                                   {{-- <div class="dropdown-menu-custom"
                                        style="display: none; position: absolute; top: 40px; right: 20px; background: #fff; border: 2px solid #ddd; border-radius: 8px; box-shadow: 0 6px 12px rgba(0,0,0,0.15); z-index: 1000; padding: 5px 0; min-width: 150px;">
                                       <a href="javascript:void(0)"
                                          class="dropdown-item delete-booking text-danger"
                                          data-id="{{ $booking->id }}"
                                          style="display: block; padding: 5px 20px; color: #dc3545; text-decoration: none; transition: background-color 0.3s ease;">
                                           Xóa đơn đặt
                                       </a>
                                   </div> --}}
                                </div>
                            </a>
                        @empty
                            <p class="text-center">Bạn chưa có đơn đặt phòng nào.</p>
                        @endforelse
                    </div>
                    {{-- {{ $bookings->links() }} --}}
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript để xử lý dropdown và điều chỉnh vị trí -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dots = document.querySelectorAll('.vertical-dots');
            const dropdowns = document.querySelectorAll('.dropdown-menu-custom');
            const roomItems = document.querySelectorAll('.room-item');

            // Prevent dropdown clicks from triggering the link
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            // Đóng tất cả dropdown khi nhấp ra ngoài
            document.addEventListener('click', function (e) {
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(e.target) && !e.target.classList.contains('vertical-dots')) {
                        dropdown.style.display = 'none';
                    }
                });
            });

            // Xử lý sự kiện nhấp vào nút ba chấm
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const dropdown = dropdowns[index];
                    const roomItem = roomItems[index];
                    const rect = dot.getBoundingClientRect();
                    const roomRect = roomItem.getBoundingClientRect();

                    const spaceBelow = window.innerHeight - rect.bottom;
                    const dropdownHeight = 80;

                    const isOpen = dropdown.style.display === 'block';
                    dropdowns.forEach(d => d.style.display = 'none');

                    if (!isOpen) {
                        dropdown.style.top = '40px';
                        dropdown.style.right = '20px';
                        dropdown.style.position = 'absolute';
                        dropdown.style.display = 'block';

                        if (spaceBelow < dropdownHeight && rect.top > dropdownHeight) {
                            dropdown.style.top = (-dropdownHeight - 5) + 'px';
                        }

                        dot.style.color = '#007bff';
                    } else {
                        dropdown.style.display = 'none';
                        dot.style.color = '#555';
                    }
                });
            });

            // Xử lý nút "Xóa đơn đặt" với AJAX
            document.querySelectorAll('.delete-booking').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const bookingId = this.getAttribute('data-id');
                    const roomItem = this.closest('.room-item');
                    if (confirm('Bạn có chắc muốn xóa đơn đặt này?')) {
                        fetch(`/bookings/${bookingId}`, {  // Sửa URL để khớp với route
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert(data.message);
                                    roomItem.style.opacity = '0';
                                    setTimeout(() => {
                                        roomItem.style.display = 'none';
                                    }, 300);
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Có lỗi xảy ra khi xóa đơn đặt!');
                            });
                    }
                });
            });
        });
    </script>

    <style>
        .room-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .dropdown-item:hover {
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .booking-section {
            position: relative;
            z-index: 1;
        }
        .dropdown-menu-custom {
            max-height: 100px;
            overflow-y: auto;
        }
    </style>
@endsection
