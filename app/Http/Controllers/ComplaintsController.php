<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\HRPerson;
use App\Province;
use App\ComplaintsCompliments;
use App\User;
use App\ContactPerson;
use App\Mail\SendComplaintsToManager;
use App\Mail\SendComplaintsToEmployee;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ComplaintsController extends Controller
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
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		
        $data['page_title'] = "Complaints & Compliments";
        $data['page_description'] = "Search";
        $data['breadcrumb'] = [
            ['title' => 'Complaints & Compliments', 'path' => '/complaints/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Complaints & Compliments Search', 'active' => 1, 'is_module' => 0]
        ];
		$data['employees'] = $employees;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Search';
        return view('complaints.complaint_search')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$data['page_title'] = "Complaints & Compliments";
        $data['page_description'] = "Add A New Complaints & Compliments";
        $data['breadcrumb'] = [
            ['title' => 'Complaints & Compliments', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add Complaints & Compliments', 'active' => 1, 'is_module' => 0]
        ];
        $data['employees'] = $employees;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Add Compliments / Complaints';
        AuditReportsController::store('Compliments & Complaints', 'Compliments & Complaints Create Page Accessed', 'Added By Accessed', 0);
		return view('complaints.add_complaints')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form data
        $this->validate($request, [
            'date_complaint_compliment' => 'required',
            'office' => 'required',
            'company_id' => 'required',
            'employee_id' => 'required',
            'summary_complaint_compliment' => 'required',
        ]);

        $comData = $request->all();
		$person = Auth::user()->load('person');
        //Exclude empty fields from query
        foreach ($comData as $key => $value) {
            if (empty($comData[$key])) {
                unset($comData[$key]);
            }
        }
		// convert date
		$date = str_replace('/', '-', $comData['date_complaint_compliment']);
        $date = strtotime($date);
        //Insert Data

        $complaint = new ComplaintsCompliments();
        $complaint->office = !empty($comData['office']) ? $comData['office'] : '';
        $complaint->error_type = !empty($comData['error_type']) ? $comData['error_type'] : '';
        $complaint->pending_reason = !empty($comData['pending_reason']) ? $comData['pending_reason'] : '';
        $complaint->summary_corrective_measure = !empty($comData['summary_corrective_measure']) ? $comData['summary_corrective_measure'] : '';
        $complaint->summary_complaint_compliment = !empty($comData['summary_complaint_compliment']) ? $comData['summary_complaint_compliment'] : '';
        $complaint->type = !empty($comData['type']) ? $comData['type'] : 0;
        $complaint->type_complaint_compliment = !empty($comData['type_complaint_compliment']) ? $comData['type_complaint_compliment'] : 0;
        $complaint->employee_id = !empty($comData['employee_id']) ? $comData['employee_id'] : 0;
        $complaint->company_id = !empty($comData['company_id']) ? $comData['company_id']:0;
        $complaint->responsible_party = !empty($comData['responsible_party']) ? $comData['responsible_party']:0;
        $complaint->supplier = !empty($comData['supplier']) ? $comData['supplier']:0;
        $complaint->status = 1;
        $complaint->created_by = $person->person->id;
        $complaint->date_complaint_compliment = $date;
        $complaint->date_created = time();
        $complaint->client_id = !empty($comData['contact_person_id']) ? $comData['contact_person_id']:0;
        $complaint->save();
		// return 
		if ($complaint->type == 1) $text = "Complaints";
		else $text = "Compliments";
		// send email to employee and the manager
		$employeeDetails = HRPerson::where('id', $comData['employee_id'])->where('status',1)
            ->select('manager_id','first_name','surname','email')->first();
        $managerID = !empty($employeeDetails->manager_id) ? $employeeDetails->manager_id : 0;
		$managerDetails = HRPerson::where('id', $managerID)->where('status',1)
                ->select('first_name', 'email')
                ->first();
		// send email to manager
		if (!empty($managerDetails->email))
			Mail::to($managerDetails->email)->send(new SendComplaintsToManager($managerDetails->first_name, $text, $employeeDetails->first_name." ".$employeeDetails->surname));
		// send emal to employee
		if (!empty($employeeDetails->email))
			Mail::to($employeeDetails->email)->send(new SendComplaintsToEmployee($employeeDetails->first_name, $text));		
		
		AuditReportsController::store('Compliments & Complaints', 'New Compliments & Complaints Added', 'Added By User', 0);
        return redirect('/complaints/view/' . $complaint->id)->with('success_add', "The $text has been added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ComplaintsCompliments $complaint, $back='')
    {
        if (!empty($complaint)) $complaint = $complaint->load('employees','createdBy','company','client');
		//return $complaint;
		if ($complaint->type == 1) $text = "Complaint";
		else $text = "Compliment"; 
		$data['back'] = '';
		if (!empty($back) && empty($app)) $data['back'] = "/complaints/search";
		$typeComplaints = array(1=>"Client", 2=>"Supplier", 3=>"Internal");
		$reponsible = array(1=>"Employee", 2=>"Supplier", 3=>"Client");
		$statuses = array(1=>"Open", 2=>"Closed");
        $data['typeComplaints'] = $typeComplaints;
        $data['statuses'] = $statuses;
        $data['reponsible'] = $reponsible;
        $data['text'] = $text;
        $data['complaint'] = $complaint;
		$data['page_title'] = "View $text Details";
        $data['page_description'] = "$text Information";
        $data['breadcrumb'] = [
            ['title' => 'Complaints&Compliments', 'path' => '/complaints/search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'View Complaints & Compliments Details', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Compliments & Complaints', 'View Compliments & Complaints Infos', 'Accessed By User', 0);
        return view('complaints.view_complaint')->with($data);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ComplaintsCompliments $complaint)
    {
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		if ($complaint->type == 1) $text = "Complaint";
		else $text = "Compliment"; 
        $data['page_title'] = "Edit $text Details";
        $data['page_description'] = "$text Information";
        $data['breadcrumb'] = [
            ['title' => 'Compliments & Complaints', 'path' => '/complaints/search', 'icon' => 'fa fa-address-book', 'active' => 0, 'is_module' => 1],
            ['title' => 'View details', 'active' => 1, 'is_module' => 0]
        ];
		$data['text'] = $text;
        $data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'search';
        $data['complaint'] = $complaint;
        $data['employees'] = $employees;
        $data['companies'] = $companies;
        AuditReportsController::store('Compliments & Complaints', 'Compliments & Complaints Edited', "Edited By User", 0);
        return view('complaints.edit_complaint')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComplaintsCompliments $complaint)
    {
		//validate form data
        $this->validate($request, [
            'date_complaint_compliment' => 'required',
            'office' => 'required',
            'company_id' => 'required',
            'employee_id' => 'required',
            'summary_complaint_compliment' => 'required',
        ]);
         $comData = $request->all();
		$person = Auth::user()->load('person');
        //Exclude empty fields from query
        foreach ($comData as $key => $value) {
            if (empty($comData[$key])) {
                unset($comData[$key]);
            }
        }
		// convert date
		$date = str_replace('/', '-', $comData['date_complaint_compliment']);
        $date = strtotime($date);
        //Update Data

        $complaint->office = !empty($comData['office']) ? $comData['office'] : '';
        $complaint->error_type = !empty($comData['error_type']) ? $comData['error_type'] : '';
        $complaint->pending_reason = !empty($comData['pending_reason']) ? $comData['pending_reason'] : '';
        $complaint->summary_corrective_measure = !empty($comData['summary_corrective_measure']) ? $comData['summary_corrective_measure'] : '';
        $complaint->summary_complaint_compliment = !empty($comData['summary_complaint_compliment']) ? $comData['summary_complaint_compliment'] : '';
        $complaint->type = !empty($comData['type']) ? $comData['type'] : 0;
        $complaint->type_complaint_compliment = !empty($comData['type_complaint_compliment']) ? $comData['type_complaint_compliment'] : 0;
        $complaint->employee_id = !empty($comData['employee_id']) ? $comData['employee_id'] : 0;
        $complaint->company_id = !empty($comData['company_id']) ? $comData['company_id']:0;
        $complaint->responsible_party = !empty($comData['responsible_party']) ? $comData['responsible_party']:0;
        $complaint->status = !empty($comData['status']) ? $comData['status']:0;;
        $complaint->created_by = $person->person->id;
        $complaint->date_complaint_compliment = $date;
        $complaint->date_created = time();
        $complaint->client_id = !empty($comData['contact_person_id']) ? $comData['contact_person_id']:0;
        $complaint->update();
		
        if ($complaint->type == 1) $text = "Complaints";
		else $text = "Compliments";
		AuditReportsController::store('Compliments & Complaints', 'New Compliments & Complaints Updated', 'Updated By User', 0);
        return redirect('/complaints/view/' . $complaint->id)->with('success_add', "The $text has been Updated successfully");    
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
	
	/// Search Results
	public function searchResults(Request $request)
    {
		$dateFrom = $dateTo = 0;
		$date = $request->date_complaint_compliment;
		if (!empty($date))
		{
			$startExplode = explode('-', $date);
			$dateFrom = strtotime($startExplode[0]);
			$dateTo = strtotime($startExplode[1]);
		}
		$status =$request->status; 
		$employeeId =$request->employee_id; 
		$typeComplaintCompliment =$request->type_complaint_compliment; 
		$type =$request->type; 
		$contactPersonId =$request->contact_person_id; 
		$companyId =$request->company_id; 
		$office =$request->office; 
		$complaints = ComplaintsCompliments::
		where(function ($query) use ($office) {
			if (!empty($office)) {
				$query->where('office', 'ILIKE', "%$office%");
			}
		})
		->where(function ($query) use ($employeeId) {
			if (!empty($employeeId)) {
				$query->where('employee_id', $employeeId);
			}
		})
		->where(function ($query) use ($typeComplaintCompliment) {
			if (!empty($typeComplaintCompliment)) {
				$query->where('type_complaint_compliment', $typeComplaintCompliment);
			}
		})
		->where(function ($query) use ($status) {
			if (!empty($status) && $status == 1) $query->where('status',1);
			elseif (!empty($status) && $status == 2) $query->where('status',2);
		})
		->where(function ($query) use ($dateFrom, $dateTo) {
			if ($dateFrom > 0 && $dateTo  > 0) {
				$query->whereBetween('complaints_compliments.date_complaint_compliment', [$dateFrom, $dateTo]);
			}
		})
		->where(function ($query) use ($type) {
			if (!empty($type)) {
				$query->where('type', $type);
			}
		})
		->where(function ($query) use ($contactPersonId) {
			if (!empty($contactPersonId)) {
				$query->where('client_id', $contactPersonId);
			}
		})
		->where(function ($query) use ($companyId) {
			if (!empty($companyId)) {
				$query->where('company_id', $companyId);
			}
		})
		->orderBy('complaints_compliments.date_complaint_compliment')
		->get();
		if (!empty($complaints)) $complaints = $complaints->load('employees','createdBy','company','client');
		//return $complaints;
		$data['complaints'] = $complaints;
        $data['page_title'] = "Compliments & Complaints Search Results";
        $data['page_description'] = "Compliments & Complaints Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Compliments & Complaints', 'path' => '/complaints/search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Results', 'path' => '/complaints/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Search';
        return view('complaints.complaints_search_results')->with($data);
    }
	//// report index
	public function reports()
    {
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		
        $data['page_title'] = "Complaints & Compliments";
        $data['page_description'] = "Report";
        $data['breadcrumb'] = [
            ['title' => 'Complaints & Compliments', 'path' => '/complaints/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Complaints & Compliments Report', 'active' => 1, 'is_module' => 0]
        ];
		$data['employees'] = $employees;
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Reports';
        return view('complaints.report_index')->with($data);
    }
	/// Reports Search Results
	public function reportSearchResults(Request $request)
    {
		$dateFrom = $dateTo = 0;
		$date = $request->date_complaint_compliment;
		if (!empty($date))
		{
			$startExplode = explode('-', $date);
			$dateFrom = strtotime($startExplode[0]);
			$dateTo = strtotime($startExplode[1]);
		}
		$status =$request->status; 
		$employeeId =$request->employee_id; 
		$typeComplaintCompliment =$request->type_complaint_compliment; 
		$type =$request->type; 
		$contactPersonId =$request->contact_person_id; 
		$companyId =$request->company_id; 
		$office =$request->office; 
		$complaints = ComplaintsCompliments::
		where(function ($query) use ($office) {
			if (!empty($office)) {
				$query->where('office', 'ILIKE', "%$office%");
			}
		})
		->where(function ($query) use ($employeeId) {
			if (!empty($employeeId)) {
				$query->where('employee_id', $employeeId);
			}
		})
		->where(function ($query) use ($typeComplaintCompliment) {
			if (!empty($typeComplaintCompliment)) {
				$query->where('type_complaint_compliment', $typeComplaintCompliment);
			}
		})
		->where(function ($query) use ($status) {
			if (!empty($status) && $status == 1) $query->where('status',1);
			elseif (!empty($status) && $status == 2) $query->where('status',2);
		})
		->where(function ($query) use ($dateFrom, $dateTo) {
			if ($dateFrom > 0 && $dateTo  > 0) {
				$query->whereBetween('complaints_compliments.date_complaint_compliment', [$dateFrom, $dateTo]);
			}
		})
		->where(function ($query) use ($type) {
			if (!empty($type)) {
				$query->where('type', $type);
			}
		})
		->where(function ($query) use ($contactPersonId) {
			if (!empty($contactPersonId)) {
				$query->where('client_id', $contactPersonId);
			}
		})
		->where(function ($query) use ($companyId) {
			if (!empty($companyId)) {
				$query->where('company_id', $companyId);
			}
		})
		->orderBy('complaints_compliments.date_complaint_compliment')
		->get();
		if (!empty($complaints)) $complaints = $complaints->load('employees','createdBy','company','client');
		$typeComplaints = array(1=>"Client", 2=>"Supplier", 3=>"Internal");
		$reponsible = array(1=>"Employee", 2=>"Supplier", 3=>"Client");
		$statuses = array(1=>"Open", 2=>"Closed");
		$data['typeComplaints'] = $typeComplaints;
        $data['statuses'] = $statuses;
        $data['reponsible'] = $reponsible;
		$data['complaints'] = $complaints;
        $data['page_title'] = "Compliments & Complaints Report Results";
        $data['page_description'] = "Compliments & Complaints Report Results";
        $data['breadcrumb'] = [
            ['title' => 'Compliments & Complaints', 'path' => '/complaints/reports', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Report Results', 'path' => '/complaints/reports', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Compliments & Complaints';
        $data['active_rib'] = 'Reports';
        return view('complaints.complaints_reports_results')->with($data);
    }
}
