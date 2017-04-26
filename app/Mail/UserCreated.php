<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'support@afrixcel.co.za';
        $name = 'Afrixcel Support';
        $subject = 'Afrixcel Test Email';

        return $this->view('mails.default_mail')
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }

    //Passing data to email niew
    //public $variableName = 'Some Text';
}
