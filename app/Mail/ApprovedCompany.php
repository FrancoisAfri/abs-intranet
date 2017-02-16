<?php

namespace App\Mail;

use App\contacts_company;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ApprovedCompany extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $company;
    public $company_url = '/contacts/company/';
    
    public function __construct(User $user, contacts_company $company)
    {
        $this->user = $user->load('person');
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
        $subject = ($this->company->status === 2) ? 'Final Approval Needed | Osizweni Education & Development Centre' : $strCompType . ' Approved | Osizweni Education & Development Centre';

        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Osizweni Education & Development Centre';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');

        $data['company_type'] = $strCompType;

        if ($this->company->status === 2) {
            return $this->view('mails.company_gm_approval')
                ->from($fromAddress, $fromName)
                ->subject($subject)
                ->with($data);
        }
        elseif ($this->company->status === 3) {
            return $this->view('mails.approved_company')
                ->from($fromAddress, $fromName)
                ->subject($subject)
                ->with($data);
        }
    }
}
