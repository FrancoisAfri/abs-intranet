<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class Accept_application extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $first_name;
    public $surname;
    public $email;

    public function __construct($first_name, $surname ,$email)
    {
        $this->first_name = $first_name;
        $this->surname = $surname;
        $this->email = $email;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Should get these details from setup
        $fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'leave Application Approved | Afrixcel Business Solutions';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Afrixcel Business Solutions';
        $data['company_logo'] = 'http://www.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');

        return $this->view('mails.approved_leave_application')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}
