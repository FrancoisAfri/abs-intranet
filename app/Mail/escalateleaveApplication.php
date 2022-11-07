<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class escalateleaveApplication extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $employee;
    public $oldmanager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $employee, $oldmanager)
    {
        $this->name = $name;
        $this->employee = $employee;
        $this->oldmanager = $oldmanager;


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
        $subject = "Escalation email";

        $data['support_email'] = $companyDetails['support_email'];
        $data['name'] = $this->name;
        $data['employee'] = $this->employee;
        $data['oldmanager'] = $this->oldmanager;
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['profile_url'] = url('/users/profile');
        $data['dashboard_url'] = url('/leave/approval');

        return $this->view('mails.escalate_leave_applications')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
