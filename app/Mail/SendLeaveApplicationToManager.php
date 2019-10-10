<?php

namespace App\Mail;
use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendLeaveApplicationToManager extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $first_name;
    public $leaveAttachment;
	
    public function __construct($first_name, $leaveAttachment)
    {
        $this->first_name = $first_name;
        $this->leaveAttachment = $leaveAttachment;
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
        $subject = "Leave Application Approved on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') .$companyDetails['company_logo_url'];

        return $this->view('mails.approved_leave_app')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
			->attachData($this->leaveAttachment, 'Leave Application.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
