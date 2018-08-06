<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ContactPerson;
use App\CompanyIdentity;
use App\ContactsCommunication;
use Illuminate\Support\Facades\Storage;

class ClientCommunication extends Mailable
{
    use Queueable, SerializesModels;
	public $client;
	public $ContactsCommunication;
	public $user;
	public $urls = '/';
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactPerson $client, ContactsCommunication $ContactsCommunication, User $user)
	{
		$this->client = $client;
		$this->ContactsCommunication = $ContactsCommunication;
		$this->user = $user;
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
		$subject = "$companyName Client Communication.";

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyDetails['full_company_name'] ;
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

		return $this->view('mails.client_communication')
			->from($user->person->email, $companyDetails['mailing_name'])
			->subject($subject)
			->with($data);
	}
}