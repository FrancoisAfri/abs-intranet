<?php

namespace App\Http\Controllers;

use App\CRMAccount;
use App\Quotation;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        $account->load('company', 'client', 'quotations.products.ProductPackages', 'quotations.packages.products_type');

        $purchaseStatus = ['' => '', 5 => 'Client Waiting Invoice', 6 => 'Invoice Sent', 7 => 'Partially Paid', 8 => 'Paid'];
        $labelColors = ['' => 'danger', 5 => 'warning', 6 => 'primary', 7 => 'primary', 8 => 'success'];

        //calculate quote cost | calculate the balance | check which action buttons to show
        foreach ($account->quotations as $quotation) {
            //calculate the quote cost
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
            $quotation->cost = $total;

            //Action buttons
            if (in_array($quotation->status, [6, 7])) $quotation->can_capture_payment = true;
            else $quotation->can_capture_payment = false;
            if ($quotation->status == 8) $quotation->can_send_invoice = false;
            else $quotation->can_send_invoice = true;
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
}
