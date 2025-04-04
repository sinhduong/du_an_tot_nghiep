<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $fromEmail;

    public function __construct($data, $fromEmail)
    {
        $this->data = $data;
        $this->fromEmail = $fromEmail;
    }

    public function build()
    {
        return $this->from($this->fromEmail, $this->data['name']) // Từ email và tên người gửi
                    ->subject('Thông Báo Liên Hệ Mới: ' . $this->data['subject']) // Chủ đề email
                    ->view('emails.contact_success') // Sửa thành tên template đúng
                    ->with(['data' => $this->data]); // Truyền dữ liệu vào view
    }
}
