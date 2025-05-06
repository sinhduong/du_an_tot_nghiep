// xử lý select ở staffs
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Chọn nhiều giá trị",
            allowClear: true,
            closeOnSelect: false // Cho phép chọn nhiều phòng
        });
    });
    //  Thêm CKEditor cho trường notes
