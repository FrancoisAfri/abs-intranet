<?php

namespace App\Http\Controllers;

use App\CRMAccount;
use App\CRMInvoice;
use App\CRMPayment;
use App\Quotation;
use App\DivisionLevel;
use App\ContactCompany;
use App\ContactPerson;
use App\product_products;
use App\product_packages;
use App\QuotesTermAndConditions;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class CRMAccountController extends Controller
{
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * View a specific account.
     *
     * @param $account
     * @return  \Illuminate\View\View
     */
    public function viewAccount(CRMAccount $account)
    {
        $account->load('company', 'client', 'quotations.products.ProductPackages', 'quotations.packages.products_type', 'quotations.invoices.payments', 'quotations.services');

        $purchaseStatus = ['' => '', 5 => 'Client Waiting Invoice', 6 => 'Invoice Sent', 7 => 'Partially Paid', 8 => 'Paid'];
        $labelColors = ['' => 'danger', 5 => 'warning', 6 => 'primary', 7 => 'primary', 8 => 'success'];
        //return $account;

        //should check payment option
        //calculate quote cost | calculate the balance | check which action buttons to show
        foreach ($account->quotations as $quotation) {
            //calculate the quote cost
            $productsSubtotal = 0;
            $packagesSubtotal = 0;
            $servicesSubtotal = 0;
            foreach ($quotation->products as $product) {
                $productsSubtotal += ($product->pivot->price * $product->pivot->quantity);
            }
            foreach ($quotation->packages as $package) {
                $packagesSubtotal += ($package->pivot->price * $package->pivot->quantity);
            }
            foreach ($quotation->services as $service) {
                $servicesSubtotal += ($service->quantity * $service->rate);
            }
            $subtotal = ($quotation->quote_type == 2) ? $servicesSubtotal : $productsSubtotal + $packagesSubtotal;
            $discountPercent = $quotation->discount_percent;
            $discountAmount = ($discountPercent > 0) ? ($subtotal * $discountPercent) / 100 : 0;
            $discountedAmount = $subtotal - $discountAmount;
            $vatAmount = ($quotation->add_vat == 1) ? $discountedAmount * 0.14 : 0;
            $total = $discountedAmount + $vatAmount;
            $quotation->cost = $total;

            //calculate the balance
            $totalPaid = CRMPayment::where('quote_id', $quotation->id)->sum('amount');
            $quotation->balance = $quotation->cost - $totalPaid;

            //load invoices
            if ($quotation->payment_option == 1) { //once-off purchases load the first invoice.
                $quotation->load(['invoices' => function ($query) {
                    $query->first();
                }]);
            }

            //Action buttons
            if (in_array($quotation->status, [6, 7])) {
                $quotation->can_capture_payment = true;
            } else {
                $quotation->can_capture_payment = false;
            }
            if ($quotation->status == 8) {
                $quotation->can_send_invoice = false;
            } else {
                $quotation->can_send_invoice = true;
            }
        }

//        return $account;
        $data['page_title'] = "Account";
        $data['page_description'] = "Client Account";
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/quote', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Account', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'search';
        $data['account'] = $account;
        $data['purchaseStatus'] = $purchaseStatus;
        $data['labelColors'] = $labelColors;
        AuditReportsController::store('CRM', "Account Page Accessed (Account # $account->id)", "Accessed By User", 0);

        return view('crm.view_account')->with($data);
    }

    /**
     * View a specific account.
     *
     * @param $quote
     * @return \Illuminate\View\View
     */
    public function viewAccountFromQuote(Quotation $quote)
    {
        $account = CRMAccount::find($quote->account_id);
        return $this->viewAccount($account);
    }

    /**
     * Capture/save a payment in the payments table.
     *
     * @param $quoteID
     * @param $invoiceID
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function capturePayment(Request $request, Quotation $quotation, CRMInvoice $invoice)
    {
        //Validate amount and date
        $this->validate($request, [
            'payment_date' => 'bail|required|date_format:"d/m/Y"',
            'amount_paid' => 'bail|required|numeric|min:0'
        ]);

        //calculate the quote cost
        $quotation->load('products.ProductPackages', 'packages.products_type', 'services');
        $productsSubtotal = 0;
        $packagesSubtotal = 0;
        $servicesSubtotal = 0;
        foreach ($quotation->products as $product) {
            $productsSubtotal += ($product->pivot->price * $product->pivot->quantity);
        }
        foreach ($quotation->packages as $package) {
            $packagesSubtotal += ($package->pivot->price * $package->pivot->quantity);
        }
        foreach ($quotation->services as $service) {
            $servicesSubtotal += ($service->quantity * $service->rate);
        }
        $subtotal = ($quotation->quote_type == 2) ? $servicesSubtotal : $productsSubtotal + $packagesSubtotal;
        $discountPercent = $quotation->discount_percent;
        $discountAmount = ($discountPercent > 0) ? ($subtotal * $discountPercent) / 100 : 0;
        $discountedAmount = $subtotal - $discountAmount;
        $vatAmount = ($quotation->add_vat == 1) ? $discountedAmount * 0.14 : 0;
        $quoteCost = $discountedAmount + $vatAmount;

        $paymentDate = trim($request->input('payment_date'));
        $paymentDate = str_replace('/', '-', $paymentDate);
        $paymentDate = strtotime($paymentDate);

        $payment = null;
        DB::transaction(function () use ($payment, $quotation, $invoice, $request, $paymentDate, $quoteCost) {
            //get the sum of the previous payments
            $invoiceTotalPaid = CRMPayment::where('invoice_id', $invoice->id)->sum('amount');
            $quoteTotalPaid = CRMPayment::where('quote_id', $quotation->id)->sum('amount');

            //save new payment
            $payment = new CRMPayment();
            $payment->quote_id = $quotation->id;
            $payment->invoice_id = $invoice->id;
            $payment->amount = $request->input('amount_paid');
            $payment->payment_date = $paymentDate;
            $payment->save();

            //Upload supporting document
            if ($request->hasFile('supporting_document')) {
                $fileExt = $request->file('supporting_document')->extension();
                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'pdf']) && $request->file('supporting_document')->isValid()) {
                    $fileName = time() . "_proof_of_payment_" . '.' . $fileExt;
                    $request->file('supporting_document')->storeAs('crm/proof_of_payments', $fileName);
                    //Update file name in hr table
                    $payment->proof_of_payment = $fileName;
                    $payment->update();
                }
            }

            //update statuses
            $quotation->status = (($quoteCost - round($quoteTotalPaid + $request->input('amount_paid'), 2)) > 0) ? 7 : 8;
            $quotation->update();
            $invoice->status = (($invoice->amount - round($invoiceTotalPaid + $request->input('amount_paid'), 2)) > 0) ? 3 : 4;
            $invoice->update();

            //audit the action
            AuditReportsController::store('CRM', 'New Payment Captured (payment id: ' . $payment->id . ')', "By User", 0);
        });

        return response()->json(['payment_captured' => ($payment) ? $payment->id : 0], 200);
    }
    
    public function crmreportIndex(){
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->has('quoteProfile');
            }]);
           
            
        $CRMAccount = CRMAccount::orderBy('id', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
      //  return $CRMAccount;
		$products = product_products::where('status', 1)->where('stock_type', '<>',1)->orderBy('name', 'asc')->get();
        $packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();
        $termsAndConditions = QuotesTermAndConditions::where('status', 1)->get();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Search Create';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Reports';
        $data['highestLvl'] = $highestLvl;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['termsAndConditions'] = $termsAndConditions;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);
        
        return view('crm.report_search')->with($data); 
    }
	
}