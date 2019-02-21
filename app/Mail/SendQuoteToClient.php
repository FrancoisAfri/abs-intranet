<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendQuoteToClient extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent;
    public $quoteAttachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($messageContent, $quoteAttachment, $email)
    {
        $this->messageContent = $messageContent;
        $this->quoteAttachment = $quoteAttachment;
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
        $subject = "$companyName - Quotation.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		
        return $this->view('mails.send_quote_to_client')
            ->from(!empty($this->email) ? $this->email : $companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->attachData($this->quoteAttachment, 'quotation.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
