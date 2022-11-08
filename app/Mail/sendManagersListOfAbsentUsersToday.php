<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendManagersListOfAbsentUsersToday extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $first_name;
    public $leaveAttachment;

    public function __construct($first_name, $leaveAttachment ,$Attachment )
    {
        $this->first_name = $first_name;
        $this->leaveAttachment = $leaveAttachment;
        $this->Attachment = $Attachment;
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
        $subject = "Monthly Leave Balance Report for $companyName online system.";

        $data['attachment'] = $this->leaveAttachment;
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') .$companyDetails['company_logo_url'];

        return $this->view('mails.leave_balance_report')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->attachData($this->Attachment, 'Leave balance.xls')
            ->attachData($this->leaveAttachment, 'Leave Report.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
