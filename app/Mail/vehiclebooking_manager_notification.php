<?php

namespace App\Mail;

use App\CompanyIdentity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class vehiclebooking_manager_notification extends Mailable
{
   use Queueable, SerializesModels;

    public $first_name;
    public $surname;
    public $email;
    public $required_from;
    public $required_to;
    public $Usage_type;
    public $driver;
    public $destination;
    public $purpose;
    public $vehicle_model;
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($first_name, $surname, $email, $required_from , $required_to ,$Usage_type ,
        $driver ,$destination , $purpose , $vehicle_model, $comment)

    {
        $this->first_name = $first_name;
        $this->surname = $surname;
        $this->email = $email;
        $this->required_from = $required_from;
        $this->required_to = $required_to;
        $this->Usage_type = $Usage_type;
        $this->driver = $driver;
        $this->destination = $destination;
        $this->purpose = $purpose;
        $this->vehicle_model = $vehicle_model;
        $this->comment = $comment;
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
        $subject = "New vehicle booking Applicationon $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url('/');

        return $this->view('mails.manager_notification')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
