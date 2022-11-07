<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class managerReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $employee;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $employee)
    {
        $this->name = $name;
        $this->employee = $employee;
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
        $subject = "Leave Approval Reminder";

        $data['support_email'] = $companyDetails['support_email'];
        $data['fullname'] = $this->name;
        $data['employee'] = $this->employee;
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['profile_url'] = url('/users/profile');
        $data['dashboard_url'] = url('/leave/approval');

        return $this->view('mails.remind_manager')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
