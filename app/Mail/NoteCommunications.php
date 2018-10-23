<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoteCommunications extends Mailable
{
    use Queueable, SerializesModels;
	
	public $first_name;
    public $note;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $note)
    {
        $this->first_name = $first_name;
        $this->note = $note;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $subject = "$companyName - JC Note.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.note_communciations')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
