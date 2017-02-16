<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class loanMail extends Mailable
{
    use Queueable, SerializesModels;
	public $firstname;
	public $loanID;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $loanID)
    {
        //
		$this->firstname = $firstname;
		$this->loanID = $loanID;
		
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
        $subject = 'New Loan application on NU-LAXMI LEASING online system.';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'NU-LAXMI LEASING';
        $data['company_logo'] = 'http://devloans.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');
        $data['loan_url'] = 'http://devloans.afrixcel.co.za/loan/view/'.$this->loanID.'';

        return $this->view('mails.loan_application')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}
