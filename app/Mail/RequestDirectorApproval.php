<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RequestDirectorApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $loan_url = 'http://devloans.afrixcel.co.za/loan/view/';
    
    public function __construct(User $user, $loan_id)
    {
        $this->user = $user->load('person');
        $this->loan_url .= $loan_id.'';
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
        $subject = 'Approval Needed | NU-LAXMI LEASING';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'NU-LAXMI LEASING';
        $data['company_logo'] = 'http://devloans.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');

        return $this->view('mails.request_director_approval')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}
