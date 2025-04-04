<?php

namespace App\Mail;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingSuccess extends Mailable
{
    use Queueable,
        SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        // Log dữ liệu trong class mail để kiểm tra
        Log::info('Booking data in BookingSuccess: ' . json_encode($this->booking->toArray()));

        return $this->view('emails.booking_success')
            ->subject('Đặt Phòng Thành Công');
    }
}
