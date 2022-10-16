<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendManagersListOfAbsentUsers extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $first_name;
    public $leaveAttachment;
    public $date;

    public function __construct($first_name, $Attachment , $date)
    {
        $this->first_name = $first_name;
        $this->Attachment = $Attachment;
        $this->date = $date;
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
        $subject = "List of Absent Employees   for $companyName online system.";

        $data['date'] = $this->date;
        $data['attachment'] = $this->Attachment;
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') .$companyDetails['company_logo_url'];

        return $this->view('mails.list_of_absent_users')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
//            ->attachData($this->leaveAttachment, 'Absent Users.pdf', [
//                'mime' => 'application/pdf',
//            ])
            ->attachData($this->Attachment->string("xls"), 'Absent Users.xls')
            ->with($data);
    }
}
