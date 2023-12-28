<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PolicyRefresh extends Mailable
{
    use Queueable, SerializesModels;
	
	public $first_name;
    public $policy;
    public $policyID;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $policy, $policyID)
    {
        $this->first_name = $first_name;
        $this->surname = $policy;
        $this->policyID = $policyID;
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
        $subject = "Policy refresh $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['mypolicies_url'] = url("/policy/read-policy-document/$this->policyID");
        $data['dashboard_url'] = url('/');

        return $this->view('mails.refresh_policy')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
