<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CloseComplaints extends Mailable
{
    use Queueable, SerializesModels;
	public $first_name;
    public $complaintID;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $complaintID)
    {
        $this->first_name = $first_name;
		$this->complaintID = $complaintID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $complaintID = $this->complaintID;
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $subject = "Complaint Closed on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url("/complaints/view/$complaintID");

        return $this->view('mails.close_complaints')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
