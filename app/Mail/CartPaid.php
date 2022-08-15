<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Api\Core;
use App\Api\Email;

class CartPaid extends Mailable
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
            ->subject('Xác Nhận Thanh Toán')
            ->view('mails.cart_paid')
            ->text('mails.cart_paid_text')
            ;
    }
}
