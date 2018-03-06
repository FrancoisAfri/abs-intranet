<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\contacts_company;
use App\HRPerson;
use App\Mail\ApprovedCompany;
use App\Mail\CompanyELMApproval;
use App\Mail\RejectedCompany;
use App\Province;
use App\contactsCompanydocs;
use App\contactsClientdocuments;
use App\User;
Use App\contacts_note;
use App\ContactPerson;
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
		$this->middleware('password_expired');
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
        $deparments = DB::table('division_level_fours')->where('active', 1)->orderBy('name', 'asc')->get();
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Contacts";
        $data['page_description'] = "Add a New Company";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add company', 'active' => 1, 'is_module' => 0]
        ];
        $data['provinces'] = $provinces;
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;
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
        $this->validate($request, [
            'name' => 'required',
            'bee_score' => 'numeric',
            'email' => 'email',
            'phys_postal_code' => 'integer',
        ]);

        $formData = $request->all();

        //Exclude empty fields from query
        foreach ($formData as $key => $value) {
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
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $deparments = DB::table('division_level_fours')->where('active', 1)->where('id', $company->dept_id)->first();
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
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;
        return view('contacts.view_company')->with($data);
    }

    public function notes(ContactCompany $company)
    {
        //die('dd/');
        $companyID = $company->id;
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function ($query) {
            $query->orderBy('name', 'asc');
        }]);
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('company_id', $companyID)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $notes = contacts_note::orderBy('id', 'asc')->get();
        $contactnotes = DB::table('contacts_notes')
            ->select('contacts_notes.*', 'contacts_contacts.gender as gender', 'contacts_contacts.profile_pic as profile_pic')
            ->leftJoin('contacts_contacts', 'contacts_notes.hr_person_id', '=', 'contacts_contacts.id')
            ->where('contacts_notes.company_id', $companyID)
            ->orderBy('contacts_notes.id')
            ->get();
        $notesStatus = array(1 => 'Supplier', 2 => 'Operations', 3 => 'Finance', 4 => 'After Hours', 5 => 'Sales', 6 => 'Client');
        $communicationmethod = array(1 => 'Telephone', 2 => 'Meeting/Interview', 3 => 'Email', 4 => 'Fax', 4 => 'SMS');

        $company->load('employees.company');
        $data['notesStatus'] = $notesStatus;
        $data['communicationmethod'] = $communicationmethod;
        $data['page_title'] = "Notes";
        $data['page_description'] = "Notes ";
        $data['contactnotes'] = $contactnotes;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['employees'] = $employees;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'View Notes', 'active' => 1, 'is_module' => 0]
        ];
        // $data['positions'] = $aPositions;
        $data['company'] = $company;
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Company';
        AuditReportsController::store('Notes', 'Notes Updated', " Updated By User", 0);
        return view('contacts.notes')->with($data);
    }

    ####
    public function addnote(Request $request)
    {
        $this->validate($request, [
            // 'hr_id' => 'required',
            // 'number_of_days' => 'required',
        ]);

        $noteData = $request->all();
        unset($noteData['_token']);

        $date = str_replace('/', '-', $noteData['date']);
        $date = strtotime($date);

        $time = str_replace('/', '-', $noteData['time']);
        $time = strtotime($time);

        $follow_date = str_replace('/', '-', $noteData['follow_date']);
        $follow_date = strtotime($follow_date);

        $contactsnote = new contacts_note();
        $contactsnote->originator_type = $noteData['originator_type'];
        $contactsnote->company_id = $noteData['company_id'];
        $contactsnote->hr_person_id = $noteData['hr_person_id'];
        $contactsnote->employee_id = $noteData['employee_id'];
        $contactsnote->date = $date;
        $contactsnote->time = $time;
        $contactsnote->communication_method = $noteData['communication_method'];
        $contactsnote->rensponse = $noteData['rensponse_type'];
        $contactsnote->notes = $noteData['notes'];
        $contactsnote->next_action = $noteData['next_action'];
        $contactsnote->follow_date = $follow_date;
        $contactsnote->save();


        //AuditReportsController::store('Leave custom', 'leave custom Added', "leave type Name: $leave_customs->hr_id", 0);
        return response()->json();
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
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $deparments = DB::table('division_level_fours')->where('active', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "Clients";
        $data['page_description'] = "Edit Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Edit company', 'active' => 1, 'is_module' => 0]
        ];
        $data['company'] = $company;
        $data['provinces'] = $provinces;
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;
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
     * @param  \Illuminate\Http\Request $request
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
        // return  $formData ;
        //Exclude empty fields from query
        foreach ($formData as $key => $value) {
            if (empty($formData[$key])) {
                unset($formData[$key]);
            }
        }

        // return $formData;
        //Update company data
        // $company->estimated_spent = $formData['estimated_spent'];
        // $company->domain_name = $formData['domain_name'];
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
     * @param  \Illuminate\Http\Request $request
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
            'phone_number' => 'bail|required_if:company_type,2|numeric|min:0.1',
            'postal_address' => 'bail|required_if:company_type,2|min:0.1',
        ]);
        $companyData = $request->all();

        //Exclude empty fields from query
        foreach ($companyData as $key => $value) {
            if (empty($companyData[$key])) {
                unset($companyData[$key]);
            }
        }

        //convert numeric values to numbers
        if (isset($companyData['bee_score'])) {
            $companyData['bee_score'] = (double)$companyData['bee_score'];
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
        if ((int)$companyData['company_type'] !== 2) {
            $elManagers = HRPerson::where('position', 4)->get();
            if (count($elManagers) > 0) {
                $elManagers->load('user');
                foreach ($elManagers as $elManager) {
                    $elmEmail = $elManager->email;
                    Mail::to($elmEmail)->send(new CompanyELMApproval($elManager, $company));
                }
                $notifConf = " \nA request for approval has been sent to the Education and Learning Manager(s).";
            }
        }

        $strCompanyType = $this->company_types[(int)$companyData['company_type']];
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
            if ($company->status === 1) {
                $company->status = -1;
                $company->first_approver_id = $user->id;
                $company->first_rejection_reason = $request['rejection_reason'];
            }
            if ($company->status === 2) {
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
        } else return response()->json(['error' => ['Unauthorized user or illegal company status type']], 422);
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
            if ($company->status === 1) {
                $company->status = 2;
                $company->first_approver_id = $user->id;
            } elseif ($company->status === 2) {
                $company->status = 3;
                $company->second_approver_id = $user->id;
            }
            $company->update();

            //Notify the GM about the approval
            $notifConf = '';
            if ($company->status === 2) {
                $gManagers = HRPerson::where('position', 1)->get();
                if (count($gManagers) > 0) {
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
        } else return redirect('/');
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
     * @param  \Illuminate\Http\Request $request
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
            foreach ($companyData as $key => $value) {
                if (empty($companyData[$key])) {
                    unset($companyData[$key]);
                }
            }

            //convert numeric values to numbers
            if (isset($companyData['bee_score'])) {
                $companyData['bee_score'] = (double)$companyData['bee_score'];
            }

            //convert numeric values to numbers
            if (isset($companyData['estimated_spent'])) {
                $companyData['estimated_spent'] = (double)$companyData['estimated_spent'];
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
                if ($company->company_type !== 2) {
                    $elManagers = HRPerson::where('position', 4)->get();
                    if (count($elManagers) > 0) {
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
        } else return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function contactnote(Request $request)
    {
        $this->validate($request, [
            // 'name' => 'required',
        ]);

        $notedata = $request->all();
        unset($notedata['_token']);

        $userID = $notedata['hr_person_id'];
        $companyID = $notedata['company_id'];
        $personID = $notedata['contact_person_id'];
        $notesStatus = array(1 => 'Supplier', 2 => 'Operations', 3 => 'Finance', 4 => 'After Hours', 5 => 'Sales', 6 => 'Client');
        $notes = DB::table('contacts_notes')
            ->select('contacts_notes.*', 'contacts_contacts.first_name as name ', 'contacts_contacts.surname as surname', 'contact_companies.name as companyname')
            ->leftJoin('contacts_contacts', 'contacts_notes.hr_person_id', '=', 'contacts_contacts.id')
            ->leftJoin('contact_companies', 'contacts_notes.company_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('contacts_notes.employee_id', $userID);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('contacts_notes.company_id', $companyID);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('contacts_notes.hr_person_id', $personID);
                }
            })
            ->orderBy('contacts_notes.id')
            ->get();

        //$data['companies'] = $companies;
        $data['notesStatus'] = $notesStatus;
        $data['userID'] = $userID;
        $data['companyID'] = $companyID;
        $data['personID'] = $personID;
        $data['notes'] = $notes;
        // $data['companyname'] = $companyname;
        $data['page_title'] = "Notes  Report";
        $data['page_description'] = "Notes Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Notes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Report';
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('contacts.contacts_note_report_result')->with($data);
    }

    public function meetings(Request $request)
    {
        $this->validate($request, [
            // // 'name' => 'required',
            // 'date_from' => 'date_format:"d F Y"',
            //'action_date' => 'required',
        ]);

        $meetingdata = $request->all();
        unset($meetingdata['_token']);

        $companyID = $meetingdata['company_id'];
        $personID = $meetingdata['contact_person_id'];
        $datefrom = $meetingdata['date_from'];
        $dateto = $meetingdata['date_to'];
        $Datefrom = str_replace('/', '-', $meetingdata['date_from']);
        $Datefrom = strtotime($meetingdata['date_from']);
        $Dateto = str_replace('/', '-', $meetingdata['date_to']);
        $Dateto = strtotime($meetingdata['date_to']);
        $notesStatus = array(1 => 'Supplier', 2 => 'Operations', 3 => 'Finance', 4 => 'After Hours', 5 => 'Sales', 6 => 'Client');
        $meetingminutes = DB::table('meeting_minutes')
            ->select('meeting_minutes.*', 'meetings_minutes.minutes as meeting_minutes', 'contact_companies.name as companyname')
            ->leftJoin('meetings_minutes', 'meeting_minutes.id', '=', 'meetings_minutes.meeting_id')
            ->leftJoin('contact_companies', 'meeting_minutes.company_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('meeting_minutes.meeting_date', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('meetings_minutes.client_id', $personID);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('meeting_minutes.company_id', $companyID);
                }
            })
            // ->orderBy('contacts_notes.id')
            ->get();

        //  $companyname = $meetingminutes->first()->companyname;
        // return $meetingminutes;

        $data['notesStatus'] = $notesStatus;
        $data['companyID'] = $companyID;
        //$data['companyname'] = $companyname;
        $data['personID'] = $personID;
        $data['Datefrom'] = $Datefrom;
        $data['Dateto'] = $Dateto;
        $data['meetingminutes'] = $meetingminutes;
        $data['page_title'] = "Notes  Report";
        $data['page_description'] = "Notes Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Notes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Report';
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('contacts.meeting_minutes_report_result')->with($data);
    }

    ##print reports
    public function printmeetingsReport(Request $request)
    {

        $personID = $request['hr_person_id'];
        $Datefrom = $request['date_from'];
        $Dateto = $request['date_to'];
        $companyID = $request['company_id'];


        $meetingminutes = DB::table('meeting_minutes')
            ->select('meeting_minutes.*', 'meetings_minutes.minutes as meeting_minutes', 'contact_companies.name as companyname')
            ->leftJoin('meetings_minutes', 'meeting_minutes.id', '=', 'meetings_minutes.meeting_id')
            ->leftJoin('contact_companies', 'meeting_minutes.company_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('meeting_minutes.meeting_date', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('meetings_minutes.client_id', $personID);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('meeting_minutes.company_id', $companyID);
                }
            })
            // ->orderBy('contacts_notes.id')
            ->get();

        // return $meetingminutes;


        $data['meetingminutes'] = $meetingminutes;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $user = Auth::user()->load('person');
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Afrixcel Business Solution';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['date'] = date("d-m-Y");
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('contacts.reports.meeting_print')->with($data);
    }

    public function printclientReport(Request $request)
    {

        $userID = $request['hr_person_id'];
        $companyID = $request['company_id'];
        $personID = $request['user_id'];

        $notesStatus = array(1 => 'Supplier', 2 => 'Operations', 3 => 'Finance', 4 => 'After Hours', 5 => 'Sales', 6 => 'Client');

        $notes = DB::table('contacts_notes')
            ->select('contacts_notes.*', 'contacts_contacts.first_name as name ', 'contacts_contacts.surname as surname', 'contact_companies.name as companyname')
            ->leftJoin('contacts_contacts', 'contacts_notes.hr_person_id', '=', 'contacts_contacts.id')
            ->leftJoin('contact_companies', 'contacts_notes.company_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('contacts_notes.employee_id', $userID);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('contacts_notes.company_id', $companyID);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('contacts_notes.hr_person_id', $personID);
                }
            })
            ->orderBy('contacts_notes.id')
            ->get();
        $data['notesStatus'] = $notesStatus;
        $data['notes'] = $notes;
        $data['page_title'] = "Leave history Audit Report";
        $data['page_description'] = "Leave history Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Reports';
        $user = Auth::user()->load('person');
        $data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'Afrixcel Business Solution';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['date'] = date("d-m-Y");
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('contacts.reports.contacts_note_print')->with($data);
    }

    public function viewdocumets(ContactCompany $company)
    {
        $companyID = $company->id;
        $document = contactsCompanydocs::orderby('id', 'asc')->where('company_id', $companyID)->get();
        $data['page_title'] = "Clients";
        $data['page_description'] = "View Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'View company', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Company';
        $data['company'] = $company;
        $data['document'] = $document;
		AuditReportsController::store('Contacts',"Accessed Documents For Company: $company->name", "Accessed By User", 0);
        return view('contacts.contacts_companydocs')->with($data);
    }

    public function addCompanyDoc(Request $request)
    {
        $this->validate($request, [
            //'name' => 'required|unique:contactsCompanydocs,name',
            'exp_date' => 'required',
            'supporting_docs' => 'required',
        ]);

        $contactsCompanydocs = $request->all();
        unset($contactsCompanydocs['_token']);

        $Datefrom = $contactsCompanydocs['date_from'] = str_replace('/', '-', $contactsCompanydocs['date_from']);
        $Datefrom = $contactsCompanydocs['date_from'] = strtotime($contactsCompanydocs['date_from']);

        $Expirydate = $contactsCompanydocs['exp_date'] = str_replace('/', '-', $contactsCompanydocs['exp_date']);
        $Expirydate = $contactsCompanydocs['exp_date'] = strtotime($contactsCompanydocs['exp_date']);

        $contactsCompany = new contactsCompanydocs();
        $contactsCompany->name = $contactsCompanydocs['name'];
        $contactsCompany->description = $contactsCompanydocs['description'];
        $contactsCompany->date_from = $Datefrom;
        $contactsCompany->expirydate = $Expirydate;
        $contactsCompany->company_id = $contactsCompanydocs['companyID'];
        $contactsCompany->status = 1;
        $contactsCompany->save();
		$company = ContactCompany::where('id', $contactsCompanydocs['companyID'])->first(); 
        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $contactsCompany->id . "_client_documents." . $fileExt;
                $request->file('supporting_docs')->storeAs('ContactCompany/company_documents', $fileName);
                //Update file name in the table
                $contactsCompany->supporting_docs = $fileName;
                $contactsCompany->update();
            }
        }

        AuditReportsController::store('Contacts', 'Company Document Added', "Company Document added , Document Name: $contactsCompanydocs[name], 
		Document description: $contactsCompanydocs[description], Document expiry date: $Expirydate ,  Company Name : $company->name", 0);
		return response()->json();
    }

    public function companydocAct(Request $request, contactsCompanydocs $document)
    {
        $companyDetails = ContactCompany::where('id', $document->company_id)->first();
        if ($document->status == 1)
		{
            $stastus = 0;
			$label = "De-Activated";
		}
        else
		{
            $stastus = 1;
			$label = "Activated";
		}
        $document->status = $stastus;
        $document->update();
        AuditReportsController::store('Contacts', "Company Document Status Changed: $label, Document: $document->name, Company $companyDetails->name", "Changed By User", 0);
        return back();
    }

    public function deleteCompanyDoc(contactsCompanydocs $document)
    {
		$companyDetails = ContactCompany::where('id', $document->company_id)->first();
        $document->delete();

        AuditReportsController::store('Contacts', "Company Document Deleted: $document->name For: $companyDetails->name", "Deleted By User", 0);
        return back();
    }

    public function editCompanydoc(Request $request, contactsCompanydocs $company)
    {
        $this->validate($request, [
//            'name' => 'required',
//            'name' => 'required|unique:contactsCompanydocs,name',
//            'exp_date' => 'required',
//            'supporting_docs' => 'required',
        ]);

        $contactsCompanydocs = $request->all();
        unset($contactsCompanydocs['_token']);

        $Datefrom = $contactsCompanydocs['date_from'] = str_replace('/', '-', $contactsCompanydocs['date_from']);
        $Datefrom = $contactsCompanydocs['date_from'] = strtotime($contactsCompanydocs['date_from']);

        $Expirydatet = $contactsCompanydocs['expirydate'] = str_replace('/', '-', $contactsCompanydocs['expirydate']);
        $Expirydate = $contactsCompanydocs['expirydate'] = str_replace('/', '-', $contactsCompanydocs['expirydate']);
        $Expirydate = $contactsCompanydocs['expirydate'] = strtotime($contactsCompanydocs['expirydate']);

        $company->name = $contactsCompanydocs['name'];
        $company->description = $contactsCompanydocs['description'];
        $company->date_from = $Datefrom;
        $company->expirydate = $Expirydate;
        $company->company_id = $contactsCompanydocs['companyID'];
        $company->update();

        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $company->id . "_client_documents." . $fileExt;
                $request->file('supporting_docs')->storeAs('ContactCompany/company_documents', $fileName);
                //Update file name in the table
                $company->supporting_docs = $fileName;
                $company->update();
            }
        }
		$companyDetails = ContactCompany::where('id', $company->company_id)->first();
        // request fields

        AuditReportsController::store('Contacts', 'Document Updated', "Company Document Updated, Document Name: $contactsCompanydocs[name], 
       Document description: $contactsCompanydocs[description], Document expiry date: $Expirydatet, Company Name : $companyDetails->name ", 0);
        return response()->json();
    }

}
