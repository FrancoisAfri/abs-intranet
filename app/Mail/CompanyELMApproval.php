<?php

namespace App\Mail;

use App\contacts_company;
use App\HRPerson;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CompanyELMApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $person;
    public $company_url = '/contacts/company/';
    public $company;

    public function __construct(HRPerson $person, contacts_company $company)
    {
        $this->person = $person;
        $this->company = $company;
        $this->company_url .= $company->id.'/view';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $companyTypes = [1 => 'Service Provider', 2 => 'School', 3 => 'Sponsor'];
        $strCompType = $companyTypes[$this->company->company_type];

        //Should get these details from setup
        $fromAddress = 'noreply@afrixcel.co.za';
        $fromName = 'Afrixcel Support';
        $subject = 'Approval Needed | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        $data['company_type'] = $strCompType;

        return $this->view('mails.company_elm_approval')
            ->from($fromAddress, $fromName)
            ->subject($subject)
            ->with($data);
    }
}
