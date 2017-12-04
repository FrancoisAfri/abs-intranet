<?php

namespace App\Mail;

use App\CompanyIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVoucher extends Mailable
{
    use Queueable, SerializesModels;

    public $voucherAttachment;
    public $clientName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($clientName, $voucherAttachment)
    {
        $this->voucherAttachment = $voucherAttachment;
        $this->clientName = $clientName;
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
        $subject = "$companyName - Client Voucher.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        return $this->view('mails.send_voucher')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->attachData($this->voucherAttachment, 'voucher.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with($data);
    }
}
