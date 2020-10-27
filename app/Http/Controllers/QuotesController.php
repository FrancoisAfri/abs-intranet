<?php

namespace App\Http\Controllers;

use App\modules;
use App\HRPerson;
use App\Quotation;
use Carbon\Carbon;
use App\CRMAccount;
use App\CRMInvoice;
use App\CRMPayment;
use App\ContactPerson;
use App\DivisionLevel;
use App\EmailTemplate;
use App\QuoteConfiguration;
use App\module_access;
use App\ContactCompany;
use App\ProductService;
use App\CompanyIdentity;
use Barryvdh\DomPDF\PDF;
use App\product_packages;
use App\product_category;
use App\product_products;
use App\termsConditionsCategories;
use App\Mail\ApproveQuote;
use App\QuoteCompanyProfile;
use Illuminate\Http\Request;
use App\QuoteApprovalHistory;
use App\Mail\SendQuoteToClient;
use App\Mail\QuotesRejectionMail;
use App\ProductServiceSettings;
use App\QuotesTermAndConditions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class QuotesController extends Controller
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

    public $quoteStatuses = [1 => 'Awaiting Manager Approval', 2 => 'Awaiting Client Approval',
        3 => 'Approved by Manager', -3 => 'Declined by Manager', 4 => 'Approved by Client',
        -4 => 'Declined by Client', -1 => 'Cancelled', 5 => 'Authorised'];

    /**
     * Show the quote setup page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function setupIndex()
    {
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->doesntHave('quoteProfile');
            }]);
        $highestLvlWithAllDivs = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->load('divisionLevelGroup');
        $validityPeriods = [7, 14, 30, 60, 90, 120];
		//category_id
        $quoteProfiles = QuoteCompanyProfile::where('status', 1)->where('division_level', $highestLvl->level)->get()->load('divisionLevelGroup');
        $termConditions = QuotesTermAndConditions::where('status', 1)->get();
        $sendQuoteTemplate = EmailTemplate::where('template_key', 'send_quote')->get()->first();
        $approvedQuoteTemplate = EmailTemplate::where('template_key', 'approved_quote')->get()->first();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotation Settings';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'setup';
        $data['highestLvl'] = $highestLvl;
        $data['highestLvlWithAllDivs'] = $highestLvlWithAllDivs;
        $data['validityPeriods'] = $validityPeriods;
        $data['quoteProfiles'] = $quoteProfiles;
        $data['termConditions'] = $termConditions;
        $data['sendQuoteTemplate'] = $sendQuoteTemplate;
        $data['approvedQuoteTemplate'] = $approvedQuoteTemplate;
        AuditReportsController::store('Quote', 'Quote Setup Page Accessed', 'Accessed By User', 0);

        return view('quote.quote_setup')->with($data);
    }
	
	public function setupConfiguration()
    {
        $quoteConfiguration = QuoteConfiguration::first();
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotation Settings';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'setup';
        $data['quoteConfiguration'] = $quoteConfiguration;
        AuditReportsController::store('Quote', 'Quote Settings Page Accessed', 'Accessed By User', 0);

        return view('quote.quote_settings')->with($data);
    }
	public function AddQuotesetting(Request $request) {
		$docData = $request->all();
		unset($docData['_token']);
		$QuoteConfiguration = new QuoteConfiguration();
		$QuoteConfiguration->allow_client_email_quote = $request->input('allow_client_email_quote');
		$QuoteConfiguration->save();
		AuditReportsController::store('Quote', 'Quote Configuration Saved', "Actioned By User", 0);
		return back();
    }
	public function AddQuotesettings(Request $request, QuoteConfiguration $setup) {
        $docData = $request->all();
        unset($docData['_token']);
        $setup->allow_client_email_quote = $request->input('allow_client_email_quote');
        $setup->update();
        AuditReportsController::store('Quote', 'Quotes Settings Saved', "Actioned By User", 0);
        return back();
    }
    /**
     * Save the quote profile for a specific division.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveQuoteProfile(Request $request)
    {
        $this->validate($request, [
            'division_id' => 'bail|required|integer|min:1',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'validity_period' => 'required',
        ]);

        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->level;

        $quoteProfile = new QuoteCompanyProfile($request->all());
        $quoteProfile->division_level = $highestLvl;
        $quoteProfile->status = 1;
        $quoteProfile->save();

        //Upload the letter head image
        if ($request->hasFile('letter_head')) {
            $fileExt = $request->file('letter_head')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('letter_head')->isValid()) {
                $fileName = time() . '_letter_head_' . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'New Quote Profile Added', 'Added By User', 0);
        return response()->json(['profile_id' => $quoteProfile->id], 200);
    }

    /**
     * Edit the quote profile for a specific division.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\QuoteCompanyProfile $quoteProfile
     * @return \Illuminate\Http\Response
     */
    public function updateQuoteProfile(Request $request, QuoteCompanyProfile $quoteProfile)
    {
        $this->validate($request, [
            'division_id' => 'bail|required|integer|min:1',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'validity_period' => 'required',
        ]);

        //$quoteProfile = new QuoteCompanyProfile($request->all());
        $quoteProfile->update($request->all());

        //Upload the letter head image
        if ($request->hasFile('letter_head')) {
            $fileExt = $request->file('letter_head')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('letter_head')->isValid()) {
                $fileName = time() . '_letter_head_' . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'Quote Profile Edited. (ID: )' . $quoteProfile->id, 'Edited By User', 0);
        return response()->json(['profile_id' => $quoteProfile->id], 200);
    }

    /**
     * Show the create quote page
     *
     * @return \Illuminate\Contracts\View\View
    */
    public function createIndex()
    {
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->has('quoteProfile');
            }]);
            
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$productsCategories = product_category::orderBy('id', 'asc')->get();

		$products = product_products::where('status', 1)->where('stock_type', '<>',1)->orderBy('name', 'asc')->get();
        $packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();

		$termsAndConditions = DB::table('quotes_terns_conditions')
            ->select('quotes_terns_conditions.id as term_id', 'quotes_terns_conditions.term_name',
			'quote_terms_categories.name as cat_name','quotes_terns_conditions.category_id')
            ->leftJoin('quote_terms_categories', 'quote_terms_categories.id', '=', 'quotes_terns_conditions.category_id')
			->where('quotes_terns_conditions.status',1)
			->where('quotes_terns_conditions.category_id','>',0)
			->orderBy('cat_name')
			->orderBy('term_name')
			->get();
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Create a quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'create quote';
        $data['highestLvl'] = $highestLvl;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
		$data['productsCategories'] = $productsCategories;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['termsAndConditions'] = $termsAndConditions;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);

        return view('quote.create_quote')->with($data);
    }

    public function authorisationIndex()
    {
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first();

        //Check if logged in person is a superuser
        $user = Auth::user()->load('person');
        $quoteModule = modules::where('code_name', 'quote')->first();
        $moduleID = ($quoteModule) ? $quoteModule->id : 0;
        $objQuoteModAccess = module_access::where('module_id', $moduleID)->where('user_id', $user->id)->first();
        $quoteModAccess = $objQuoteModAccess ? $objQuoteModAccess->access_level : 0;
        $isSuperUser = ($quoteModAccess >= 5) ? true : false;

        $quoteApplications = Quotation::where(function ($query) use ($isSuperUser) {
            if (! $isSuperUser) {
                $query->whereHas('person', function ($query) {
                    $query->where('manager_id', $user->person->id);
                });
            }
        })
        ->whereIn('status', [1, 2])
        ->with('products', 'packages', 'person', 'company', 'client', 'divisionName')
        ->orderBy('id')
        ->get();
        $data['highestLvl'] = $highestLvl;
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Authorisation';
        $data['breadcrumb'] = [
            ['title' => 'Quotes', 'path' => 'quotes/authorisation', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Quotes Authorisation', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Authorisation';
        $data['quoteApplications'] = $quoteApplications;
        //return $quoteApplications;
        AuditReportsController::store('Quote', 'Quote Authorisation Page Accessed', 'Accessed By User', 0);
        return view('quote.authorisation')->with($data);
    }

    /**
     * Client approved the quote
     *
     * @param $request
     * @param $quotation
     * @return \Illuminate\Contracts\View\View
     */
    public function clientApproveQuote(Request $request, Quotation $quote)
    {
        $this->validate($request, [
            'payment_option' => 'bail|required|integer|min:1',
            'payment_term' => 'bail|required_if:payment_option,2|integer',
            'first_payment_date' => 'bail|required_if:payment_option,2|date_format:d/m/Y',
        ]);

        $paymentOption = $request->input('payment_option');
        $paymentTerm = $request->input('payment_term');
        $firstPaymentDate = str_replace('/', '-', $request->input('first_payment_date'));
        $firstPaymentDate = strtotime($firstPaymentDate);

        return $this->approveQuote($quote, true, $paymentOption, $paymentTerm, $firstPaymentDate);
    }

    // Approve Quote
    public function approveQuote(Quotation $quote, $isClientApproval = false, $paymentOption = null, $paymentTerm = null, $firstPaymentDate = null)
    {
        $quote->load('products', 'packages', 'services');
        $stastus = $quote->status;
        if ($quote->status == 1) {
            $stastus = 2;
        } elseif ($quote->status == 2) {
            $stastus = 5;
        }
        $configuration = QuoteConfiguration::first();
       // return $stastus;
        $quote->status = $stastus;
        $changedStatus = 'Status Changed To: ' . $quote->quote_status;
		$quote->load('client', 'person');
		$email = !empty($quote->person->email) ? $quote->person->email : '';
        //$quote->update();
        if ($stastus == 5) {
            //Email Client to confirm success
            //$quote->load('client');
            $messageContents = EmailTemplate::where('template_key', 'approved_quote')->first();
            if (!empty($messageContents)) {
				
                $messageContent = $messageContents->template_content;
				$messageContent = str_replace('[client name]', $quote->client->full_name, $messageContent);
				$messageContent = str_replace('[employee details]', $quote->person->first_name." ".$quote->person->surname." ".$quote->person->email." ".$quote->person->cell_number, $messageContent);
				$quoteAttachment = $this->viewQuote($quote, false,true, false, true);
				if (!empty($quote->client->email))
					Mail::to($quote->client->email)->send(new SendQuoteToClient($messageContent, $quoteAttachment,$email));
            }
            $quote->status = 5;

            //Create an account for the client or add quote to his existing account
            DB::transaction(function () use ($isClientApproval, $quote, $paymentOption, $paymentTerm, $firstPaymentDate) {
                if ($isClientApproval) {
                    $quote->payment_option = $paymentOption;
                    $quote->payment_term = ($paymentTerm) ? $paymentTerm : null;
                    $quote->first_payment_date = ($firstPaymentDate) ? $firstPaymentDate : null;

                    $crmAccount = CRMAccount::where('client_id', $quote->client_id)
                        ->where(function ($query) use ($quote) {
                            if ($quote->company_id && $quote->company_id > 0) {
                                $query->where('company_id', $quote->company_id);
                            }
                        })->first();
                    if (!($crmAccount)) {
                        $crmAccount = new CRMAccount();
                        $crmAccount->client_id = $quote->client_id;
                        $crmAccount->company_id = $quote->company_id;
                        $crmAccount->start_date = time();
                        $crmAccount->status = 1;
                        $crmAccount->save();
                    }

                    $companyDetails = CompanyIdentity::systemSettings();
                    $accountNumber = $companyDetails['header_acronym_bold'] . $companyDetails['header_acronym_regular'];
                    $accountNumber = !empty($accountNumber) ? strtoupper($accountNumber) : 'SYS';
                    $accountNumber .= 'ACC' . sprintf('%07d', $crmAccount->id);
                    $crmAccount->account_number = $accountNumber;
                    $crmAccount->update();

                    $quote->account_id = $crmAccount->id;
                }
                $quote->update();

                //create invoices if the payment option is 2
                if ($quote->payment_option == 2 && $quote->payment_term > 0 && $quote->first_payment_date) {
                    $paymentDueDate = Carbon::createFromTimestamp($quote->first_payment_date); //payment due date

                    //calculate quotation total cost
                    $productsSubtotal = 0;
                    $packagesSubtotal = 0;
                    $servicesSubtotal = 0;
                    foreach ($quote->products as $product) {
                        $productsSubtotal += ($product->pivot->price * $product->pivot->quantity);
                    }
                    foreach ($quote->packages as $package) {
                        $packagesSubtotal += ($package->pivot->price * $package->pivot->quantity);
                    }
                    foreach ($quote->services as $service) {
                        $servicesSubtotal += ($service->quantity * $service->rate);
                    }
                    $subtotal = ($quote->quote_type == 2) ? $servicesSubtotal : $productsSubtotal + $packagesSubtotal;
                    $discountPercent = $quote->discount_percent;
                    $discountAmount = ($discountPercent > 0) ? ($subtotal * $discountPercent) / 100 : 0;
                    $discountedAmount = $subtotal - $discountAmount;
                    $vatAmount = ($quote->add_vat == 1) ? $discountedAmount * 0.15 : 0;
                    $quoteTotal = $discountedAmount + $vatAmount;

                    $invoiceAmount = $quoteTotal / $quote->payment_term;

                    for ($i = 1; $i <= $quote->payment_term; $i++) {
                        $invoice = new CRMInvoice();
                        $invoice->quotation_id = $quote->id;
                        $invoice->company_id = $quote->company_id;
                        $invoice->client_id = $quote->client_id;
                        $invoice->account_id = $quote->account_id;
                        $invoice->invoice_date = time();
                        $invoice->payment_due_date = $paymentDueDate->timestamp;
                        $invoice->status = 1;
                        $invoice->amount = $invoiceAmount;
                        $invoice->save();

                        $companyDetails = CompanyIdentity::systemSettings();
                        $invoiceNumber = $companyDetails['header_acronym_bold'] . $companyDetails['header_acronym_regular'];
                        $invoiceNumber = !empty($invoiceNumber) ? strtoupper($invoiceNumber) : 'SYS';
                        $invoiceNumber .= 'INV' . sprintf('%07d', $invoice->id);
                        $invoice->invoice_number = $invoiceNumber;
                        $invoice->update();
                        $paymentDueDate->addMonth();
                    }
                }
            });
            $changedStatus .= ', Email sent to client, to welcome them';
        } elseif ($stastus == 2) {
            //if authorization not required: email quote to client and update status to awaiting client approval
            $messageContents = EmailTemplate::where('template_key', 'send_quote')->first();
            if (!empty($messageContents) && !empty($configuration->allow_client_email_quote)) {
                $messageContent = $messageContents->template_content;
				$messageContent = str_replace('[client name]', $quote->client->full_name, $messageContent);
				$messageContent = str_replace('[employee details]', $quote->person->first_name." ".$quote->person->surname." ".$quote->person->email." ".$quote->person->cell_number, $messageContent);
				$quoteAttachment = $this->viewQuote($quote, false,true, false, true);
				Mail::to($quote->client->email)->send(new SendQuoteToClient($messageContent, $quoteAttachment,$email));
            }
			$quote->status = $stastus;
            $quote->update();
            $changedStatus .= ', Email sent to client, to notify them';
        }
        // Add to quote history
        $QuoteApprovalHistory = new QuoteApprovalHistory();
        $QuoteApprovalHistory->quotation_id = $quote->id;
        $QuoteApprovalHistory->user_id = Auth::user()->person->id;
        $QuoteApprovalHistory->status = $stastus;
        $QuoteApprovalHistory->comment = $changedStatus;
        $QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
        $QuoteApprovalHistory->save();
        AuditReportsController::store('Quote', "Quote Status Changed: $changedStatus", 'Edited by User', 0);
        if ($isClientApproval) {
            return redirect('/crm/account/quote/' . $quote->id);
        } else {
            return back();
        }
    }

    // Decline Quote
    public function declineQuote(Quotation $quote, $isClientApproval = false, $rejectionReason= '')
    {
        if ($quote->status == 1) {
            $stastus = -3;
        } else {
            $stastus = -4;
        }
        $quote->status = $stastus;
        $quote->update();
        $changedStatus = $this->quoteStatuses[$stastus];
		if (!empty($rejectionReason)) $changedStatus = $changedStatus." "."Rejection Reason: ".$rejectionReason;
        // Add to quote history
        $QuoteApprovalHistory = new QuoteApprovalHistory();
        $QuoteApprovalHistory->quotation_id = $quote->id;
        $QuoteApprovalHistory->user_id = Auth::user()->person->id;
        $QuoteApprovalHistory->status = $stastus;
        $QuoteApprovalHistory->comment = $changedStatus;
        $QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
        $QuoteApprovalHistory->save();
		// Email quote Creator		
		if (!empty($quote->hr_person_id))
		{
			$creator = HRPerson::find($quote->hr_person_id);
			if (!empty($creator->email))
                Mail::to($creator->email)->send(new QuotesRejectionMail($creator, $quote->id, $changedStatus));
		}
        AuditReportsController::store('Quote', "Quote Status Changed: $changedStatus", 'Edited by User', 0);
        return back();
    }
	
	// Decline Quote
    public function clientDeclineQuote(Request $request,Quotation $quote)
    {
        $this->validate($request, [
            'rejection_reason' => 'bail|required',
        ]);

        $rejectionReason = $request->input('rejection_reason');

        return $this->declineQuote($quote, true, $rejectionReason);
    }
	
    // Cancel quote
    public function cancelQuote(Quotation $quote)
    {
        $quote->status = -1;
        $quote->update();
        // Add to quote history
        $QuoteApprovalHistory = new QuoteApprovalHistory();
        $QuoteApprovalHistory->quotation_id = $quote->id;
        $QuoteApprovalHistory->user_id = Auth::user()->person->id;
        $QuoteApprovalHistory->status = -1;
        $QuoteApprovalHistory->comment = 'Cancelled By User';
        $QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
        $QuoteApprovalHistory->save();
        AuditReportsController::store('Quote', 'Quote Status Changed: Cancelled', 'Edited by User', 0);
        return back();
    }

    /**
     * Show page to adjust the quote details (such as products quantity, etc.)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
    */
    public function adjustQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_type' => 'bail|required|integer|min:1',
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
        ]);
        $quoteType = $request->input('quote_type');

        $validator->after(function ($validator) use ($request, $quoteType) {
            $products = $request->input('product_id');
            $packages = $request->input('package_id');
			$contactPersonID = $request->input('contact_person_id');
			$contactPeople = ContactPerson::where('status', 1)->where('id', $contactPersonID)->first();
            if (empty($contactPeople->email))
                $validator->errors()->add('contact_email', 'The Contact person Choosen does not have an email address. Please add one before completing this process.');
            elseif (!empty($contactPeople->email) && !filter_var($contactPeople->email, FILTER_VALIDATE_EMAIL))
				$validator->errors()->add('contact_email', 'The Contact person Choosen does not have a valid email address. Please correct it before completing this process.');
				
			if (($quoteType == 1) && (!$products && !$packages)) {
                $validator->errors()->add('product_id', 'Please make sure you select at least a product or a package.');
                $validator->errors()->add('package_id', 'Please make sure you select at least a product or a package.');
            }
        });

        $validator->validate();
       // return $request->all();

        $currentTime = time();

        //Get products
        $productIDs = $request->input('product_id');
        $products = [];
        if (count($productIDs) > 0) {
            $products = product_products::whereIn('id', $productIDs)
                ->with(['ProductPackages', 'productPrices' => function ($query) {
                    $query->orderBy('id', 'desc');
                    $query->limit(1);
                }, 'promotions' => function ($query) use ($currentTime) {
                    $query->where('status', 1)
                        ->whereRaw("start_date < $currentTime")
                        ->whereRaw("end_date > $currentTime")
                        ->orderBy('start_date', 'desc')
                        ->limit(1);
                }])
                ->orderBy('category_id')
                ->get();
            //get products current prices
            foreach ($products as $product) {
                $promoDiscount = ($product->promotions->first()) ? $product->promotions->first()->discount : 0;
                $currentPrice = ($product->productPrices->first())
                    ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
                $currentPrice = $currentPrice - (($currentPrice * $promoDiscount) / 100);
                $product->current_price = $currentPrice;
            }
        }

        //Get packages
        $packageIDs = $request->input('package_id');
        $packages = [];
        if (count($packageIDs) > 0) {
            $packages = product_packages::whereIn('id', $packageIDs)
                ->with(['products_type' => function ($query) {
                    $query->with(['productPrices' => function ($query) {
                        $query->orderBy('id', 'desc');
                        $query->limit(1);
                    }]);
                }, 'promotions' => function ($query) use ($currentTime) {
                    $query->where('status', 1)
                        ->whereRaw("start_date < $currentTime")
                        ->whereRaw("end_date > $currentTime")
                        ->orderBy('start_date', 'desc')
                        ->limit(1);
                }])
                ->get();
            //calculate the package price
            foreach ($packages as $package) {
                $packageProducts = $package->products_type;
                $packageCost = 0;
                foreach ($packageProducts as $packageProduct) {
                    $packageProduct->current_price = ($packageProduct->productPrices && $packageProduct->productPrices->first())
                        ? $packageProduct->productPrices->first()->price : (($packageProduct->price) ? $packageProduct->price : 0);

                    $packageCost += $packageProduct->current_price;
                }
                $packageDiscount = ($package->discount) ? $package->discount : 0;
                $promoDiscount = ($package->promotions->first()) ? $package->promotions->first()->discount : 0;
                $packagePrice = $packageCost - (($packageCost * $packageDiscount) / 100);
                $packagePrice = $packagePrice - (($packagePrice * $promoDiscount) / 100);
                $package->price = $packagePrice;
            }
        }
        //return $packages;

        $servicesSettings = ProductServiceSettings::first();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Create a quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'create quote';
        $data['divisionID'] = $request->input('division_id');
        $data['quoteType'] = $quoteType;
        $data['contactPersonId'] = $request->input('contact_person_id');
        $data['companyID'] = $request->input('company_id');
        $data['tcIDs'] = $request->input('tc_id');
        $data['quote_title'] = $request->input('quote_title');
        $data['quote_remarks'] = $request->input('quote_remarks');
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['servicesSettings'] = $servicesSettings;
        //return $data;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);
        return view('quote.adjust_quote')->with($data);
    }

    /**
     * Save the quote
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function saveQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote_type' => 'bail|required|integer|min:1',
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
            'quote_title' => 'bail|required',
            'quantity.*' => 'bail|required|integer|min:1',
            'package_quantity.*' => 'bail|required|integer|min:1',
            'service_quantity.*' => 'bail|required|integer|min:1',
            //'price' => 'bail|required_if:quote_type,1',
            //'price.*' => 'bail|required|integer|min:1',
            'package_price.*' => 'bail|required|integer|min:1',
            'service_rate' => 'bail|required_if:quote_type,2|numeric',
            'description' => 'bail|required_if:quote_type,2',
            'description.*' => 'bail|required|max:1000',
            'discount_percent' => 'numeric',
        ]);
        $validator->validate();

        //get the quote profile and determine if the quotation requires approval
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
        $divisionID = $request->input('division_id');
        $quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $divisionID)->first();

        //return $request->all();
        $user = Auth::user()->load('person');
        $status = 1;
        //save quote
        $quote = new Quotation();
        DB::transaction(function () use ($quote, $request, $highestLvl, $user) {
            $quoteType = $request->input('quote_type');
            $quote->quote_type = ($quoteType > 0) ? $quoteType : null;
            $quote->company_id = ($request->input('company_id') > 0) ? $request->input('company_id') : null;
            $quote->client_id = $request->input('contact_person_id');
            $quote->division_id = $request->input('division_id');
            $quote->quote_title = $request->input('quote_title');
            $quote->quote_remarks = $request->input('quote_remarks');
            $quote->division_level = $highestLvl;
            $quote->hr_person_id = $user->person->id;
            $quote->discount_percent = ($request->input('discount_percent')) ? $request->input('discount_percent') : null;
            $quote->add_vat = ($request->input('add_vat')) ? $request->input('add_vat') : null;
            $quote->status = 1;
            $quote->save();

            $companyDetails = CompanyIdentity::systemSettings();
            $quoteNumber = $companyDetails['header_acronym_bold'] . $companyDetails['header_acronym_regular'];
            $quoteNumber = !empty($quoteNumber) ? strtoupper($quoteNumber) : 'SYS';
            $quoteNumber .= 'QTE' . sprintf('%07d', $quote->id);
            $quote->quote_number = $quoteNumber;
            $quote->update();

            if ($quoteType == 1) {
                //save quote's products
                $prices = $request->input('current_price');
                $quantities = $request->input('quantity');
				$comments = $request->input('comment');
                if ($prices) {
                    foreach ($prices as $productID => $price) {
						$price = preg_replace('/(?<=\d)\s+(?=\d)/', '', $price);
						$price = (double) $price;
                        $quote->products()->attach($productID, ['price' => $price, 'quantity' => $quantities[$productID], 'comment' => $comments[$productID]]);
                    }
                }
                //save quote's packages
                $packagePrices = $request->input('package_price');
                $packageQuantities = $request->input('package_quantity');
                if ($packagePrices) {
                    foreach ($packagePrices as $packageID => $packagePrice) {
                        $quote->packages()->attach($packageID, ['price' => $packagePrice, 'quantity' => $packageQuantities[$packageID]]);
                    }
                }
            } elseif ($quoteType == 2) {
                $serviceSettings = ProductServiceSettings::first();
                $serviceRate = ($serviceSettings) ? $serviceSettings->service_rate : 0;

                //save quote's services
                $serviceDescription = $request->input('description');
                $serviceQuantity = $request->input('service_quantity');
                if ($serviceDescription) {
                    foreach ($serviceDescription as $key => $description) {
                        $service = new ProductService();
                        $service->quotation_id = $quote->id;
                        $service->description = $description;
                        $service->quantity = $serviceQuantity[$key];
                        $service->rate = $serviceRate;
                        $service->save();
                    }
                }
            }
            //save quote's T&C's
            $tcIDs = ($request->input('tc_id')) ? $request->input('tc_id') : [];
            $quote->termsAndConditions()->sync($tcIDs);
        });
        //if authorization required: email manager for authorization
        if ($quoteProfile->authorisation_required === 2 && $quote->id) {
            $managerID = $user->person->manager_id;
            if ($managerID) {
                $manager = HRPerson::find($managerID);
				if (!empty($manager->email))
					Mail::to($manager->email)->send(new ApproveQuote($manager, $quote->id));
            }
            //Add to quote history
            $QuoteApprovalHistory = new QuoteApprovalHistory();
            $QuoteApprovalHistory->quotation_id = $quote->id;
            $QuoteApprovalHistory->user_id = Auth::user()->person->id;
            $QuoteApprovalHistory->status = $status;
            $QuoteApprovalHistory->comment = 'New Quote Created, Manager Approval';
            $QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
            $QuoteApprovalHistory->save();
        }
		else 
		{
            $status = 2;
            //if authorization not required: email quote to client and update status to awaiting client approval
			$quote->load('client','person');
            $messageContent = EmailTemplate::where('template_key', 'send_quote')->first();
            if (!empty($messageContent)) {
                $messageContent = $messageContent->template_content;
            }

			if (!empty($quote->client->full_name) && !empty($quote->client->email))
			{
				$messageContent = str_replace('[client name]', $quote->client->full_name, $messageContent);
				$quoteAttachment = $this->viewQuote($quote, false ,true, false, true);
				Mail::to($quote->client->email)->send(new SendQuoteToClient($messageContent, $quoteAttachment));
            }
			$quote->status = 2;
            $quote->update();
            //Add to quote history
            $QuoteApprovalHistory = new QuoteApprovalHistory();
            $QuoteApprovalHistory->quotation_id = $quote->id;
            $QuoteApprovalHistory->user_id = Auth::user()->person->id;
            $QuoteApprovalHistory->status = $status;
            $QuoteApprovalHistory->comment = 'New Quote Created, Client Approval';
            $QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
            $QuoteApprovalHistory->save();
        }
        AuditReportsController::store('Quote', 'New Quote Created', 'Create by user', 0);

        return redirect("/quote/view/$quote->id/01")->with(['success_add' => 'The quotation has been successfully added!']);
    }

    /**
     * Show the quotation search page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchQuote()
    {
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->has('quoteProfile');
            }]);
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $products = product_products::where('status', 1)->where('stock_type', '<>',1)->orderBy('name', 'asc')->get();
        $packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();
        $termsAndConditions = QuotesTermAndConditions::where('status', 1)->get();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Search quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'create quote';
        $data['highestLvl'] = $highestLvl;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['termsAndConditions'] = $termsAndConditions;
        AuditReportsController::store('Quote', 'Search Quote Page Accessed', 'Accessed By User', 0);

        return view('quote.search_quote')->with($data);
    }

    public function searchResults(Request $request)
    {
        $companyID = trim($request->company_id);

        $contactPersonID = $request->contact_person_id;
        $personPassportNum = $request->passport_number;
        $divisionID = $request->division_id;

        $status = $request->status;
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first();
        $quoteApplications = Quotation::where(function ($query) use ($companyID) {
			if ($companyID /*&& $companyID != "01"*/) {
                $query->where('company_id', $companyID);
            }
        })
		->where(function ($query) use ($status) {
				if ($status) {
                $query->where('status', $status);
				}
            })
		->where(function ($query) use ($divisionID) {
				if ($divisionID) {
                $query->where('division_id', $divisionID);
				}
            })
        ->with('products', 'packages', 'person', 'company', 'client', 'divisionName')
        ->orderBy('id', 'desc')
        ->get();
        $data['highestLvl'] = $highestLvl;
        $data['companyID'] = !empty($companyID) ? $companyID : '01';
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Search Results';
        $data['breadcrumb'] = [
            ['title' => 'Quotes', 'path' => 'quotes/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Results', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Search Results';
        $data['quoteApplications'] = $quoteApplications;
        AuditReportsController::store('Quote', 'Quote Search Results', 'Accessed By User', 0);
        return view('quote.search_results')->with($data);
    }

    /**
     * Show the quotation search page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewQuote(Quotation $quotation, $companyID=false , $isPDF = false, $printQuote = false, $emailQuote = false, $isInvoice = false, CRMInvoice $paramInvoice = null)
    {
        $quotation->load('products.ProductPackages', 'packages.products_type', 'company', 'client', 'termsAndConditions', 'services', 'person');
        //return $quotation;
		$invoice = null;
        $totalPaid = 0;
        $paymentTerm = null;
        $remainingTerm = null;
		//return $quotation;
		$hrID = Auth::user()->id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'quote')->where('security_modules_access.access_level', '>=', 3)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();
			
        if ($isInvoice) {
            $quotation->load('invoices', 'account');

            //get the sum of the previous payments
            $totalPaid = CRMPayment::where('quote_id', $quotation->id)->sum('amount');

            //specify invoice
            $invoice = ($quotation->payment_option == 1) ? $quotation->invoices->first() : $paramInvoice;

            //update statuses if invoice is being emailed to the client
            if ($emailQuote) {
                DB::transaction(function () use ($quotation, $invoice) {
                    if ($quotation->status < 6) {
                        $quotation->status = 6;
                        $quotation->update();
                    }
                    if ($invoice->status < 2) {
                        $invoice->status = 2;
                        $invoice->update();
                    }
                });
            }

            //get the payment term
            $paymentTerm = $quotation->invoices->count();
            $remainingTerm = $quotation->invoices->where('status', '<>', 4)->count();
        }
        $productsSubtotal = 0;
        $packagesSubtotal = 0;
        $servicesSubtotal = 0;
        $totalServiceQty = 0;
        foreach ($quotation->products as $product) {
            $productsSubtotal += ($product->pivot->price * $product->pivot->quantity);
        }
        foreach ($quotation->packages as $package) {
            $packagesSubtotal += ($package->pivot->price * $package->pivot->quantity);
        }
        foreach ($quotation->services as $service) {
            $totalServiceQty += $service->quantity;
            $servicesSubtotal += ($service->quantity * $service->rate);
        }
        $subtotal = ($quotation->quote_type == 2) ? $servicesSubtotal : $productsSubtotal + $packagesSubtotal;
        $discountPercent = $quotation->discount_percent;
        $discountAmount = ($discountPercent > 0) ? ($subtotal * $discountPercent) / 100 : 0;
        $discountedAmount = $subtotal - $discountAmount;
        $vatAmount = ($quotation->add_vat == 1) ? $discountedAmount * 0.15 : 0;
        $total = $discountedAmount + $vatAmount;

        //return $quotation;
        $servicesSettings = ProductServiceSettings::first();

        $data['page_title'] = ($isInvoice) ? 'Invoice' : 'Quotes';
        $data['page_description'] = 'View a quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'View', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'search';
        $data['servicesSettings'] = $servicesSettings;
        $data['totalServiceQty'] = $totalServiceQty;
        $data['quotation'] = $quotation;
        $data['invoice'] = $invoice;
        $data['totalPaid'] = $totalPaid;
        $data['subtotal'] = $subtotal;
        $data['companyID'] = $companyID ;
        $data['discountPercent'] = $discountPercent;
        $data['discountAmount'] = $discountAmount;
        $data['vatAmount'] = $vatAmount;
        $data['total'] = $total;
        $data['balanceDue'] = $total - $totalPaid;
        $data['paymentTerm'] = $paymentTerm;
        $data['remainingTerm'] = $remainingTerm;
        $data['userAccess'] = $userAccess;
		
        AuditReportsController::store('Quote', 'View Quote Page Accessed', 'Accessed By User', 0);
		
        if ($isPDF === true || $companyID === 'pdf') {

            $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
            $quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $quotation->division_id)
                ->first();
			if (!empty($quoteProfile )) $quoteProfile  = $quoteProfile->load('divisionLevelGroup'); 
           
			$companyDetails = CompanyIdentity::systemSettings();

			$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
			$data['date'] = date("d-m-Y");

			$data['file_name'] = 'Quotation';
            $data['user'] = Auth::user()->load('person');
            $data['quoteProfile'] = $quoteProfile;

            $view = ($isInvoice) ? view('crm.pdf_invoice', $data)->render() : view('quote.pdf_quote', $data)->render();
            $pdf = resolve('dompdf.wrapper');
            $pdf->getDomPDF()->set_option('enable_html5_parser', true);
            $pdf->loadHTML($view);
            if ($printQuote || $companyID === 'pdf') {
				if ($isInvoice) return $pdf->stream('invoice' . $quotation->id . '.pdf');
				else return $pdf->stream('quotation_' . $quotation->id . '.pdf');
            }
			elseif ($emailQuote) {
                return $pdf->output();
            }
			// Add to quote history
			$QuoteApprovalHistory = new QuoteApprovalHistory();
			$QuoteApprovalHistory->quotation_id = $quote->id;
			$QuoteApprovalHistory->user_id = Auth::user()->person->id;
			$QuoteApprovalHistory->status = $quote->status;
			$QuoteApprovalHistory->comment = "Quote Printed";
			$QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
			$QuoteApprovalHistory->save();
        } else {
            if ($isInvoice) {
                return view('crm.view_invoice')->with($data);
            } else {
                return view('quote.view_quote')->with($data);
            }
			// Add to quote history
			$QuoteApprovalHistory = new QuoteApprovalHistory();
			$QuoteApprovalHistory->quotation_id = $quote->id;
			$QuoteApprovalHistory->user_id = Auth::user()->person->id;
			$QuoteApprovalHistory->status = $quote->status;
			$QuoteApprovalHistory->comment = "Quote Viewed";
			$QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
			$QuoteApprovalHistory->save();
        }
    }

    /**
     * Show the quotation PDF view
     *
     * @return \Illuminate\Contracts\View\View
    */
    public function viewPDFQuote(Quotation $quotation)
    {
        return $this->viewQuote($quotation, false ,true, true);
    }

    /**
     * Email the quotation to the client
     *
     * @return \Illuminate\Contracts\View\View
    */
    public function emailQuote(Quotation $quote)
    {
        $quote->load('client','person');
        $messageContent = EmailTemplate::where('template_key', 'send_quote')->first();
		if (!empty($messageContent))
		{
			$QuoteApprovalHistory = new QuoteApprovalHistory();
			$QuoteApprovalHistory->quotation_id = $quote->id;
			$QuoteApprovalHistory->user_id = Auth::user()->person->id;
			$QuoteApprovalHistory->status = $quote->status;
			$QuoteApprovalHistory->comment = "Quote Emailed";
			$QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
			$QuoteApprovalHistory->save();
			
			$messageContent = $messageContent->template_content;
			$messageContent = str_replace('[client name]', $quote->client->full_name, $messageContent);
			$messageContent = str_replace('[employee details]', $quote->person->first_name." ".$quote->person->surname." ".$quote->person->email." ".$quote->person->cell_number, $messageContent);
			$quoteAttachment = $this->viewQuote($quote,false, true, false, true);
			Mail::to($quote->client->email)->send(new SendQuoteToClient($messageContent, $quoteAttachment));
		}

        return back()->with(['quote_emailed' => 'The quotation has been successfully emailed to the client!']);
    }

    /**
     * Show the quotation first update page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function updateQuoteIndex(Quotation $quote)
    {
        $quote->load('products', 'packages', 'termsAndConditions', 'services');
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->has('quoteProfile');
            }]);
        //return $quote;
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $products = product_products::where('status', 1)->where('stock_type', '<>',1)->orderBy('name', 'asc')->get();
        $packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();
        $termsAndConditions = DB::table('quotes_terns_conditions')
            ->select('quotes_terns_conditions.id as term_id', 'quotes_terns_conditions.term_name',
			'quote_terms_categories.name as cat_name','quotes_terns_conditions.category_id')
            ->leftJoin('quote_terms_categories', 'quote_terms_categories.id', '=', 'quotes_terns_conditions.category_id')
			->where('quotes_terns_conditions.status',1)
			->where('quotes_terns_conditions.category_id','>',0)
			->orderBy('cat_name')
			->orderBy('term_name')
			->get();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Modify a quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Modify', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'search';
        $data['quote'] = $quote;
        $data['highestLvl'] = $highestLvl;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['termsAndConditions'] = $termsAndConditions;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);

        return view('quote.edit_quote')->with($data);
    }

    /**
     * Show the quotation second update (adjust) page
     *
     * @return \Illuminate\Contracts\View\View
    */
    public function adjustQuoteModification(Request $request, Quotation $quote)
    {
        $quote->load('products', 'packages');
		//$quote->load('products.ProductPackages', 'packages.products_type', 'company', 'client', 'termsAndConditions', 'services');
        $validator = Validator::make($request->all(), [
            'quote_type' => 'bail|required|integer|min:1',
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
        ]);
		//return $quote;
        $quoteType = $request->input('quote_type');

        $validator->after(function ($validator) use ($request, $quoteType) {
            $products = $request->input('product_id');
            $packages = $request->input('package_id');

            if (($quoteType == 1) && !$products && !$packages) {
                $validator->errors()->add('product_id', 'Please make sure you select at least a product or a package.');
                $validator->errors()->add('package_id', 'Please make sure you select at least a product or a package.');
            }
        });

        $validator->validate();

        //Get products
        $productIDs = $request->input('product_id');
        $products = [];
        if (count($productIDs) > 0) {
            $products = product_products::whereIn('id', $productIDs)
                ->with(['ProductPackages', 'productPrices' => function ($query) {
                    $query->orderBy('id', 'desc');
                    $query->limit(1);
                }])
                ->orderBy('category_id')
                ->get();
            //get products current prices
            foreach ($products as $product) {
                $product->current_price = ($product->productPrices && $product->productPrices->first())
                    ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
            }
        }

        //Get packages
        $packageIDs = $request->input('package_id');
        $packages = [];
        if (count($packageIDs) > 0) {
            $packages = product_packages::whereIn('id', $packageIDs)
                ->with(['products_type' => function ($query) {
                    $query->with(['productPrices' => function ($query) {
                        $query->orderBy('id', 'desc');
                        $query->limit(1);
                    }]);
                }])
                ->get();
            //calculate the package price
            foreach ($packages as $package) {
                $packageProducts = $package->products_type;
                $packageCost = 0;
                foreach ($packageProducts as $packageProduct) {
                    $packageProduct->current_price = ($packageProduct->productPrices && $packageProduct->productPrices->first())
                        ? $packageProduct->productPrices->first()->price : (($packageProduct->price) ? $packageProduct->price : 0);

                    $packageCost += $packageProduct->current_price;
                }
                $package->price = $packageCost - (($packageCost * $package->discount) / 100);
            }
        }

        $servicesSettings = ProductServiceSettings::first();

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Modify quotation';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'create quote';
        $data['servicesSettings'] = $servicesSettings;
        $data['quoteType'] = $quoteType;
        $data['quoteTitle'] = $request->input('quote_title');
        $data['quoteRemarks'] = $request->input('quote_remarks');
        $data['divisionID'] = $request->input('division_id');
        $data['contactPersonId'] = $request->input('contact_person_id');
        $data['companyID'] = $request->input('company_id');
        $data['tcIDs'] = $request->input('tc_id');
        $data['quote'] = $quote;
        $data['products'] = $products;
        $data['packages'] = $packages;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);

        return view('quote.edit_adjust_quote')->with($data);
    }

    /**
     * Update a quotation
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function updateQuote(Request $request, Quotation $quote)
    {
        $validator = Validator::make($request->all(), [
            'quote_type' => 'bail|required|integer|min:1',
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
            //'quantity' => 'bail|required',
            'quantity.*' => 'bail|required|integer|min:1',
            'package_quantity.*' => 'bail|required|integer|min:1',
            'service_quantity.*' => 'bail|required|integer|min:1',
            //'price' => 'bail|required_if:quote_type,1',
            'price.*' => 'bail|required|integer|min:1',
            'package_price.*' => 'bail|required|integer|min:1',
            'service_rate' => 'bail|required_if:quote_type,2|numeric',
            'description' => 'bail|required_if:quote_type,2',
            'description.*' => 'bail|required|max:1000',
            'discount_percent' => 'numeric',
        ]);
        $validator->validate();

        //get the quote profile and determine if the quotation requires approval
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
        //$divisionID = $request->input('division_id');
        //$quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $divisionID)->first();

        //return $request->all();
        $user = Auth::user()->load('person');

        //save quote
        //$quote = new Quotation();
        DB::transaction(function () use ($quote, $request, $highestLvl, $user) {
            $quoteType = $request->input('quote_type');
            $quote->quote_type = ($quoteType > 0) ? $quoteType : null;
            $quote->company_id = ($request->input('company_id') > 0) ? $request->input('company_id') : null;
            $quote->client_id = $request->input('contact_person_id');
            $quote->division_id = $request->input('division_id');
            $quote->division_level = $highestLvl;
			$quote->quote_title = $request->input('quote_title');
            $quote->quote_remarks = $request->input('quote_remarks');
            //$quote->hr_person_id = $user->person->id;
            $quote->discount_percent = ($request->input('discount_percent')) ? $request->input('discount_percent') : null;
            $quote->add_vat = ($request->input('add_vat')) ? $request->input('add_vat') : null;
            $quote->status = 1;
            $quote->update();

            if ($quoteType == 1) {

                //save quote's products
                $prices = $request->input('current_price');
                $quantities = $request->input('quantity');
				$comments = $request->input('comment');
                $quote->products()->detach();
                if ($prices) {
                    foreach ($prices as $productID => $price) {
                        $quote->products()->attach($productID, ['price' => $price, 'quantity' => $quantities[$productID], 'comment' => $comments[$productID]]);
                    }
                }

                //save quote's packages
                $packagePrices = $request->input('package_price');
                $packageQuantities = $request->input('package_quantity');
                $quote->packages()->detach();
                if ($packagePrices) {
                    foreach ($packagePrices as $packageID => $packagePrice) {
                        $quote->packages()->attach($packageID, ['price' => $packagePrice, 'quantity' => $packageQuantities[$packageID]]);
                    }
                }
            } elseif ($quoteType == 2) {
                $serviceSettings = ProductServiceSettings::first();
                $serviceRate = ($serviceSettings) ? $serviceSettings->service_rate : 0;

                //save quote's services
                $serviceDescription = $request->input('description');
                $serviceQuantity = $request->input('service_quantity');
                if ($serviceDescription) {
                    $deleterRows = ProductService::where('quotation_id', $quote->id)->delete();
                    foreach ($serviceDescription as $key => $description) {
                        $service = new ProductService();
                        $service->quotation_id = $quote->id;
                        $service->description = $description;
                        $service->quantity = $serviceQuantity[$key];
                        $service->rate = $serviceRate;
                        $service->save();
                    }
                }
            }

            //save quote's T&C's
            $tcIDs = ($request->input('tc_id')) ? $request->input('tc_id') : [];
            $quote->termsAndConditions()->sync($tcIDs);
        });
        // Add to quote history
        $QuoteApprovalHistory = new QuoteApprovalHistory();
        $QuoteApprovalHistory->quotation_id = $quote->id;
        $QuoteApprovalHistory->user_id = Auth::user()->person->id;
        $QuoteApprovalHistory->status = 1;
        $QuoteApprovalHistory->comment = 'Quote Modified';
        $QuoteApprovalHistory->approval_date = time();
        $QuoteApprovalHistory->save();

        //if authorization required: email manager for authorization
        /* if($quoteProfile->authorisation_required === 2 && $quote->id) { */
        $managerID = $user->person->manager_id;
        if ($managerID) {
            $manager = HRPerson::find($managerID);
            Mail::to($manager->email)->send(new ApproveQuote($manager, $quote->id));
        }
        /*}
        else {
            //if authorization not required: email quote to client and update status to awaiting client approval
            $quote->load('client');
            $messageContent = EmailTemplate::where('template_key', 'send_quote')->first()->template_content;
            $messageContent = str_replace('[client name]', $quote->client->full_name, $messageContent);
            $quoteAttachment = $this->viewQuote($quote, true, false, true);
            Mail::to($quote->client->email)->send(new SendQuoteToClient($messageContent, $quoteAttachment));
            $quote->status = 2;
            $quote->update();
        }
        */
        AuditReportsController::store('Quote', 'Quote Modified', 'Modified by user', 0);

        return redirect("/quote/view/$quote->id/01")->with(['success_add' => 'The quotation has been successfully added!']);
    }

    //
    public function newQuote(Request $request)
    {
        $this->validate($request, [
        ]);
        $quotes = $request->all();

        unset($quotes['_token']);
        foreach ($quotes as $key => $value) {
            if (empty($quotes[$key])) {
                unset($quotes[$key]);
            }
        }
        return $quotes;
        foreach ($quotes as $key => $sValue) {
            if (strlen(strstr($key, 'selected'))) {
                $aValue = explode('_', $key);
                $proID = $aValue[1];

                //return $unit;
                $products = product_products::where('id', $proID)->orderBy('category_id', 'asc')->get();
                if (!empty($products)) {
                    $products = $products->load('promotions');
                }

                foreach ($products as $product) {
                    $promoDiscount = ($product->promotions->first()) ? $product->promotions->first()->discount : 0;
                    $currentPrice = ($product->productPrices->first()) ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
                    $currentPrice = $currentPrice - (($currentPrice * $promoDiscount) / 100);
                    $product->current_price = $currentPrice;
                }
            }
        }

        $quote = new Quotation();
        $user = Auth::user()->load('person');

        //
        $typID[] = $quotes['package_quantity'];
        return $typID;

        $data['page_title'] = 'Dashboard';
        $data['page_description'] = 'Main Dashboard';
        //$data['Ribbon_module'] = $Ribbon_module;
        return back();
        // return view('dashboard.client_dashboard')->with($data); //Clients Dashboard
    }
    
    public function Quotereports(){
      
        $highestLvl = DivisionLevel::where('active', 1)
            ->orderBy('level', 'desc')->limit(1)->get()->first()
            ->load(['divisionLevelGroup' => function ($query) {
                $query->has('quoteProfile');
            }]);
            
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
       // return $contactPeople;
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
        AuditReportsController::store('Quote', 'Report Page Accessed', 'Accessed By User', 0);
        
        return view('quote.report_search')->with($data);
    }
    
    public function reportsinndex(Request $request){
       $this->validate($request, [
            
        ]);
        $QuoteData = $request->all();
        unset($QuoteData['_token']);  
       
        $actionFrom = $actionTo = 0;
        $actionDate = $QuoteData['action_date'];
        $DivisionID = $QuoteData['division_id'];
        $CompanyID = $QuoteData['company_id'];
        $quoteType = $QuoteData['quote_type'];
        $status = $QuoteData['status'];
        $contactpersonID = $QuoteData['contact_person_id'];
        $productID = !empty($QuoteData['product_id']) ? $QuoteData['product_id'] : 0; 
        $packageID = !empty($QuoteData['package_id']) ? $QuoteData['package_id'] : 0; 
          
         if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
       
        $quotation = Quotation::all();

        if ($quoteType == 1){

			$quotationsAudit = DB::table('quotations')
                            ->select('quotations.*','contact_companies.name as companyname','contacts_contacts.first_name as firstname' ,
                                    'contacts_contacts.surname as surname' , 'hr_people.first_name as quotefirstname','hr_people.surname as quotesurname',
                                    'hr.first_name as approverfirstname','hr.surname as approversurname' ,'quote_approval_history.status as quoteStatus',
                                    'quote_approval_history.comment as Comment','quote_approval_history.approval_date as approvaldate',
                                    'quote_approval_history.user_id as approvalID' ) 
                            ->leftJoin('contact_companies', 'quotations.company_id', '=', 'contact_companies.id')        
                            ->leftJoin('contacts_contacts', 'quotations.client_id', '=', 'contacts_contacts.id') 
                            ->leftJoin('hr_people', 'quotations.hr_person_id', '=', 'hr_people.id')
                            ->leftJoin('quote_approval_history', 'quotations.id', '=', 'quote_approval_history.quotation_id')                 
                            ->leftJoin('hr_people as hr', 'quote_approval_history.user_id', '=', 'hr.id')   
                            ->where(function ($query) use ($actionFrom, $actionTo) {
                                if ($actionFrom > 0 && $actionTo > 0) {
                                    $query->whereBetween('quote_approval_history.approval_date', [$actionFrom, $actionTo]);
                                }
                            })
                            ->where(function ($query) use ($DivisionID) {
                                if (!empty($DivisionID)) {
                                    $query->where('quotations.division_id', $DivisionID);
                                }
                            })
                            ->where(function ($query) use ($CompanyID) {
                                if (!empty($CompanyID)) {
                                    $query->where('quotations.division_level', $CompanyID);
                                }
                            })
                            ->where(function ($query) use ($contactpersonID) {
                                if (!empty($contactpersonID)) {
                                    $query->where('quotations.hr_person_id', $contactpersonID);
                                }
                            })
                            ->where(function ($query) use ($contactpersonID) {
                                if (!empty($contactpersonID)) {
                                    $query->where('quotations.hr_person_id', $contactpersonID);
                                }
                            })
//                            ->where(function ($query) use ($status) {
//                                if (!empty($status)) {
//                                    $query->where('quote_approval_history.status', $status);
//                                }
//                            })
                            ->orderBy('quotations.id')
                            ->get();    
         

          
        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Report';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Reports';
        $data['quotationsAudit'] = $quotationsAudit;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);
        
        return view('quote.quoute_print')->with($data);
        
        }elseif($quoteType == 2 && $status == 1 ){ // converted into invoice
            
            $quotationsAudit = DB::table('quotations')
                            ->select('quotations.*','contact_companies.name as companyname','contacts_contacts.first_name as firstname' ,
                                    'contacts_contacts.surname as surname' , 'hr_people.first_name as quotefirstname','hr_people.surname as quotesurname',
                                    'hr.first_name as approverfirstname','hr.surname as approversurname' ,'quote_approval_history.status as quoteStatus',
                                    'quote_approval_history.comment as Comment','quote_approval_history.approval_date as approvaldate',
                                    'quote_approval_history.user_id as approvalID' ) 
                            ->leftJoin('contact_companies', 'quotations.company_id', '=', 'contact_companies.id')        
                            ->leftJoin('contacts_contacts', 'quotations.client_id', '=', 'contacts_contacts.id') 
                            ->leftJoin('hr_people', 'quotations.hr_person_id', '=', 'hr_people.id')
                            ->leftJoin('quote_approval_history', 'quotations.id', '=', 'quote_approval_history.quotation_id')                 
                            ->leftJoin('hr_people as hr', 'quote_approval_history.user_id', '=', 'hr.id')    
                            ->whereIn('quote_approval_history.status', [ 3, 4 , 5 , 6 , 7 , 8])//check if the booking is not approved
                            ->orderBy('quotations.id')
                            ->get();  
             
            // return $quotationsAudit;

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Report';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Reports';
        $data['quotationsAudit'] = $quotationsAudit;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);
        return view('quote.convertedinvoice_print')->with($data);

        }elseif($quoteType == 2 && $status == 2 ){ // Not converted invoice
           $quotationsAudit = DB::table('quotations')
                            ->select('quotations.*','contact_companies.name as companyname','contacts_contacts.first_name as firstname' ,
                                    'contacts_contacts.surname as surname' , 'hr_people.first_name as quotefirstname','hr_people.surname as quotesurname',
                                    'hr.first_name as approverfirstname','hr.surname as approversurname' ,'quote_approval_history.status as quoteStatus',
                                    'quote_approval_history.comment as Comment','quote_approval_history.approval_date as approvaldate',
                                    'quote_approval_history.user_id as approvalID' ) 
                            ->leftJoin('contact_companies', 'quotations.company_id', '=', 'contact_companies.id')        
                            ->leftJoin('contacts_contacts', 'quotations.client_id', '=', 'contacts_contacts.id') 
                            ->leftJoin('hr_people', 'quotations.hr_person_id', '=', 'hr_people.id')
                            ->leftJoin('quote_approval_history', 'quotations.id', '=', 'quote_approval_history.quotation_id')                 
                            ->leftJoin('hr_people as hr', 'quote_approval_history.user_id', '=', 'hr.id')    
                            ->whereNotIn('quote_approval_history.status', [ 3, 4 , 5 , 6 , 7 , 8])//check if the booking is not approved
                            ->orderBy('quotations.id')
                            ->get();  
             
            // return $quotationsAudit;

        $data['page_title'] = 'Quotes';
        $data['page_description'] = 'Quotes Report';
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        
        $data['quotationsAudit'] = $quotationsAudit;
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Reports';
        
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', 'Accessed By User', 0);
        return view('quote.invoice_print')->with($data); 
       
        }
	}
	public function historyReports(Request $request){
		
			$this->validate($request, [
				
			]);
			$QuoteData = $request->all();
			unset($QuoteData['_token']);  
		   
			$actionFrom = $actionTo = 0;
			$actionDate = $QuoteData['action_date'];
			$DivisionID = $QuoteData['division_id'];
			$CompanyID = $QuoteData['company_id'];
			$contactpersonID = $QuoteData['contact_person_id'];
			  
			 if (!empty($actionDate)) {
				$startExplode = explode('-', $actionDate);
				$actionFrom = strtotime($startExplode[0]);
				$actionTo = strtotime($startExplode[1]);
			}
		   
			$quotation = Quotation::all();

			$quotationsAudits = DB::table('quotations')
				->select('quotations.*','contact_companies.name as companyname','contacts_contacts.first_name as firstname' ,
						'contacts_contacts.surname as surname' ,
						'hr.first_name as approverfirstname','hr.surname as approversurname' ,'quote_approval_history.status as quoteStatus',
						'quote_approval_history.comment as Comment','quote_approval_history.approval_date as approvaldate',
						'quote_approval_history.user_id as approvalID' )
				->leftJoin('contact_companies', 'quotations.company_id', '=', 'contact_companies.id')        
				->leftJoin('contacts_contacts', 'quotations.client_id', '=', 'contacts_contacts.id') 
				->leftJoin('quote_approval_history', 'quotations.id', '=', 'quote_approval_history.quotation_id') 
				->leftJoin('hr_people as hr', 'quote_approval_history.user_id', '=', 'hr.id')   
				->where(function ($query) use ($actionFrom, $actionTo) {
					if ($actionFrom > 0 && $actionTo > 0) {
						$query->whereBetween('quote_approval_history.approval_date', [$actionFrom, $actionTo]);
					}
				})
				->where(function ($query) use ($DivisionID) {
					if (!empty($DivisionID)) {
						$query->where('quotations.division_id', $DivisionID);
					}
				})
				->where(function ($query) use ($CompanyID) {
					if (!empty($CompanyID)) {
						$query->where('quotations.company_id', $CompanyID);
					}
				})
				->where(function ($query) use ($contactpersonID) {
					if (!empty($contactpersonID)) {
						$query->where('quotations.client_id', $contactpersonID);
					}
				})
				->orderBy('quotations.id')
               ->get();  
			$data['page_title'] = 'Quotes';
			$data['page_description'] = 'Quotes Report';
			$data['breadcrumb'] = [
				['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
				['title' => 'Create', 'active' => 1, 'is_module' => 0]
			];
			//$quoteStatuses = $this->quoteStatuses;
			$data['quoteStatuses'] = $this->quoteStatuses;
			$data['contactpersonID'] = $contactpersonID;
			$data['actionDate'] = $actionDate;
			$data['CompanyID'] = $CompanyID;
			$data['DivisionID'] = $DivisionID;
			$data['active_mod'] = 'Quote';
			$data['active_rib'] = 'Reports';
			$data['quotationsAudits'] = $quotationsAudits;
			AuditReportsController::store('Quote', 'Quote History Report Page Accessed', 'Accessed By User', 0);
        
        return view('quote.quoute_history_report')->with($data);

    }
	public function historyReportsPrint(Request $request){
		
			$this->validate($request, [
				
			]);
			$QuoteData = $request->all();
			unset($QuoteData['_token']);  
		   
			$actionFrom = $actionTo = 0;
			$actionDate = $QuoteData['action_date'];
			$DivisionID = $QuoteData['division_id'];
			$CompanyID = $QuoteData['company_id'];
			$contactpersonID = $QuoteData['contact_person_id'];
			  
			 if (!empty($actionDate)) {
				$startExplode = explode('-', $actionDate);
				$actionFrom = strtotime($startExplode[0]);
				$actionTo = strtotime($startExplode[1]);
			}
		   
			$quotation = Quotation::all();

			$quotationsAudits = DB::table('quotations')
				->select('quotations.*','contact_companies.name as companyname','contacts_contacts.first_name as firstname' ,
						'contacts_contacts.surname as surname' ,
						'hr.first_name as approverfirstname','hr.surname as approversurname' ,'quote_approval_history.status as quoteStatus',
						'quote_approval_history.comment as Comment','quote_approval_history.approval_date as approvaldate',
						'quote_approval_history.user_id as approvalID' )
				->leftJoin('contact_companies', 'quotations.company_id', '=', 'contact_companies.id')        
				->leftJoin('contacts_contacts', 'quotations.client_id', '=', 'contacts_contacts.id') 
				->leftJoin('quote_approval_history', 'quotations.id', '=', 'quote_approval_history.quotation_id') 
				->leftJoin('hr_people as hr', 'quote_approval_history.user_id', '=', 'hr.id')   
				->where(function ($query) use ($actionFrom, $actionTo) {
					if ($actionFrom > 0 && $actionTo > 0) {
						$query->whereBetween('quote_approval_history.approval_date', [$actionFrom, $actionTo]);
					}
				})
				->where(function ($query) use ($DivisionID) {
					if (!empty($DivisionID)) {
						$query->where('quotations.division_id', $DivisionID);
					}
				})
				->where(function ($query) use ($CompanyID) {
					if (!empty($CompanyID)) {
						$query->where('quotations.company_id', $CompanyID);
					}
				})
				->where(function ($query) use ($contactpersonID) {
					if (!empty($contactpersonID)) {
						$query->where('quotations.client_id', $contactpersonID);
					}
				})
				->orderBy('quotations.id')
               ->get();  
			
			$data['page_title'] = 'Quotes';
			$data['page_description'] = 'Quotes Report';
			$data['breadcrumb'] = [
				['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
				['title' => 'Create', 'active' => 1, 'is_module' => 0]
			];
			$data['quoteStatuses'] = $this->quoteStatuses;
			$data['contactpersonID'] = $contactpersonID;
			$data['actionDate'] = $actionDate;
			$data['CompanyID'] = $CompanyID;
			$data['DivisionID'] = $DivisionID;
			$data['active_mod'] = 'Quote';
			$data['active_rib'] = 'Reports';
			$data['quotationsAudits'] = $quotationsAudits;
			$companyDetails = CompanyIdentity::systemSettings();
			$companyName = $companyDetails['company_name'];
			$user = Auth::user()->load('person');

			$data['support_email'] = $companyDetails['support_email'];
			$data['company_name'] = $companyName;
			$data['full_company_name'] = $companyDetails['full_company_name'];
			$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
			$data['date'] = date("d-m-Y");
			$data['user'] = $user;
			AuditReportsController::store('Quote', 'Quote History Report Page Accessed', 'Accessed By User', 0);  
			return view('quote.report_history_print')->with($data);
    }
}