<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\contacts_company;
use App\HRPerson;
use App\Mail\ApprovedCompany;
use App\Mail\CompanyELMApproval;
use App\Mail\RejectedCompany;
use App\Province;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ContactCompaniesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
    {
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Contacts";
        $data['page_description'] = "Add a New Company";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add company', 'active' => 1, 'is_module' => 0]
        ];
        $data['provinces'] = $provinces;
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Add Company';
        return view('contacts.add_company')->with($data);
    }

    public $company_types = [1 => 'Service Provider', 2 => 'School', 3 => 'Sponsor'];
    public function createServiceProvider()
    {
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Partners";
        $data['page_description'] = "Register a New Service Provider";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts', 'icon' => 'fa address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'Register service provider', 'active' => 1, 'is_module' => 0]
        ];
        $data['company_type'] = 1; //Service provider
        $data['str_company_type'] = $this->company_types[1];
        $data['active_mod'] = 'partners';
        $data['active_rib'] = 'Add New Service Provider';
        $data['provinces'] = $provinces;
		AuditReportsController::store('Partners', 'Service Providers Page Accessed', "Actioned By User", 0);
        return view('contacts.add_company')->with($data);
    }
    public function createSchool()
    {
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Partners";
        $data['page_description'] = "Register a New School";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts', 'icon' => 'fa address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'Register school', 'active' => 1, 'is_module' => 0]
        ];
        $data['company_type'] = 2; //school
        $data['str_company_type'] = $this->company_types[2];
        $data['active_mod'] = 'partners';
        $data['active_rib'] = 'Add New School';
        $data['provinces'] = $provinces;
		AuditReportsController::store('Partners', 'School Page Accessed', "Actioned By User", 0);
        return view('contacts.add_company')->with($data);
    }
    public function createSponsor()
    {
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Partners";
        $data['page_description'] = "Register a New Sponsor";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts', 'icon' => 'fa address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'Register sponsor', 'active' => 1, 'is_module' => 0]
        ];
        $data['company_type'] = 3; //sponsor
        $data['str_company_type'] = $this->company_types[3];
        $data['active_mod'] = 'partners';
        $data['active_rib'] = 'Add New Sponsor';
        $data['provinces'] = $provinces;
		AuditReportsController::store('Partners', 'Sponsor Page Accessed', "Actioned By User", 0);
        return view('contacts.add_company')->with($data);
    }

    public function storeCompany(Request $request)
    {
        //validate form data
        $this->validate($request,[
           'name' => 'required',
           'bee_score' => 'numeric',
           'email' => 'email',
           'phys_postal_code' => 'integer',
        ]);

        $formData = $request->all();

        //Exclude empty fields from query
        foreach ($formData as $key => $value)
        {
            if (empty($formData[$key])) {
                unset($formData[$key]);
            }
        }

        //Insert Data
        $company = new ContactCompany($formData);
        $company->status = 1;
        $company->save();

        //Upload BEE document
        if ($request->hasFile('bee_certificate_doc')) {
            $fileExt = $request->file('bee_certificate_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('bee_certificate_doc')->isValid()) {
                $fileName = $company->id . "_bee_certificate." . $fileExt;
                $request->file('bee_certificate_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->bee_certificate_doc = $fileName;
                $company->update();
            }
        }

        //Upload Company Registration document
        if ($request->hasFile('comp_reg_doc')) {
            $fileExt = $request->file('comp_reg_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('comp_reg_doc')->isValid()) {
                $fileName = $company->id . "_comp_reg_doc." . $fileExt;
                $request->file('comp_reg_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->comp_reg_doc = $fileName;
                $company->update();
            }
        }

        return redirect('/contacts/company/' . $company->id . '/view')->with('success_add', "The company has been added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  ContactCompany $company
     * @return \Illuminate\Http\Response
     */
    public function showCompany(ContactCompany $company)
    {
        $company->load('employees.company');
        $user = Auth::user()->load('person');
        $beeCertDoc = $company->bee_certificate_doc;
        $compRegDoc = $company->comp_reg_doc;
        $provinces = Province::where('country_id', 1)->where('id', $company->phys_province)->get()->first();
        $canEdit = (in_array($user->type, [1, 3]) || ($user->type == 2 && ($user->person->company_id && $user->person->company_id == $company->id))) ? true : false;

        $data['page_title'] = "Clients";
        $data['page_description'] = "View Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'View company', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Company';
        $data['company'] = $company;
        $data['bee_certificate_doc'] = (!empty($beeCertDoc)) ? Storage::disk('local')->url("company_docs/$beeCertDoc") : '';
        $data['comp_reg_doc'] = (!empty($compRegDoc)) ? Storage::disk('local')->url("company_docs/$compRegDoc") : '';
        $data['provinces'] = $provinces;
        $data['canEdit'] = $canEdit;
        return view('contacts.view_company')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ContactCompany $company
     * @return \Illuminate\Http\Response
     */
    public function editCompany(ContactCompany $company)
    {
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Clients";
        $data['page_description'] = "Edit Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Edit company', 'active' => 1, 'is_module' => 0]
        ];
        $data['company'] = $company;
        $data['provinces'] = $provinces;
        $data['active_mod'] = 'clients';
        $data['active_rib'] = 'add company';
        return view('contacts.edit_company')->with($data);
    }
	public function actCompany(ContactCompany $company) 
    {
        if ($company->status == 1) $stastus = 2;
        else $stastus = 1;

        $company->status = $stastus;    
        $company->update();
		AuditReportsController::store('Contacts', 'Client Status Changed', "Status Changed to $stastus for $company->name", 0);
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ContactCompany $company
     * @return \Illuminate\Http\Response
     */
    public function updateCompany(Request $request, ContactCompany $company)
    {
        //Validation
        $this->validate($request, [
            'name' => 'required',
            'bee_score' => 'numeric',
            'email' => 'email',
            'phys_postal_code' => 'integer',
        ]);
        $formData = $request->all();

        //Exclude empty fields from query
        foreach ($formData as $key => $value)
        {
            if (empty($formData[$key])) {
                unset($formData[$key]);
            }
        }

        //Update company data
        $company->update($formData);

        //Upload BEE document
        if ($request->hasFile('bee_certificate_doc')) {
            $fileExt = $request->file('bee_certificate_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('bee_certificate_doc')->isValid()) {
                $fileName = $company->id . "_bee_certificate." . $fileExt;
                $request->file('bee_certificate_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->bee_certificate_doc = $fileName;
                $company->update();
            }
        }

        //Upload Company Registration document
        if ($request->hasFile('comp_reg_doc')) {
            $fileExt = $request->file('comp_reg_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('comp_reg_doc')->isValid()) {
                $fileName = $company->id . "_comp_reg_doc." . $fileExt;
                $request->file('comp_reg_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->comp_reg_doc = $fileName;
                $company->update();
            }
        }

        return redirect('/contacts/company/' . $company->id . '/view')->with('success_edit', "The company details have been successfully updated");
    }
	
	
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'bee_score' => 'bail|required_if:company_type,1|numeric|min:0.1',
            'email' => 'bail|required_if:company_type,2|email',
            'company_type' => 'required',
            'phone_number'=> 'bail|required_if:company_type,2|numeric|min:0.1',
            'postal_address'=>'bail|required_if:company_type,2|min:0.1',
        ]);
        $companyData = $request->all();

        //Exclude empty fields from query
        foreach ($companyData as $key => $value)
        {
            if (empty($companyData[$key])) {
                unset($companyData[$key]);
            }
        }

        //convert numeric values to numbers
        if (isset($companyData['bee_score'])) {
            $companyData['bee_score'] = (double) $companyData['bee_score'];
        }

        //Inset company data
        //$status = ($companyData['company_type'] === 2) ? 3 : 1;
        $company = new contacts_company($companyData);
        $company->status = 1;
        $company->loader_id = Auth::user()->id;
        $company->save();

        //Upload BEE document
        if ($request->hasFile('bee_certificate_doc')) {
            $fileExt = $request->file('bee_certificate_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('bee_certificate_doc')->isValid()) {
                $fileName = $company->id . "_bee_certificate." . $fileExt;
                $request->file('bee_certificate_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->bee_certificate_doc = $fileName;
                $company->update();
            }
        }

        //Upload Company Registration document
        if ($request->hasFile('comp_reg_doc')) {
            $fileExt = $request->file('comp_reg_doc')->extension();
            if (in_array($fileExt, ['pdf']) && $request->file('comp_reg_doc')->isValid()) {
                $fileName = $company->id . "_comp_reg_doc." . $fileExt;
                $request->file('comp_reg_doc')->storeAs('company_docs', $fileName);
                //Update file name in the table
                $company->comp_reg_doc = $fileName;
                $company->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $company->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('activities', $fileName);
                //Update file name in the table
                $company->supporting_doc = $fileName;
                $company->update();
            }
        }

        //Notify the E&L Manager for approval
        $notifConf = '';
        if ((int) $companyData['company_type'] !== 2){
            $elManagers = HRPerson::where('position', 4)->get();
            if(count($elManagers) > 0) {
                $elManagers->load('user');
                foreach ($elManagers as $elManager) {
                    $elmEmail = $elManager->email;
                    Mail::to($elmEmail)->send(new CompanyELMApproval($elManager, $company));
                }
                $notifConf = " \nA request for approval has been sent to the Education and Learning Manager(s).";
            }
        }

        $strCompanyType = $this->company_types[(int) $companyData['company_type']];
		AuditReportsController::store('Partners', 'New Partners Added', "$strCompanyType Added By User", 0);
        return redirect('/contacts/company/' . $company->id . '/view')->with('success_add', "The $strCompanyType has been added successfully.$notifConf");
    }

    /**
     * Display the specified resource.
     *
     * @param  contacts_company $company
     * @return \Illuminate\Http\Response
     */
    public function show(contacts_company $company)
    {
        $user = Auth::user();
        $companyStatus = [-2 => "Rejected by General Manager", -1 => "Rejected by Education and Learning Manager", 1 => "Pending Education and Learning Manager's Approval", 2 => "Pending General Manager's Approval", 3 => 'Approved'];
        $statusLabels = [-2 => "callout-danger", -1 => "callout-danger", 1 => "callout-warning", 2 => 'callout-warning', 3 => 'callout-success'];
        $beeCertDoc = $company->bee_certificate_doc;
        $compRegDoc = $company->comp_reg_doc;
        $supportingDoc = $company->supporting_doc;
        $provinces = Province::where('country_id', 1)->where('id', $company->phys_province_id)->get();
        $accessLvl = DB::table('security_modules_access')->select('access_level')->where('user_id', $user->id)->where('module_id', 4)->first()->access_level;
        $showEdit = (($company->status === 3 && $accessLvl >= 4) || (in_array($company->status, [-1, -2]) && $company->loader_id === $user->id)) ? true : false;
        //$showEdit = true;
        $showELMApproveReject = (in_array($company->company_type, [1, 3]) && $company->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 4) ? true : false;
        $showGMApproveReject = (in_array($company->company_type, [1, 3]) && $company->status === 2 && in_array($user->type, [1, 3]) && $user->person->position === 1) ? true : false;

        $data['page_title'] = "Partners";
        $data['page_description'] = "View Partner's Details";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts', 'icon' => 'fa fa-address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'View details', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'partners';
        $data['active_rib'] = 'search';
        $data['company'] = $company;
        $data['status_strings'] = $companyStatus;
        $data['status_labels'] = $statusLabels;
        $data['bee_certificate_doc'] = (!empty($beeCertDoc)) ? Storage::disk('local')->url("company_docs/$beeCertDoc") : '';
        $data['comp_reg_doc'] = (!empty($compRegDoc)) ? Storage::disk('local')->url("company_docs/$compRegDoc") : '';
        $data['supporting_doc'] = (!empty($supportingDoc)) ? Storage::disk('local')->url("company_docs/$supportingDoc") : '';
        $data['str_company_type'] = $this->company_types[$company->company_type];
        $data['provinces'] = $provinces;
        $data['show_edit'] = $showEdit;
        $data['show_elm_approve'] = $showELMApproveReject;
        $data['show_gm_approve'] = $showGMApproveReject;
		AuditReportsController::store('Partners', 'View Partners Informations', "Partners  Viewed By User", 0);
        return view('contacts.view_company')->with($data);
    }

    /**
     * Reject a loaded company.
     *
     * @param  Request $request
     * @param  contacts_company $company
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, contacts_company $company)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to reject the activity
        if (in_array($company->status, [1, 2]) && in_array($user->type, [1, 3]) && in_array($user->person->position, [1, 4])) {
            //Validate reason
            $this->validate($request, [
                'rejection_reason' => 'required'
            ]);

            //Update status to rejected
            if ($company->status === 1){
                $company->status = -1;
                $company->first_approver_id = $user->id;
                $company->first_rejection_reason = $request['rejection_reason'];
            }
            if ($company->status === 2){
                $company->status = -2;
                $company->second_approver_id = $user->id;
                $company->second_rejection_reason = $request['rejection_reason'];
            }
            $company->update();

            //Notify the applicant about the rejection
            $creator = User::find("$company->loader_id")->load('person');
            $creatorEmail = $creator->person->email;
            Mail::to($creatorEmail)->send(new RejectedCompany($creator, $request['rejection_reason'], $company));
			AuditReportsController::store('Partners', 'Partners Rejected', "Partners Rejected By User", 0);
            return response()->json(['programme_rejected' => $company], 200);
        }
        else return response()->json(['error' => ['Unauthorized user or illegal company status type']], 422);
    }

    /**
     * Approve a loaded company.
     *
     * @param  contacts_company $company
     * @return \Illuminate\Http\Response
     */
    public function approve(contacts_company $company)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to approve the activity
        if (in_array($company->status, [1, 2]) && in_array($user->type, [1, 3]) && in_array($user->person->position, [1, 4])) {
            //Update status to approved
            if ($company->status === 1){
                $company->status = 2;
                $company->first_approver_id = $user->id;
            }
            elseif ($company->status === 2){
                $company->status = 3;
                $company->second_approver_id = $user->id;
            }
            $company->update();

            //Notify the GM about the approval
            $notifConf = '';
            if ($company->status === 2) {
                $gManagers = HRPerson::where('position', 1)->get();
                if(count($gManagers) > 0) {
                    foreach ($gManagers as $gManager) {
                        $gmEmail = $gManager->email;
                        $gmUsr = User::find($gManager->user_id);
                        Mail::to($gmEmail)->send(new ApprovedCompany($gmUsr, $company));
                    }
                    $notifConf = " \nA request for approval has been sent to the General Manager(s).";
                }
            }

            //Notify the loader about the approval
            $strCompanyType = $this->company_types[$company->company_type];
            if ($company->status === 3) {
                $creator = User::find("$company->loader_id")->load('person');
                $creatorEmail = $creator->person->email;
                Mail::to($creatorEmail)->send(new ApprovedCompany($creator, $company));
                $notifConf = " \nA confirmation has been sent to the person who loaded the $strCompanyType.";
            }
			AuditReportsController::store('Partners', 'Partners Approved', "$strCompanyType Approved By User", 0);
            return redirect('/contacts/company/' . $company->id . '/view')->with('success_approve', "The $strCompanyType has been approved successfully.$notifConf");
        }
        else return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  contacts_company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(contacts_company $company)
    {
        $companyStatus = [-2 => "Rejected by General Manager", -1 => "Rejected by Education and Learning Manager", 1 => "Pending Education and Learning Manager's Approval", 2 => "Pending General Manager's Approval", 3 => 'Approved'];
        $statusLabels = [-2 => "callout-danger", -1 => "callout-danger", 1 => "callout-warning", 2 => 'callout-warning', 3 => 'callout-success'];
        $beeCertDoc = $company->bee_certificate_doc;
        $compRegDoc = $company->comp_reg_doc;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();

        $data['page_title'] = "Partners";
        $data['page_description'] = "View Partner's Details";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts', 'icon' => 'fa fa-address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'View details', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'partners';
        $data['active_rib'] = 'search';
        $data['company'] = $company;
        $data['status_strings'] = $companyStatus;
        $data['status_labels'] = $statusLabels;
        $data['bee_certificate_doc'] = (!empty($beeCertDoc)) ? Storage::disk('local')->url("company_docs/$beeCertDoc") : '';
        $data['comp_reg_doc'] = (!empty($compRegDoc)) ? Storage::disk('local')->url("company_docs/$compRegDoc") : '';
        $data['str_company_type'] = $this->company_types[$company->company_type];
		$strCompanyType = $this->company_types[$company->company_type];
        $data['provinces'] = $provinces;
		AuditReportsController::store('Partners', 'Partners Edited', "$strCompanyType On Edit Mode", 0);
        return view('contacts.edit_company')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  contacts_company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, contacts_company $company)
    {
        $user = Auth::user();
        $accessLvl = DB::table('security_modules_access')->select('access_level')->where('user_id', $user->id)->where('module_id', 4)->first()->access_level;
        if (($company->status === 3 && $accessLvl >= 4) || (in_array($company->status, [-1, -2]) && $company->loader_id === $user->id)) {
            //Validation
            $this->validate($request, [
                'name' => 'bail|required|min:2',
                'bee_score' => 'numeric',
                'email' => 'email',
            ]);
            $companyData = $request->all();

            //Exclude empty fields from query
            foreach ($companyData as $key => $value)
            {
                if (empty($companyData[$key])) {
                    unset($companyData[$key]);
                }
            }

            //convert numeric values to numbers
            if (isset($companyData['bee_score'])) {
                $companyData['bee_score'] = (double) $companyData['bee_score'];
            }

            //Update company data
            $company->update($companyData);
            //$company->status = 1;
            //$company->loader_id = Auth::user()->id;

            //Upload BEE document
            if ($request->hasFile('bee_certificate_doc')) {
                $fileExt = $request->file('bee_certificate_doc')->extension();
                if (in_array($fileExt, ['pdf']) && $request->file('bee_certificate_doc')->isValid()) {
                    $fileName = $company->id . "_bee_certificate." . $fileExt;
                    $request->file('bee_certificate_doc')->storeAs('company_docs', $fileName);
                    //Update file name in the table
                    $company->bee_certificate_doc = $fileName;
                    $company->update();
                }
            }

            //Upload Company Registration document
            if ($request->hasFile('comp_reg_doc')) {
                $fileExt = $request->file('comp_reg_doc')->extension();
                if (in_array($fileExt, ['pdf']) && $request->file('comp_reg_doc')->isValid()) {
                    $fileName = $company->id . "_comp_reg_doc." . $fileExt;
                    $request->file('comp_reg_doc')->storeAs('company_docs', $fileName);
                    //Update file name in the table
                    $company->comp_reg_doc = $fileName;
                    $company->update();
                }
            }

            if (in_array($company->status, [-1, -2])) {
                $company->status = 1;
                $company->update();

                //Notify the E&L Manager for approval
                $notifConf = '';
                if ($company->company_type !== 2){
                    $elManagers = HRPerson::where('position', 4)->get();
                    if(count($elManagers) > 0) {
                        $elManagers->load('user');
                        foreach ($elManagers as $elManager) {
                            $elmEmail = $elManager->email;
                            Mail::to($elmEmail)->send(new CompanyELMApproval($elManager, $company));
                        }
                        $notifConf = " \nA request for approval has been sent to the Education and Learning Manager(s).";
                    }
                }
            }

            $strCompanyType = $this->company_types[$company->company_type];
			AuditReportsController::store('Partners', 'Partners Updated', "$strCompanyType Updated By User", 0);
            return redirect('/contacts/company/' . $company->id . '/view')->with('success_edit', "The $strCompanyType details have been successfully updated.$notifConf");
        }
        else return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
