<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveQuote extends Mailable
{
    use Queueable, SerializesModels;

    public $manager;
    public $quoteID;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HRPerson $manager, $quoteID)
    {
        $this->manager = $manager;
        $this->quoteID = $quoteID;
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
        $subject = "$companyName - Quote Approval Required.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['quote_url'] = url("/quote/view/$this->quoteID/01");

        return $this->view('mails.approve_quote')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
