<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
class MechanicEmailJC extends Mailable
{
    use Queueable, SerializesModels;
	public $first_name;
    public $jcAttachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $jcAttachment)
    {
        $this->first_name = $first_name;
        $this->jcAttachment = $jcAttachment;
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
        $subject = "Job Card Notification on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url('/');
        $data['jobcard_url'] = url("/jobcards/approval");

        return $this->view('mails.send_jobcard_mechanic')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
			->attachData($this->jcAttachment, 'JobCard.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
