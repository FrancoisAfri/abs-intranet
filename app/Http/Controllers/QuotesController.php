<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\ContactPerson;
use App\DivisionLevel;
use App\EmailTemplate;
use App\HRPerson;
use App\Mail\ApproveQuote;
use App\Mail\SendQuoteToClient;
use App\product_packages;
use App\product_products;
use App\Quotation;
use App\QuoteApprovalHistory;
use App\QuoteCompanyProfile;
use App\QuotesTermAndConditions;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $quoteProfiles = QuoteCompanyProfile::where('status', 1)->where('division_level', $highestLvl->level)->get()->load('divisionLevelGroup');
        $termConditions = QuotesTermAndConditions::where('status', 1)->get();
        $sendQuoteTemplate = EmailTemplate::where('template_key', 'send_quote')->get()->first();
        $approvedQuoteTemplate = EmailTemplate::where('template_key', 'approved_quote')->get()->first();

        $data['page_title'] = "Quotes";
        $data['page_description'] = "Quotation Settings";
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
        AuditReportsController::store('Quote', 'Quote Setup Page Accessed', "Accessed By User", 0);

        return view('quote.quote_setup')->with($data);
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
                $fileName = $quoteProfile->id . "_letter_head_" . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'New Quote Profile Added', "Added By User", 0);
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
                $fileName = $quoteProfile->id . "_letter_head_" . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'Quote Profile Edited. (ID: )' . $quoteProfile->id, "Edited By User", 0);
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
        $products = product_products::where('status', 1)->orderBy('name', 'asc')->get();
        $packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();

        $data['page_title'] = "Quotes";
        $data['page_description'] = "Create a quotation";
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
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', "Accessed By User", 0);

        return view('quote.create_quote')->with($data);
    }

	public function authorisationIndex()
    {
        $data['page_title'] = "Quotes";
        $data['page_description'] = "Quotes Authorisation";
        $data['breadcrumb'] = [
            ['title' => 'Quotes', 'path' => 'quotes/authorisation', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Quotes Authorisation', 'active' => 1, 'is_module' => 0]
        ];
		
		$quoteApplications = Quotation::with(['products','packages', 'person' => function ($query) {
			$query->where('manager_id', Auth::user()->person->id);
		}])
		->orderBy('id')
		->get();

        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Authorisation';
        $data['quoteApplications'] = $quoteApplications;
		return $quoteApplications;
        AuditReportsController::store('Leave', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('quote.authorisation')->with($data);  
    }

    /**
     * Show page to adjust the quote details (such as products quantity, etc.)
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
    */
    public function adjustQuote(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
        ]);

        $validator->after(function($validator) use ($request){
            $products = $request->input('product_id');
            $packages = $request->input('package_id');

            if (! $products && ! $packages) {
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
                $package->price = $packageCost - (($packageCost * $package->discount) /100);
            }
        }
        //return $packages;

        $data['page_title'] = "Quotes";
        $data['page_description'] = "Create a quotation";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'create quote';
        $data['divisionID'] = $request->input('division_id');
        $data['contactPersonId'] = $request->input('contact_person_id');
        $data['companyID'] = $request->input('company_id');
        $data['products'] = $products;
        $data['packages'] = $packages;
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', "Accessed By User", 0);

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
        $validator = Validator::make($request->all(),[
            'division_id' => 'bail|required|integer|min:1',
            'contact_person_id' => 'bail|required|integer|min:1',
            'quantity.*' => 'bail|required|integer|min:1',
            'price.*' => 'bail|required|integer|min:1',
            'discount_percent' => 'numeric',
        ]);
        $validator->validate();

        //get the quote profile and determine if the quotation requires approval
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
        $divisionID = $request->input('division_id');
        $quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $divisionID)->first();

        //return $request->all();
        $user = Auth::user()->load('person');

        //save quote
        $quote = new Quotation();
        DB::transaction(function () use ($quote, $request, $highestLvl, $user) {
            $quote->company_id = ($request->input('company_id') > 0) ? $request->input('company_id') : null;
            $quote->client_id = $request->input('contact_person_id');
            $quote->division_id = $request->input('division_id');
            $quote->division_level = $highestLvl;
            $quote->hr_person_id = $user->person->id;
            $quote->discount_percent = ($request->input('discount_percent')) ? $request->input('discount_percent') : null;
            $quote->add_vat = ($request->input('add_vat')) ? $request->input('add_vat') : null;
            $quote->status = 1;
            $quote->save();

            $prices = $request->input('price');
            $quantities = $request->input('quantity');
            if ($prices) {
                foreach ($prices as $productID => $price) {
                    $quote->products()->attach($productID, ['price' => $price, 'quantity' => $quantities[$productID]]);
                }
            }

            $packagePrices = $request->input('package_price');
            $packageQuantities = $request->input('package_quantity');
            if ($packagePrices) {
                foreach ($packagePrices as $packageID => $packagePrice) {
                    $quote->packages()->attach($packageID, ['price' => $packagePrice, 'quantity' => $packageQuantities[$packageID]]);
                }
            }
        });
		// Add to quote history
		$QuoteApprovalHistory = new QuoteApprovalHistory();
		$QuoteApprovalHistory->quotation_id = $quote->id;
		$QuoteApprovalHistory->user_id = Auth::user()->person->id;
		$QuoteApprovalHistory->status = 1;
		$QuoteApprovalHistory->comment = "New Quote Created";
		$QuoteApprovalHistory->approval_date = strtotime(date('Y-m-d'));
		$QuoteApprovalHistory->save();

        //if authorization required: email manager for authorization
        if($quoteProfile->authorisation_required === 2 && $quote->id) {
            $managerID = $user->person->manager_id;
            if ($managerID) {
                $manager = HRPerson::find($managerID);
                Mail::to($manager->email)->send(new ApproveQuote($manager, $quote->id));
            }
        }
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
        AuditReportsController::store('Quote', 'New Quote Created', "Create by user", 0);

        return redirect('/quote/search')->with(['success_add' => 'The quotation has been successfully added!']);
    }

    /**
     * Show the quotation search page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function searchQuote()
    {
        return 'Search page';
    }

    /**
     * Show the quotation search page
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewQuote(Quotation $quotation, $isPDF = false, $printQuote = false, $emailQuote = false)
    {
        $quotation->load('products.ProductPackages', 'packages.products_type', 'company', 'client');
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
        $discountAmount = ($discountPercent > 0) ? $subtotal * $discountPercent : 0;
        $discountedAmount = $subtotal - $discountAmount;
        $vatAmount = ($quotation->add_vat == 1) ? $discountedAmount * 0.14 : 0;
        $total = $discountedAmount + $vatAmount;

        //return $quotation;

        $data['page_title'] = "Quotes";
        $data['page_description'] = "View a quotation";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'View', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'search';
        $data['quotation'] = $quotation;
        $data['subtotal'] = $subtotal;
        $data['discountPercent'] = $discountPercent;
        $data['discountAmount'] = $discountAmount;
        $data['vatAmount'] = $vatAmount;
        $data['total'] = $total;
        AuditReportsController::store('Quote', 'View Quote Page Accessed', "Accessed By User", 0);

        if ($isPDF) {
            $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
            $quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $quotation->division_id)
                ->first()->load('divisionLevelGroup');

            $data['file_name'] = 'Quotation';
            $data['user'] = Auth::user()->load('person');
            $data['quoteProfile'] = $quoteProfile;
            //$data['date'] = Carbon::now()->format('d/m/Y');

            $view = view('quote.pdf_quote', $data)->render();
            $pdf = resolve('dompdf.wrapper');
            $pdf->loadHTML($view);
            if ($printQuote) return $pdf->stream('quotation_' . $quotation->id . '.pdf');
            elseif ($emailQuote) return $pdf->output();
        }
        else return view('quote.view_quote')->with($data);
    }

    /**
     * Show the quotation PDF view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewPDFQuote(Quotation $quotation)
    {
        return $this->viewQuote($quotation, true, true);
    }
}
