<?php

namespace App\Mail;
use App\CompanyIdentity;
use App\User;
use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class business_cardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $first_name;
    public $surname;
    public $email;

    public function __construct($first_name, $surname ,$email)
    {
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

        //Should get these details from setup
        $subject = "Business Card $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['full_company_name'] ;
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.user_businessCard.blade')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
