<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendManagersListOfAssets extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $request_no;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $employee_number , $date_left ,$Attachment)
    {
        $this->first_name = $first_name;
        $this->employee_number  = $employee_number;
        $this->date_left  = $date_left;
        $this->Attachment  = $Attachment;
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
        $subject = "$companyName - Quotation.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['first_name'] = $this->first_name;
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.send_list_to_manager')
            ->from(!empty($this->email) ? $this->email : $companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->attachData($this->Attachment, 'listAssetsAndSubscriptions.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
