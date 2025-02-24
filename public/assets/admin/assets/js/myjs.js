// xử lý select ở staffs
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Chọn phòng quản lý",
            allowClear: true,
            closeOnSelect: false // Cho phép chọn nhiều phòng
        });
    });

