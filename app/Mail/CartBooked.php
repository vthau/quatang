<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Api\Core;
use App\Api\Email;

class CartBooked extends Mailable
{
    use Queueable, SerializesModels;

    public $obj;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($obj)
    {
        $this->obj = $obj;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $apiMail = new Email();
        $from = $apiMail->getContact();

        return $this->from($from)
            ->subject('Đặt Hàng Thành Công')
            ->view('mails.cart_booked')
            ->text('mails.cart_booked_text')
            ;
    }
}
