<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotesRejectionMail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $creator;
    public $quoteID;
    public $rejectionReason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HRPerson $creator, $quoteID, $rejectionReason)
    {
        $this->creator = $creator;
        $this->quoteID = $quoteID;
        $this->rejectionReason = $rejectionReason;
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
        $subject = "$companyName - Quote Declined.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['quote_url'] = url("/quote/view/$this->quoteID/01");

        return $this->view('mails.declinee_quote')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
