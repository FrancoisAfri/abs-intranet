<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\ContactPerson;
use App\DivisionLevel;
use App\EmailTemplate;
use App\product_packages;
use App\product_products;
use App\Quotation;
use App\QuoteCompanyProfile;
use App\QuotesTermAndConditions;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

		$people = DB::table('hr_people')->orderBy('id', 'asc')->get();
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);
       
		$loggedInEmplID = Auth::user()->person->id;

		$quoteStatus = array(1 => 'Approved' , 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 =>          'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejectd', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr' , 9 => 'rejectd_by_payroll');

		$quoteApplications = DB::table('leave_application')
        ->select('leave_application.*','hr_people.first_name as firstname','hr_people.surname as surname','leave_types.name as leavetype','hr_people.manager_id as manager','leave_credit.leave_balance as leave_Days') 
        ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
        ->leftJoin('leave_types', 'leave_application.hr_id', '=', 'leave_types.id') 
        ->leftJoin('leave_credit', 'leave_application.hr_id', '=', 'leave_credit.hr_id' )
        ->where('hr_people.manager_id', $loggedInEmplID)
		->orderBy('leave_application.hr_id')
        ->get();

        $data['quoteStatus'] = $quoteStatus;
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Authorisation';
        $data['quoteApplications'] = $quoteApplications;

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
        }

        //Get packages
        /*
        $packageIDs = $request->input('package_id');
        $packages = [];
        if (count($packageIDs) > 0) {
            $packages = product_packages::whereIn('id', $packageIDs)
                ->with('products_type')
                ->get();
        }
        return $packages;
        */

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
        AuditReportsController::store('Quote', 'Create Quote Page Accessed', "Accessed By User", 0);

        return view('quote.adjust_quote')->with($data);
    }

    /**
     * Save the project
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
        ]);
        $validator->validate();

        //get the quote profile and determine if the quotation requires approval
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first()->level;
        $divisionID = $request->input('division_id');
        $quoteProfile = QuoteCompanyProfile::where('division_level', $highestLvl)->where('division_id', $divisionID)->first();

        //save quote
        DB::transaction(function () use ($request, $highestLvl) {
            $quote = new Quotation();
            $quote->company_id = ($request->input('company_id') > 0) ? $request->input('company_id') : null;
            $quote->client_id = $request->input('contact_person_id');
            $quote->division_id = $request->input('division_id');
            $quote->division_level = $highestLvl;
            $quote->hr_person_id = Auth::user()->person->id;
            $quote->status = 1;
        });

        //if authorization required: email manager for authorization
        //if authorization not required: email quote to client

        return $request->all();
    }
}
