<!DOCTYPE html>
<html>
<head>
    <title>Thông Báo Liên Hệ Mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #2c3e50;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thông Báo Liên Hệ Mới</h1>
        <p><span class="label">Họ và tên:</span> {{ $data['name'] }}</p>
        <p><span class="label">Email:</span> {{ $data['email'] }}</p>
        @if(!empty($data['phone']))
            <p><span class="label">Số điện thoại:</span> {{ $data['phone'] }}</p>
        @endif
        <p><span class="label">Chủ đề:</span> {{ $data['subject'] }}</p>
        <p><span class="label">Nội dung tin nhắn:</span></p>
        <p>{{ $data['message'] }}</p>
    </div>
</body>
</html>
