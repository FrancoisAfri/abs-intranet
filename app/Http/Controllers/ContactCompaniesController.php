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
use App\CompanyIdentity;
use App\EmployeeTasks;
use App\contactsClientdocuments;
use App\User;
use App\ClientInduction;
Use App\contacts_note;
Use App\CrmDocumentType;
use App\ContactPerson;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\TaskManagementController;
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
        $deparments = DB::table('division_level_fives')->where('active', 1)->orderBy('name', 'asc')->get();
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$data['page_title'] = "Contacts";
        $data['page_description'] = "Add a New Company";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add company', 'active' => 1, 'is_module' => 0]
        ];
        $data['provinces'] = $provinces;
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;
        $data['employees'] = $employees;
        $data['active_mod'] = 'CRM';
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
            'account_owners' => 'integer',
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
                $fileName = time() . "_bee_certificate." . $fileExt;
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
                $fileName = time() . "_comp_reg_doc." . $fileExt;
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
        $company->load('employees.company','accountManager');

        $user = Auth::user()->load('person');
        $beeCertDoc = $company->bee_certificate_doc;
        $compRegDoc = $company->comp_reg_doc;
        $provinces = Province::where('country_id', 1)->where('id', $company->phys_province)->get()->first();
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $deparments = DB::table('division_level_fives')->where('active', 1)->where('id', $company->dept_id)->first();
        $canEdit = (in_array($user->type, [1, 3]) || ($user->type == 2 && ($user->person->company_id && $user->person->company_id == $company->id))) ? true : false;
		
		$communicationStatus = array(1 => 'Email', 2 => 'SMS');
        $contactsCommunications = DB::table('contacts_communications')
            ->select('contacts_communications.*', 'contacts_contacts.first_name', 'contacts_contacts.surname', 
			'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname',
			'contact_companies.name as companyname')
            ->leftJoin('contact_companies', 'contact_companies.id', '=', 'contacts_communications.company_id')
            ->leftJoin('contacts_contacts', 'contacts_contacts.id', '=', 'contacts_communications.contact_id')
            ->leftJoin('hr_people', 'hr_people.id', '=', 'contacts_communications.sent_by')
            ->where('contacts_communications.company_id',  $company->id)
            ->orderBy('contacts_communications.communication_date','contacts_communications.company_id')
            ->get();
		// task
		$tasks = DB::table('employee_tasks')
			->select('employee_tasks.id as task_id','employee_tasks.employee_id'
			,'employee_tasks.start_date'
			,'employee_tasks.due_date'
			,'employee_tasks.administrator_id','employee_tasks.upload_required'
			,'employee_tasks.description','employee_tasks.order_no','employee_tasks.notes'
			,'employee_tasks.status','employee_tasks.date_completed'
			,'hr_people.first_name as hr_fist_name','hr_people.surname as hr_surname')
			->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
			->where('employee_tasks.client_id', $company->id)
			->orderBy('employee_tasks.due_date')
			->get();
		// notes
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('company_id', $company->id)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $contactnotes = DB::table('contacts_notes')
            ->select('contacts_notes.*', 'hr_people.gender as gender'
			, 'hr_people.profile_pic as profile_pic'
			, 'contacts_contacts.first_name as con_first_name'
			, 'contacts_contacts.surname as con_surname'
			,'hr_people.first_name as hr_first_name','hr_people.surname as hr_surname'
			)
            ->leftJoin('contacts_contacts', 'contacts_notes.hr_person_id', '=', 'contacts_contacts.id')
			->leftJoin('hr_people', 'contacts_notes.employee_id', '=', 'hr_people.id')	
            ->where('contacts_notes.company_id', $company->id)
            ->orderBy('contacts_notes.id')
            ->get();
		//return $contactnotes;
		// Client induction
		$ClientInduction = ClientInduction::
            select('client_inductions.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'contact_companies.name as company_name')
			->leftJoin('hr_people', 'client_inductions.create_by', '=', 'hr_people.user_id')
			->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
			->where('client_inductions.company_id', $company->id)
			->get();
		
        $notesStatus = array(1 => 'Supplier', 2 => 'Operations', 3 => 'Finance', 4 => 'After Hours', 5 => 'Sales', 6 => 'Client');
        $communicationmethod = array(1 => 'Telephone', 2 => 'Meeting/Interview', 3 => 'Email', 4 => 'Fax', 4 => 'SMS');
		$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
        
		$data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
		$data['contactsCommunications'] = $contactsCommunications;
        $data['communicationStatus'] = $communicationStatus;
        $data['tasks'] = $tasks;
        $data['taskStatus'] = $taskStatus;
		$data['notesStatus'] = $notesStatus;
        $data['communicationmethod'] = $communicationmethod;
        $data['ClientInduction'] = $ClientInduction;
        $data['contactnotes'] = $contactnotes;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['employees'] = $employees;
        $data['page_title'] = "CRM";
        $data['page_description'] = "View Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'View company', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
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
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Search Company';
        AuditReportsController::store('Notes', 'Notes Updated', " Updated By User", 0);
        return view('contacts.notes')->with($data);
    }

    ####
    public function addnote(Request $request)
    {
        $this->validate($request,[
            'date' => 'required',
            'time' => 'required',
            'notes' => 'required',
            'communication_method' => 'required',
            'employee_id' => 'required',
            'hr_person_id' => 'required',
        ]);

		/*$this->validate($request, [
            // 'date' => 'required',
            'invoice_number' => 'required|unique:vehicle_serviceDetails,invoice_number',
        ]);*/
        $noteData = $request->all();
        unset($noteData['_token']);

        $date = str_replace('/', '-', $noteData['date']);
        $date = strtotime($date);

        $time = str_replace('/', '-', $noteData['time']);
        $time = strtotime($time);

        $contactsnote = new contacts_note();
        $contactsnote->originator_type = !empty($noteData['originator_type']) ? $noteData['originator_type'] : 0;
        $contactsnote->company_id = !empty($noteData['company_id']) ? $noteData['company_id'] : 0;
        $contactsnote->hr_person_id = !empty($noteData['hr_person_id']) ? $noteData['hr_person_id'] : 0;
        $contactsnote->employee_id = !empty($noteData['employee_id']) ? $noteData['employee_id'] : 0;
        $contactsnote->date = $date;
        $contactsnote->time = $time;
        $contactsnote->communication_method = !empty($noteData['communication_method']) ? $noteData['communication_method'] : 0;
        $contactsnote->rensponse = !empty($noteData['rensponse_type']) ? $noteData['rensponse_type'] : 0;
        $contactsnote->notes = !empty($noteData['notes']) ? $noteData['notes'] : 0;
        $contactsnote->next_action = !empty($noteData['next_action']) ? $noteData['next_action'] : 0;
        $contactsnote->save();

        AuditReportsController::store('CRM', 'Note Added', "Added by User", 0);
        return response()->json();
    }
	
	// update note
	public function updateNote(Request $request, contacts_note $note)
    {
        //Validation
        $this->validate($request, [
            'date_update' => 'required',
            'time_update' => 'required',
            'notes_update' => 'required',
            'communication_method_update' => 'required',
            'employee_id_update' => 'required',
            'hr_person_id_update' => 'required',
        ]);
        $noteData = $request->all();
        unset($noteData['_token']);
		
		$date = str_replace('/', '-', $noteData['date_update']);
        $date = strtotime($date);

        $time = str_replace('/', '-', $noteData['time_update']);
        $time = strtotime($time);
		
        $note->originator_type = !empty($noteData['originator_type_update']) ? $noteData['originator_type_update'] : 0;
        $note->hr_person_id = !empty($noteData['hr_person_id_update']) ? $noteData['hr_person_id_update'] : 0;
        $note->employee_id = !empty($noteData['employee_id_update']) ? $noteData['employee_id_update'] : 0;
        $note->date = $date;
        $note->time = $time;
        $note->communication_method = !empty($noteData['communication_method_update']) ? $noteData['communication_method_update'] : 0;
        $note->rensponse = !empty($noteData['rensponse_type_update']) ? $noteData['rensponse_type_update'] : 0;
        $note->notes = !empty($noteData['notes_update']) ? $noteData['notes_update'] : 0;
        $note->next_action = !empty($noteData['next_action_update']) ? $noteData['next_action_update'] : 0;
        $note->update();

        AuditReportsController::store('CRM', 'Note Edited', "Added by User", 0);
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
        $deparments = DB::table('division_level_fives')->where('active', 1)->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$data['page_title'] = "Clients";
        $data['page_description'] = "Edit Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Edit company', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['employees'] = $employees;
        $data['company'] = $company;
        $data['provinces'] = $provinces;
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Search Company';
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
            'account_owners' => 'integer',
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
                $fileName = time() . "_bee_certificate." . $fileExt;
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
                $fileName = time() . "_comp_reg_doc." . $fileExt;
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
                $fileName = time() . "_bee_certificate." . $fileExt;
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
                $fileName = time() . "_comp_reg_doc." . $fileExt;
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
                $fileName = time() . "_supporting_doc." . $fileExt;
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
                    $fileName = time() . "_bee_certificate." . $fileExt;
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
                    $fileName = time() . "_comp_reg_doc." . $fileExt;
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
            ['title' => 'Contacts Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Notes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.contacts_note_report_result')->with($data);
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
            ->get();
        $data['notesStatus'] = $notesStatus;
        $data['companyID'] = $companyID;
        $data['personID'] = $personID;
        $data['Datefrom'] = $Datefrom;
        $data['Dateto'] = $Dateto;
        $data['meetingminutes'] = $meetingminutes;
        $data['page_title'] = "Meetings ";
        $data['page_description'] = "Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Contacts Meeting Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.meeting_minutes_report_result')->with($data);
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
            ->get();

        $data['meetingminutes'] = $meetingminutes;
        $data['page_title'] = "Minutes Neetingd Report";
        $data['page_description'] = "Minutes Neetings Report";
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Minutes Neetings', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.meeting_print')->with($data);
    }
	public function communicationsReport(Request $request)
    {
        $this->validate($request, [
            // // 'name' => 'required',
            // 'date_from' => 'date_format:"d F Y"',
            //'action_date' => 'required',
        ]);

        $communicationsdata = $request->all();
        unset($communicationsdata['_token']);

        $companyID = $communicationsdata['company_id'];
        $personID = $communicationsdata['contact_person_id'];
        $datefrom = $communicationsdata['date_from'];
        $dateto = $communicationsdata['date_to'];
        $Datefrom = str_replace('/', '-', $communicationsdata['date_from']);
        $Datefrom = strtotime($communicationsdata['date_from']);
        $Dateto = str_replace('/', '-', $communicationsdata['date_to']);
        $Dateto = strtotime($communicationsdata['date_to']);
        $communicationStatus = array(1 => 'Email', 2 => 'SMS');
        $contactsCommunications = DB::table('contacts_communications')
            ->select('contacts_communications.*', 'contacts_contacts.first_name', 'contacts_contacts.surname', 
			'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname',
			'contact_companies.name as companyname')
            ->leftJoin('contact_companies', 'contact_companies.id', '=', 'contacts_communications.company_id')
            ->leftJoin('contacts_contacts', 'contacts_contacts.id', '=', 'contacts_communications.contact_id')
            ->leftJoin('hr_people', 'hr_people.id', '=', 'contacts_communications.sent_by')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('contacts_communications.communication_date', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('contacts_communications.company_id', $companyID);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('contacts_communications.contact_id', $personID);
                }
            })
            ->orderBy('contacts_communications.communication_date','contacts_communications.company_id')
            ->get();

        $data['communicationStatus'] = $communicationStatus;
        $data['company_id'] = $companyID;
        $data['contact_id'] = $personID;
        $data['Datefrom'] = $Datefrom;
        $data['Dateto'] = $Dateto;
        $data['contactsCommunications'] = $contactsCommunications;
        $data['page_title'] = "Contacts Communications Report";
        $data['page_description'] = "Communications Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Notes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Contacts', 'View Contacts Communications Search Results', "view Contacts Results", 0);
        return view('contacts.reports.communications_report_result')->with($data);
    }
	// Companies documents reports
	public function expiredDocumentsReport(Request $request)
    {
        $this->validate($request, [
            // // 'name' => 'required',
            // 'date_from' => 'date_format:"d F Y"',
            //'action_date' => 'required',
        ]);

        $communicationsdata = $request->all();
        unset($communicationsdata['_token']);

        $docType = $communicationsdata['doc_type'];
        $companyID = $communicationsdata['company_id'];
        $personID = $communicationsdata['contact_id'];
        $datefrom = $communicationsdata['date_from'];
        $dateto = $communicationsdata['date_to'];
        $Datefrom = str_replace('/', '-', $communicationsdata['date_from']);
        $Datefrom = strtotime($communicationsdata['date_from']);
        $Dateto = str_replace('/', '-', $communicationsdata['date_to']);
        $Dateto = strtotime($communicationsdata['date_to']);
		
        $companyDocs = DB::table('company_documents')
            ->select('company_documents.*',
			'contact_companies.name as companyname'
			,'crm_document_types.name as doc_name')
            ->leftJoin('contact_companies', 'contact_companies.id', '=', 'company_documents.company_id')
            ->leftJoin('crm_document_types', 'company_documents.doc_type', '=', 'crm_document_types.id')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('company_documents.expirydate', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('company_documents.company_id', $companyID);
                }
            })
			->where(function ($query) use ($docType) {
                if (!empty($docType)) {
                    $query->where('company_documents.doc_type', $docType);
                }
            })
            ->orderBy('companyname', 'company_documents.expirydate')
            ->get();

		$contactsDocs = DB::table('client_documents')
            ->select('client_documents.*', 'contacts_contacts.first_name'
					, 'contacts_contacts.surname')
            ->leftJoin('contacts_contacts', 'contacts_contacts.id', '=', 'client_documents.client_id')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('client_documents.expirydate', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('client_documents.client_id', $companyID);
                }
            })
            ->orderBy('client_documents.client_id')
            ->orderBy('client_documents.expirydate')
            ->get();
        //return $contactsDocs;
        $data['doc_type'] = $docType;
        $data['company_id'] = $companyID;
        $data['contact_id'] = $personID;
        $data['Datefrom'] = $Datefrom;
        $data['Dateto'] = $Dateto;
        $data['companyDocs'] = $companyDocs;
        $data['contactsDocs'] = $contactsDocs;
        $data['page_title'] = "Expiring Document Report";
        $data['page_description'] = "Expiring Document Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Document Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Contacts', 'View Contacts Expiring Document Search Results', "view Contacts Results", 0);
        return view('contacts.reports.doc_report')->with($data);
    }
	
	##print reports
    public function printDocsReport(Request $request)
    {
        $communicationsdata = $request->all();
        unset($communicationsdata['_token']);
		
		$docType = $communicationsdata['doc_type'];
        $companyID = $communicationsdata['company_id'];
        $personID = $communicationsdata['contact_id'];
        $Datefrom = str_replace('/', '-', $communicationsdata['date_from']);
        $Datefrom = strtotime($communicationsdata['date_from']);
        $Dateto = str_replace('/', '-', $communicationsdata['date_to']);
        $Dateto = strtotime($communicationsdata['date_to']);
		$companyDocs = DB::table('company_documents')
            ->select('company_documents.*',
			'contact_companies.name as companyname')
            ->leftJoin('contact_companies', 'contact_companies.id', '=', 'company_documents.company_id')
            ->leftJoin('crm_document_types', 'company_documents.doc_type', '=', 'crm_document_types.id')
			->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('company_documents.expirydate', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('company_documents.company_id', $companyID);
                }
            })
			->where(function ($query) use ($docType) {
                if (!empty($docType)) {
                    $query->where('company_documents.doc_type', $docType);
                }
            })
            ->orderBy('companyname', 'company_documents.expirydate')
            ->get();

		$contactsDocs = DB::table('client_documents')
            ->select('client_documents.*', 'contacts_contacts.first_name'
					, 'contacts_contacts.surname')
            ->leftJoin('contacts_contacts', 'contacts_contacts.id', '=', 'client_documents.client_id')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('client_documents.expirydate', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('client_documents.client_id', $companyID);
                }
            })
            ->orderBy('client_documents.client_id')
            ->orderBy('client_documents.expirydate')
            ->get();

        $data['page_title'] = "Expiring Document Report";
        $data['page_description'] = "Expiring Document Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Contacts Document Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');
		$data['companyDocs'] = $companyDocs;
        $data['contactsDocs'] = $contactsDocs;
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.docs_report_print')->with($data);
    }

    ##print reports
    public function printcommunicationsReport(Request $request)
    {
        $communicationsdata = $request->all();
        unset($communicationsdata['_token']);

        $companyID = $communicationsdata['company_id'];
        $personID = $communicationsdata['contact_id'];
        $Datefrom = str_replace('/', '-', $communicationsdata['date_from']);
        $Datefrom = strtotime($communicationsdata['date_from']);
        $Dateto = str_replace('/', '-', $communicationsdata['date_to']);
        $Dateto = strtotime($communicationsdata['date_to']);
        $communicationStatus = array(1 => 'Email', 2 => 'SMS');
        $contactsCommunications = DB::table('contacts_communications')
            ->select('contacts_communications.*', 'contacts_contacts.first_name', 'contacts_contacts.surname', 
			'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname',
			'contact_companies.name as companyname'
			,'crm_document_types.name as doc_name')
            ->leftJoin('contact_companies', 'contact_companies.id', '=', 'contacts_communications.company_id')
            ->leftJoin('contacts_contacts', 'contacts_contacts.id', '=', 'contacts_communications.contact_id')
            ->leftJoin('hr_people', 'hr_people.id', '=', 'contacts_communications.sent_by')
            ->where(function ($query) use ($Datefrom, $Dateto) {
                if ($Datefrom > 0 && $Dateto > 0) {
                    $query->whereBetween('contacts_communications.communication_date', [$Datefrom, $Dateto]);
                }
            })
            ->where(function ($query) use ($companyID) {
                if (!empty($companyID)) {
                    $query->where('contacts_communications.company_id', $companyID);
                }
            })
            ->where(function ($query) use ($personID) {
                if (!empty($personID)) {
                    $query->where('contacts_communications.contact_id', $personID);
                }
            })
            ->orderBy('contacts_communications.communication_date','contacts_communications.company_id')
            ->get();

        $data['contactsCommunications'] = $contactsCommunications;
        $data['communicationStatus'] = $communicationStatus;
        $data['page_title'] = "Contacts Report";
        $data['page_description'] = "Communications Report";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Communications Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.communications_report_print')->with($data);
    }
	//
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
        $data['page_title'] = "Notes Report";
        $data['page_description'] = "Notes Report";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Contacts', 'path' => '/contacts', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Notes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Reports';

		$companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['date'] = date("d-m-Y");
		
        AuditReportsController::store('Contacts', 'View Contacts Search Results', "view Contacts Results", 0);
        return view('contacts.reports.contacts_note_print')->with($data);
    }

    public function viewdocumets(ContactCompany $company)
    {
        $companyID = $company->id;
        $document = contactsCompanydocs::orderby('id', 'asc')->where('company_id', $companyID)->get();
		if (!empty($document)) $document = $document->load('documentType');
        $types = CrmDocumentType::where('status', 1)->orderBy('name', 'asc')->get();
		$data['page_title'] = "Clients";
        $data['page_description'] = "View Company Details";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'View company', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'Search Company';
        $data['types'] = $types;
        $data['company'] = $company;
        $data['document'] = $document;
		AuditReportsController::store('Contacts',"Accessed Documents For Company: $company->name", "Accessed By User", 0);
        return view('contacts.contacts_companydocs')->with($data);
    }

    public function addCompanyDoc(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'supporting_docs' => 'required',
            'doc_type' => 'required',
            'companyID' => 'required',
        ]);

        $contactsCompanydocs = $request->all();
        unset($contactsCompanydocs['_token']);
		if (!empty($contactsCompanydocs['date_from']))
		{
			$Datefrom = $contactsCompanydocs['date_from'] = str_replace('/', '-', $contactsCompanydocs['date_from']);
			$Datefrom = $contactsCompanydocs['date_from'] = strtotime($contactsCompanydocs['date_from']);
		}
		else $Datefrom = 0;
		if (!empty($contactsCompanydocs['exp_date']))
		{
			$Expirydate = $contactsCompanydocs['exp_date'] = str_replace('/', '-', $contactsCompanydocs['exp_date']);
			$Expirydate = $contactsCompanydocs['exp_date'] = strtotime($contactsCompanydocs['exp_date']);
		}
		else $Expirydate = 0;
        $contactsCompany = new contactsCompanydocs();
        $contactsCompany->name = $contactsCompanydocs['name'];
        $contactsCompany->description = $contactsCompanydocs['description'];
        $contactsCompany->date_from = $Datefrom;
        $contactsCompany->expirydate = $Expirydate;
        $contactsCompany->company_id = $contactsCompanydocs['companyID'];
        $contactsCompany->doc_type = $contactsCompanydocs['doc_type'];
        $contactsCompany->status = 1;
        $contactsCompany->save();
		$company = ContactCompany::where('id', $contactsCompanydocs['companyID'])->first(); 
        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time(). "_client_documents." . $fileExt;
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
			 'name_update' => 'required',
//            'name' => 'required|unique:contactsCompanydocs,name',
//            'exp_date' => 'required',
            'doc_type_update' => 'required',
        ]);

        $contactsCompanydocs = $request->all();
        unset($contactsCompanydocs['_token']);

        $Datefrom = $contactsCompanydocs['date_from_update'] = str_replace('/', '-', $contactsCompanydocs['date_from_update']);
        $Datefrom = $contactsCompanydocs['date_from_update'] = strtotime($contactsCompanydocs['date_from_update']);

        $Expirydatet = $contactsCompanydocs['expirydate'] = str_replace('/', '-', $contactsCompanydocs['expirydate']);
        $Expirydate = $contactsCompanydocs['expirydate'] = str_replace('/', '-', $contactsCompanydocs['expirydate']);
        $Expirydate = $contactsCompanydocs['expirydate'] = strtotime($contactsCompanydocs['expirydate']);

        $company->name = $contactsCompanydocs['name_update'];
        $company->description = $contactsCompanydocs['description_update'];
        $company->date_from = $Datefrom;
        $company->expirydate = $Expirydate;
        $company->doc_type = $contactsCompanydocs['doc_type_update'];
        $company->update();

        //Upload supporting document
        if ($request->hasFile('supporting_docs_update')) {
            $fileExt = $request->file('supporting_docs_update')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs_update')->isValid()) {
                $fileName = time() . "_client_documents." . $fileExt;
                $request->file('supporting_docs_update')->storeAs('ContactCompany/company_documents', $fileName);
                //Update file name in the table
                $company->supporting_docs = $fileName;
                $company->update();
            }
        }
		$companyDetails = ContactCompany::where('id', $company->company_id)->first();
        // request fields

        AuditReportsController::store('Contacts', 'Document Updated', "Company Document Updated, Document Name: $contactsCompanydocs[name_update], 
		Document description: $contactsCompanydocs[description_update], Document expiry date: $Expirydatet, Company Name : $companyDetails->name ", 0);
        return response()->json();
    }
	public function saveTask(Request $request, ContactCompany $company)
    {
        $this->validate($request, [       
            'description' => 'required',      
            'due_date' => 'required',      
            'responsible_person' => 'required',   
            'due_time' => 'required',     
            //'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		$taskData = $request->all();
		unset($taskData['_token']);
		if (!empty($taskData['due_date'])) {
            $taskData['due_date'] = str_replace('/', '-', $taskData['due_date']);
            $duedate = strtotime($taskData['due_date']);
        }
        if (!empty($taskData['due_time'])) {
            $taskData['due_time'] = str_replace('/', '-', $taskData['due_time']);
            $duetime = strtotime($taskData['due_time']);
        }
        
        $startDate = strtotime(date('Y-m-d'));
        $employeeID = $taskData['responsible_person'];
        $escalationPerson = HRPerson::where('id', $employeeID)->first();
        $managerID = !empty($escalationPerson->manager_id) ? $escalationPerson->manager_id: 0;
        $taskID = TaskManagementController::store($taskData['description'],$duedate,$startDate,$managerID,$employeeID,2
                    ,0,0,0,0,0,0,0,0,$company->id,0,0,0,$duetime);
		//Upload task doc
		$tasks = EmployeeTasks::where('id',$taskID)->first();
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'xlsx', 'doc', 'xltm']) && $request->file('document')->isValid()) {
                $fileName = time() . "_task_added_doc_" . '.' . $fileExt;
                $request->file('document')->storeAs('tasks', $fileName);
                //Update file name in the employee task table
				$tasks->document_on_task = $fileName;
				$tasks->update();
            }
        }
        $description = $request->input('description');
        AuditReportsController::store('CMR', 'CRM Meeting Task Added', "Added by User", 0);
        return response()->json(['new_task' => $description], 200);
    }
}