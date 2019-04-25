<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\EmployeeTasks;
use App\CompanyIdentity;

class DeclinejobstepNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $surname;
    public $email;
    public $reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstname, $surname, $email ,$reason )
    {
        $this->firstname = $firstname;
        $this->surname = $surname;
        $this->email = $email;
        $this->reason = $reason;
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
        $subject = "Task completed $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['jobcard_url'] = url("/jobcards/approval");


        return $this->view('mails.decline_jobcard')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
