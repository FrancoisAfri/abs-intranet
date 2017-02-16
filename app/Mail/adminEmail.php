<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class AdminEmail extends Mailable
{
    use Queueable, SerializesModels;
	public $firstname;
	public $senderName;
	public $message_to_send;
	public $senderEmail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $senderName, $message_to_send, $senderEmail)
    {
        //
		$this->firstname = $firstname;
		$this->senderName = $senderName;
		$this->message_to_send = $message_to_send;
		$this->senderEmail = $senderEmail;
		//$this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'New Client Request on NU-LAXMI LEASING online system.';
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'NU-LAXMI LEASING';
        $data['company_logo'] = 'http://devloans.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');
        return $this->view('mails.admin_email')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}