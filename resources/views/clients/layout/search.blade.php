<section class="section-search-control">
    <div class="container">
        <form action="{{ route('home') }}" method="GET" id="search-form">
            <div class="search-control-boxing">
                <!-- Date Range Picker -->
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Nhận phòng - Trả phòng
                        </h4>
                        <div class="calendar">
                            <i class="ri-calendar-2-line"></i>
                            <input type="text" id="date_range" class="lh-book-form-control" placeholder="Chọn ngày" value="{{ isset($formattedDateRange) ? $formattedDateRange : '' }}" required>
                            <!-- Hidden inputs to store check-in and check-out dates -->
                            <input type="hidden" name="check_in" id="check_in" value="{{ request()->check_in ?? \Carbon\Carbon::today()->format('Y-m-d') }}">
                            <input type="hidden" name="check_out" id="check_out" value="{{ request()->check_out ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Counter Dropdown -->
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Chọn người ở - số phòng
                        </h4>
                        <div class="counter-dropdown">
                            <i class="ri-user-line"></i>
                            <input type="text" id="counter_summary" class="lh-book-form-control" value="{{ (request()->total_guests ?? 2) }} người lớn - {{ (request()->children_count ?? 0) }} trẻ em - {{ (request()->room_count ?? 1) }} phòng" readonly>
                            <div class="counter-dropdown-content">
                                <!-- Adults -->
                                <div class="counter-item">
                                    <label>Người lớn</label>
                                    <div class="counter-controls">
                                        <button type="button" class="counter-btn minus" data-target="total_guests">-</button>
                                        <input type="text" name="total_guests" class="counter-input" value="{{ request()->total_guests ?? 2 }}" readonly>
                                        <button type="button" class="counter-btn plus" data-target="total_guests">+</button>
                                    </div>
                                </div>
                                <!-- Children -->
                                <div class="counter-item">
                                    <label>Trẻ em</label>
                                    <div class="counter-controls">
                                        <button type="button" class="counter-btn minus" data-target="children_count">-</button>
                                        <input type="text" name="children_count" class="counter-input" value="{{ request()->children_count ?? 0 }}" readonly>
                                        <button type="button" class="counter-btn plus" data-target="children_count">+</button>
                                    </div>
                                </div>
                                <!-- Rooms -->
                                <div class="counter-item">
                                    <label>Phòng</label>
                                    <div class="counter-controls">
                                        <button type="button" class="counter-btn minus" data-target="room_count">-</button>
                                        <input type="text" name="room_count" class="counter-input" value="{{ request()->room_count ?? 1 }}" readonly>
                                        <button type="button" class="counter-btn plus" data-target="room_count">+</button>
                                    </div>
                                </div>
                                <small class="note">
                                    <a href="#">Đọc thêm về chính sách đặt vé và lịch cùng với trẻ em</a>
                                </small>
                                <!-- Done Button -->
                                <button type="button" class="done-btn">Xong</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="lh-col-check">
                    <div class="search-control-button">
                        <button type="submit" class="lh-buttons">
                            Tìm
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Custom CSS for Search Form -->
<style>
    .lh-book-form-control {
        width: 100%;
        padding: 10px 10px 10px 35px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
        cursor: pointer;
    }

    .counter-dropdown {
        position: relative;
    }

    .counter-dropdown i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }

    .counter-dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1000;
        width: 100%;
    }

    .counter-dropdown-content.show {
        display: block;
    }

    .counter-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .counter-item label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    .counter-controls {
        display: flex;
        align-items: center;
    }

    .counter-btn {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #f5f5f5;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        color: #666;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .counter-btn:hover {
        background: #e0e0e0;
    }

    .counter-input {
        width: 40px;
        text-align: center;
        border: 1px solid #ddd;
        margin: 0 5px;
        padding: 5px;
        font-size: 14px;
        border-radius: 5px;
        background: #fff;
    }

    .note {
        display: block;
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    .note a {
        color: #007bff;
        text-decoration: none;
    }

    .note a:hover {
        text-decoration: underline;
    }

    .done-btn {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: block;
        margin: 10px 0 0 auto;
        transition: background 0.3s;
    }
</style>

<!-- JavaScript for Date Range Picker and Counter -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hàm định dạng ngày sang tiếng Việt
        function formatDateToVietnamese(startDate, endDate) {
            const days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            const months = [
                'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6',
                'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12'
            ];
            const startDay = days[startDate.getDay()];
            const startDateNum = startDate.getDate();
            const startMonth = months[startDate.getMonth()];
            const endDay = days[endDate.getDay()];
            const endDateNum = endDate.getDate();
            const endMonth = months[endDate.getMonth()];
            return `${startDay}, ${startDateNum} ${startMonth} - ${endDay}, ${endDateNum} ${endMonth}`;
        }

        // Lấy ngày mặc định từ server
        const defaultCheckIn = new Date("{{ request()->check_in ?? \Carbon\Carbon::today()->format('Y-m-d') }}");
        const defaultCheckOut = new Date("{{ request()->check_out ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}");

        // Đặt giá trị ban đầu cho ô input
        const dateRangeInput = document.getElementById('date_range');
        if (!dateRangeInput.value) {
            dateRangeInput.value = formatDateToVietnamese(defaultCheckIn, defaultCheckOut);
        }

        // Initialize Flatpickr
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today", // Vô hiệu hóa các ngày trước ngày hiện tại
            defaultDate: [
                "{{ request()->check_in ?? \Carbon\Carbon::today()->format('Y-m-d') }}",
                "{{ request()->check_out ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
            ],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const startDate = new Date(selectedDates[0].getTime() - (selectedDates[0].getTimezoneOffset() * 60000));
                    const endDate = new Date(selectedDates[1].getTime() - (selectedDates[1].getTimezoneOffset() * 60000));
                    document.getElementById('check_in').value = startDate.toISOString().split('T')[0];
                    document.getElementById('check_out').value = endDate.toISOString().split('T')[0];
                    document.getElementById('date_range').value = formatDateToVietnamese(startDate, endDate);
                }
            },
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    longhand: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy']
                },
                months: {
                    shorthand: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                    longhand: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12']
                }
            },
            showMonths: 2
        });

        // Counter Dropdown Logic
        const counterSummary = document.getElementById('counter_summary');
        const counterDropdown = document.querySelector('.counter-dropdown-content');
        const doneBtn = document.querySelector('.done-btn');

        counterSummary.addEventListener('click', function() {
            counterDropdown.classList.toggle('show');
        });

        doneBtn.addEventListener('click', function() {
            counterDropdown.classList.remove('show');
            const totalGuests = document.querySelector('input[name="total_guests"]').value;
            const childrenCount = document.querySelector('input[name="children_count"]').value;
            const roomCount = document.querySelector('input[name="room_count"]').value;
            counterSummary.value = `${totalGuests} người lớn - ${childrenCount} trẻ em - ${roomCount} phòng`;
        });

        document.querySelectorAll('.counter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.querySelector(`input[name="${target}"]`);
                let value = parseInt(input.value);

                if (this.classList.contains('plus')) {
                    value++;
                } else if (this.classList.contains('minus') && value > (target === 'children_count' ? 0 : 1)) {
                    value--;
                }

                input.value = value;
            });
        });

        document.addEventListener('click', function(event) {
            if (!counterSummary.contains(event.target) && !counterDropdown.contains(event.target)) {
                counterDropdown.classList.remove('show');
            }
        });
    });
</script>
