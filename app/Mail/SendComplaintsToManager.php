<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendComplaintsToManager extends Mailable
{
    use Queueable, SerializesModels;
	
	public $first_name;
    public $text;
    public $employee_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $text, $employee_name)
    {
        $this->first_name = $first_name;
        $this->text = $text;
        $this->employee_name = $employee_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$text = $this->text;
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $subject = "New $text on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url('/complaints/queue');

        return $this->view('mails.complaints_manager')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}