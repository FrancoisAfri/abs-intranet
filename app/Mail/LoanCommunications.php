<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class LoanCommunications extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $first_name;
    public $loanId;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $type, $loanId)
    {
        $this->first_name = $first_name;
        $this->type = $type;
        $this->loanId = $loanId;
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
        $subject = "New Loan Application on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url("/loan/view-ind/$this->loanId");

        return $this->view('mails.loan_communication')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
