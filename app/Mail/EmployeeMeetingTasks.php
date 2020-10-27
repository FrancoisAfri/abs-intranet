<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
use Illuminate\Support\Facades\Storage;

class EmployeeMeetingTasks extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	 
	public $person;
	public $task;
	public $sender;
	public $urls = '/';
	
    public function __construct(HRPerson $person, $task, HRPerson $sender)
    {
        $this->person = $person;
        $this->task = $task;
        $this->sender = $sender;
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
        $subject = "New Task on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['full_company_name'] ;
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.employeeTasksMeetings')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
