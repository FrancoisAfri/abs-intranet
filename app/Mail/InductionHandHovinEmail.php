<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
use Illuminate\Support\Facades\Storage;

class InductionHandHovinEmail extends Mailable
{
    use Queueable, SerializesModels;
	
	public $person;
	public $inductionTitle;
	public $urls = '/';
    /**
     * Create a new message instance.
     *
     * @return void
     */
	public function __construct(HRPerson $person, $inductionTitle)
	{
		$this->person = $person;
		$this->inductionTitle = $inductionTitle;
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

		//Should get these details from setup
        $subject = "Induction Sign Off on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['full_company_name'] ;
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.inductionHandHovin')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}