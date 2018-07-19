<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\CRMInvoice;
use App\EmailTemplate;
use App\Mail\SendInvoiceToClient;
use App\Quotation;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CRMInvoiceController extends Controller
{
    /**
     * Show a specific invoice
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewInvoice(Quotation $quotation, $isPDF = false, $printQuote = false, $emailQuote = false, CRMInvoice $paramInvoice = null)
    {
        $isInvoice = true;
        $quotation->load('invoices', 'products.ProductPackages', 'packages.products_type');
        //once-off payment
        if ($quotation->payment_option == 1) {
            if (count($quotation->invoices) > 0) {
                //view invoice
                return app('App\Http\Controllers\QuotesController')->viewQuote($quotation, false,$isPDF, $printQuote, $emailQuote, $isInvoice);
            } else {
                //create invoice
                DB::transaction(function () use ($quotation) {
                    $invoice = new CRMInvoice();
                    $invoice->quotation_id = $quotation->id;
                    $invoice->company_id = $quotation->company_id;
                    $invoice->client_id = $quotation->client_id;
                    $invoice->account_id = $quotation->account_id;
                    $invoice->invoice_date = time();
                    $invoice->status = 1;

                    $productsSubtotal = 0;
                    $packagesSubtotal = 0;
                    foreach ($quotation->products as $product) {
                        $productsSubtotal += ($product->pivot->price * $product->pivot->quantity);
                    }
                    foreach ($quotation->packages as $package) {
                        $packagesSubtotal += ($package->pivot->price * $package->pivot->quantity);
                    }
                    $subtotal = $productsSubtotal + $packagesSubtotal;
                    $discountPercent = $quotation->discount_percent;
                    $discountAmount = ($discountPercent > 0) ? ($subtotal * $discountPercent) / 100 : 0;
                    $discountedAmount = $subtotal - $discountAmount;
                    $vatAmount = ($quotation->add_vat == 1) ? $discountedAmount * 0.14 : 0;
                    $total = $discountedAmount + $vatAmount;

                    $invoice->amount = $total;
                    $invoice->save();

                    $companyDetails = CompanyIdentity::systemSettings();
                    $invoiceNumber = $companyDetails['header_acronym_bold'] . $companyDetails['header_acronym_regular'];
                    $invoiceNumber = !empty($invoiceNumber) ? strtoupper($invoiceNumber) : 'SYS';
                    $invoiceNumber .= 'INV' . sprintf('%07d', $invoice->id);
                    $invoice->invoice_number = $invoiceNumber;
                    $invoice->update();
                });

                //view invoice
                return app('App\Http\Controllers\QuotesController')->viewQuote($quotation,false, $isPDF, $printQuote, $emailQuote, $isInvoice);
            }
        } elseif ($quotation->payment_option == 2) { //monthly payment
            //view monthly invoice
            return app('App\Http\Controllers\QuotesController')->viewQuote($quotation, false,$isPDF, $printQuote, $emailQuote, $isInvoice, $paramInvoice);
        }
    }

    /**
     * Show the invoice PDF view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewPDFInvoice(Quotation $quotation)
    {
        return $this->viewInvoice($quotation, true, true);
    }

    /**
     * Show the monthly invoice PDF view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewPDFMonthlyInvoice(Quotation $quotation, CRMInvoice $invoice)
    {
        return $this->viewInvoice($quotation, true, true, false, $invoice);
    }

    /**
     * Email the invoice to the client
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function emailInvoice(Quotation $quotation)
    {
        $quotation->load('client');

        $messageContent = EmailTemplate::where('template_key', 'send_invoice')->first();
        $messageContent = ($messageContent) ? $messageContent->template_content : '';
        $messageContent = str_replace('[client name]', $quotation->client->full_name, $messageContent);
        $invoiceAttachment = $this->viewInvoice($quotation, true, false, true);
        Mail::to($quotation->client->email)->send(new SendInvoiceToClient($messageContent, $invoiceAttachment));

        return back()->with(['invoice_emailed' => 'The Invoice has been successfully emailed to the client!']);
    }

    /**
     * Email the monthly invoice to the client
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function emailMonthlyInvoice(Quotation $quotation, CRMInvoice $invoice)
    {
        $quotation->load('client');

        $messageContent = EmailTemplate::where('template_key', 'send_invoice')->first();
        $messageContent = ($messageContent) ? $messageContent->template_content : '';
        $messageContent = str_replace('[client name]', $quotation->client->full_name, $messageContent);
        $invoiceAttachment = $this->viewInvoice($quotation, true, false, true, $invoice);
        Mail::to($quotation->client->email)->send(new SendInvoiceToClient($messageContent, $invoiceAttachment));

        return back()->with(['invoice_emailed' => 'The Invoice has been successfully emailed to the client!']);
    }
}
