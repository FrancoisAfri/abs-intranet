<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\HRPerson;
use App\CompanyIdentity;
class StockRequestApproved extends Mailable
{
    use Queueable, SerializesModels;
	public $first_name;
	public $request_no;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name,$request_no)
    {
        $this->first_name = $first_name;
        $this->request_no = $request_no;
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
        $subject = "Stock Request Approved on $companyName online system.";

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['dashboard_url'] = url('/');
        $data['stock_url'] = url("/stock/request_items");

        return $this->view('mails.approved_stock_request')
            ->from($companyDetails['mailing_address'], $companyDetails['mailing_name'])
            ->subject($subject)
            ->with($data);
    }
}
