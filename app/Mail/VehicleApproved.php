<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VehicleApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	 public $firstname;
	 public $surname;
	 public $email;
	 public $iID;
	 
    public function __construct($firstname, $surname , $email, $iID)
    {
        $this->firstname = $firstname;
		$this->surname = $surname;
		$this->email = $email;
		$this->iID = $iID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyDetails = CompanyIdentity::systemSettings();

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['dashboard_url'] = url('/');
		$data['fleetapprove_url'] = url("/vehicle_management/viewdetails/$this->iID");
		////
        $subject = "Vehicle Approved $companyDetails[company_name] online system.";        
        return $this->view('mails.new_vehicle_approved')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
