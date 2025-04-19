<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thanh Toán Thành Công</title>
</head>
<body>
<h1>Xin chào {{ $booking->user->name }},</h1>
<p>Cảm ơn bạn đã đặt phòng tại khách sạn của chúng tôi!</p>

<h2>Thông tin đặt phòng:</h2>
<ul>
    <li>Mã đặt phòng: {{ $booking->booking_code }}</li>
    <li>Ngày nhận phòng: {{ $booking->check_in->format('d/m/Y H:i') }}</li>
    <li>Ngày trả phòng: {{ $booking->check_out->format('d/m/Y H:i') }}</li>
    <li>Số lượng phòng: {{ $booking->room_quantity }}</li>
    <li>Tổng tiền: {{ number_format($booking->total_price, 0, ',', '.') }} VND</li>
    <li>Tổng tiền đã thanh toán: {{ number_format($booking->paid_amount, 0, ',', '.') }} VND</li>
    <li>Số tiền còn lại: {{ number_format($booking->total_price - $booking->paid_amount, 0, ',', '.') }} VND</li>
    <li>Phương thức thanh toán: {{ $booking->payments->first()?->method ?? 'Không xác định' }}</li>
</ul>

@if($booking->status == 'partial' || $booking->status == 'paid')
    <p>Thanh toán qua {{ $booking->payments->first()?->method ?? 'cổng thanh toán' }} đã hoàn tất. Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
@endif

<p>Trân trọng,<br>Đội ngũ khách sạn</p>
</body>
</html>
