<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ProductService;
use App\ContactCompany;
use App\ContactPerson;
use App\CRMAccount;
use App\CRMInvoice;
use App\CRMPayment;
use App\DivisionLevel;
use App\EmailTemplate;
use App\HRPerson;
use App\Mail\ApproveQuote;
use App\Mail\SendQuoteToClient;
use App\product_packages;
use App\product_products;
use App\ProductServiceSettings;
use App\Quotation;
use App\QuoteApprovalHistory;
use App\QuoteCompanyProfile;
use App\QuotesTermAndConditions;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CRMSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sendInvoiceTemplate = EmailTemplate::where('template_key', 'send_invoice')->first();

        $data['page_title'] = "CRM";
        $data['page_description'] = "CRM Settings";
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/quote', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'setup';
        $data['sendInvoiceTemplate'] = $sendInvoiceTemplate;
        AuditReportsController::store('CRM', 'CRM Setup Page Accessed', "Accessed By User", 0);

        return view('crm.setup')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function search()
    {
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        
        $data['page_title'] = 'CRM';
        $data['page_description'] = 'Search CRM Account';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Search';
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        AuditReportsController::store('CRM', 'Search CRM Page Accessed', 'Accessed By User', 0);

        return view('crm.search_accounts')->with($data);
    }
	
	public function searchResults(Request $request)
    {
        $companyID = trim($request->company_id);
        $contactPersonID = $request->contact_person_id;
        $accountNumber = $request->account_number;

        $accounts = CRMAccount::where(function ($query) use ($companyID) {
            if ($companyID) {
                $query->where('company_id', $companyID);
            }
        })
		->where(function ($query) use ($contactPersonID) {
			if (!empty($contactPersonID)) {
				$query->where('client_id', $contactPersonID);
			}
		})
		->where(function ($query) use ($accountNumber) {
			if (!empty($accountNumber)) {
				$query->where('account_number', $accountNumber);
			}
		})
        ->with('company', 'client')
        ->orderBy('account_number')
        ->get();
       // return $accounts;
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Search Results';
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => 'crm/accounts/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Results', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Search';
        $data['accounts'] = $accounts;
        AuditReportsController::store('CRM', 'CRM Search Results', 'Accessed By User', 0);
        return view('crm.search_accounts_results')->with($data);
    }
	////
	public function searchAccount(){
            
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $data['page_title'] = 'CRM';
        $data['page_description'] = 'Account Search';
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/crm/search_account', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Account Search';
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        AuditReportsController::store('CRM', 'Account Search Page Accessed', 'Accessed By User', 0);
        
        return view('crm.accounts_search')->with($data); 
    }
	//
}
