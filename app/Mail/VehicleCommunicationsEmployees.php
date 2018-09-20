<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
use App\VehicleCommunications;
use App\User;
use Illuminate\Support\Facades\Storage;

class VehicleCommunicationsEmployees extends Mailable
{
    use Queueable, SerializesModels;
	public $employee;
	public $VehicleCommunications;
	public $email;
	public $urls = '/';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HRPerson $employee, VehicleCommunications $VehicleCommunications, $email)
    {
        $this->employee = $employee;
		$this->VehicleCommunications = $VehicleCommunications;
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
		$subject = "$companyName Communication.";

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyDetails['full_company_name'] ;
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

		return $this->view('mails.vehicle_communication_employees')
			->from(!empty($this->email) ? $this->email : $companyDetails['mailing_address'], $companyDetails['mailing_name'])
			->subject($subject)
			->with($data);
    }
}
