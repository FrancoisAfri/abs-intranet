<?php

namespace App\Mail;

// use App\contacts_company;
use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class leave_applications extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $surname;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $user;
   
    // public $company_url = '/contacts/company/';

    public function __construct($first_name, $surname, $email)
    {
        // $this->user = $user->load('person');
        $this->first_name = $first_name;
        $this->surname = $surname;
        $this->email = $email;
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
        $subject = "New Leave Application on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['profile_url'] = url('/users/profile');

        return $this->view('mails.leave_application')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
