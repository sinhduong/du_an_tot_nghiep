<section class="section-search-control">
    <div class="container">
        <form action="{{ route('home') }}" method="GET" id="search-form">
            <div class="search-control-boxing">
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Nhận phòng
                        </h4>
                        <div class="calendar" id="date_1">
                            <i class="ri-calendar-2-line"></i>
                            <input type="text" name="check_in" placeholder="Ngày đến" class="lh-book-form-control datepicker" value="{{ request()->check_in ?? \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Trả phòng
                        </h4>
                        <div class="calendar" id="date_2">
                            <i class="ri-calendar-2-line"></i>
                            <input type="text" name="check_out" placeholder="Ngày đi" class="lh-book-form-control datepicker" value="{{ request()->check_out ?? \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Người lớn
                        </h4>
                        <div class="custom-select">
                            <select name="total_guests" required>
                                <option value="" disabled {{ !request()->total_guests ? 'selected' : '' }}>Chọn</option>
                                <option value="1" {{ request()->total_guests == '1' ? 'selected' : '' }}>Một</option>
                                <option value="2" {{ request()->total_guests == '2' ? 'selected' : '' }}>Hai</option>
                                <option value="3" {{ request()->total_guests == '3' ? 'selected' : '' }}>Ba</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="lh-col">
                    <div class="search-box">
                        <h4 class="heading">
                            Trẻ em
                        </h4>
                        <div class="custom-select">
                            <select name="children_count" required>
                                <option value="" disabled {{ !request()->children_count ? 'selected' : '' }}>Chọn</option>
                                <option value="0" {{ request()->children_count == '0' ? 'selected' : '' }}>Không</option>
                                <option value="1" {{ request()->children_count == '1' ? 'selected' : '' }}>Một</option>
                                <option value="2" {{ request()->children_count == '2' ? 'selected' : '' }}>Hai</option>
                                <option value="3" {{ request()->children_count == '3' ? 'selected' : '' }}>Ba</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="lh-col-check">
                    <div class="search-control-button">
                        <button type="submit" class="lh-buttons result-placeholder">
                            Tìm
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Thêm jQuery và jQuery UI để hỗ trợ datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        // Khởi tạo datepicker cho check-in
        $("#date_1 .datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0, // Không cho phép chọn ngày trước hôm nay
            onSelect: function (selectedDate) {
                // Cập nhật ngày tối thiểu cho check-out
                $("#date_2 .datepicker").datepicker("option", "minDate", selectedDate);
            }
        });

        // Khởi tạo datepicker cho check-out
        $("#date_2 .datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 1, // Ngày trả phòng phải sau ngày nhận phòng
        });
    });
</script>
