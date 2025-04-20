<?php

namespace App\Mail;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSuccess extends Mailable
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
        Log::info('Booking data in PaymentSuccess: ' . json_encode($this->booking->toArray()));

        return $this->view('emails.payment_success')
            ->subject('Thanh Toán Thành Công');
    }
}
