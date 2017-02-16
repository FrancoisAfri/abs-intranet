<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RejectedLoanApplication extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $rejection_reason;
    public $loan_url = 'http://devloans.afrixcel.co.za/loan/edit/';

    public function __construct(User $user, $rejection_reason, $loan_id)
    {
        $this->user = $user->load('person');
        $this->rejection_reason = $rejection_reason;
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
        $subject = 'Correction Needed to Your Loan Application | NU-LAXMI LEASING';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'NU-LAXMI LEASING';
        $data['company_logo'] = 'http://devloans.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');

        return $this->view('mails.rejected_loan_application')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}
