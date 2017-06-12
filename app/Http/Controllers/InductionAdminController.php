<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\HRPerson;
use App\User;
use App\ClientInduction;
use App\EmployeeTasks;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\TaskManagementController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InductionAdminController extends Controller
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
		$libraries = DB::table('task_libraries')->orderBy('order_no', 'asc')->get();
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
        $data['page_title'] = "Induction";
        $data['page_description'] = "Create New Induction";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction', 'path' => '/induction/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Create Induction', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Create Induction';
		
		
		$data['users'] = $employees;
		$data['companies'] = $companies;
		$data['libraries'] = $libraries;
		AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('induction.add_induction')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'company_id' => 'integer',
        ]);
		
		$inductionData = $request->all();

		//Exclude empty fields from query
		foreach ($inductionData as $key => $value)
		{
			if (empty($inductionData[$key])) {
				unset($inductionData[$key]);
			}
		}

		$user = Auth::user();
        $companyID = (int) $inductionData['company_id'];
        $ClientInduction = new ClientInduction();
		$ClientInduction->induction_title = $inductionData['title'];
		$ClientInduction->company_id = $companyID;
		$ClientInduction->status = 1;
		$ClientInduction->create_by = $user->id;
		$ClientInduction->save();
		foreach ($inductionData as $key => $sValue) 
		{
			if (strlen(strstr($key, 'selected')))
			{
				$aValue = explode("_", $key);
				$unit = $aValue[0];
				$libraryID = $aValue[1];
				if (($unit == 'selected'))
				{
					$description = !empty($inductionData['description_'.$libraryID]) ? $inductionData['description_'.$libraryID] : '';
					$duedate = !empty($inductionData['due_date_'.$libraryID]) ? $inductionData['due_date_'.$libraryID] : '';
					$startDate = !empty($inductionData['start_date_'.$libraryID]) ? $inductionData['start_date_'.$libraryID] : '';
					$administratorID = !empty($inductionData['administrator_id'.$libraryID]) ? $inductionData['administrator_id'.$libraryID] : 0;
					$employeeID = !empty($inductionData['employee_id_'.$libraryID]) ? $inductionData['employee_id_'.$libraryID] : 0;
					$orderNo = !empty($inductionData['order_no_'.$libraryID]) ? $inductionData['order_no_'.$libraryID] : 0;
					$uploadRequired = !empty($inductionData['upload_required_'.$libraryID]) ? $inductionData['upload_required_'.$libraryID] : 0;
					$escalationPerson = HRPerson::where('id', $employeeID)->first();
					$escalationPerson->manager_id = !empty($escalationPerson->manager_id) ? $escalationPerson->manager_id: 0;
					TaskManagementController::store($description,$duedate,$startDate,$escalationPerson->manager_id,$employeeID,1
					,$orderNo,$libraryID,0,$uploadRequired,0,$ClientInduction->id, $administratorID);
				}
			}
		}
		AuditReportsController::store('Induction', 'induction Added', "Induction Title: $ClientInduction->title", 0);
		return redirect('/induction/' . $ClientInduction->id . '/view')->with('success_add', "The Induction has been added successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
	
    public function show(ClientInduction $induction)
    {
        if ($induction->status == 1) 
		{
			$user = Auth::user()->load('person');
			$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
			$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
			$tasks = DB::table('employee_tasks')
			->select('employee_tasks.id as task_id','employee_tasks.employee_id','employee_tasks.upload_required'
			,'employee_tasks.description','employee_tasks.order_no','employee_tasks.notes'
			,'employee_tasks.status','employee_tasks.date_completed'
			,'hr_people.first_name as hr_fist_name','hr_people.surname as hr_surname'
			, 'employee_tasks_documents.document as emp_doc')
			->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
			->leftJoin('employee_tasks_documents', 'employee_tasks_documents.task_id', '=', 'employee_tasks.id')
			->where('employee_tasks.induction_id', $induction->id)
			->orderBy('employee_tasks.order_no')
			->get();
			$induction->load('ClientName')->first();
			$data['page_title'] = "View Induction Details";
			$data['page_description'] = "Induction Details";
			$data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction Search', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
           // ['title' => 'Induction Search Results', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Details', 'active' => 1, 'is_module' => 0]
				];
			$data['active_mod'] = 'Induction';
			$data['active_rib'] = 'Induction Search';
			$data['induction'] = $induction;
			$data['user'] = $user;
			$data['employees'] = $employees;
			$data['tasks'] = $tasks;
			$data['taskStatus'] = $taskStatus;
			//return $data;
			AuditReportsController::store('Induction', 'Induction Details Page Accessed', "Accessed by User", 0);
			return view('induction.view_induction')->with($data);
		}
		else return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	public function search()
    {
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $data['page_title'] = "Induction Search";
        $data['page_description'] = "Induction Search";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            //['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Induction Search';
		
		$data['users'] = $users;
		$data['companies'] = $companies;
		AuditReportsController::store('Induction', 'View Induction Search', "Accessed By User", 0);
        return view('induction.induction_search')->with($data);
    }
	
	public function searchResults(Request $request)
    {
        $completionFrom = $completionTo = 0;
		$actionDate = $request->action_date;
		$companyID = $request->company_id;
		$inductionTitle = $request->induction_title;
		$createdBy = $request->created_by;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$completionFrom = strtotime($startExplode[0]);
			$completionTo = strtotime($startExplode[1]);
		}
		$inductions = DB::table('client_inductions')
		->select('client_inductions.*','hr_people.first_name as firstname', 'hr_people.surname as surname'
		, 'contact_companies.name as comp_name')
		->leftJoin('hr_people', 'client_inductions.create_by', '=', 'hr_people.user_id')
		->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
		/*->where(function ($query) use ($completionFrom, $completionTo) {
		if ($completionFrom > 0 && $completionTo  > 0) {
			$query->whereBetween('client_inductions.create_by', [$completionFrom, $completionTo]);
		}
		})*/
		->where(function ($query) use ($companyID) {
		if (!empty($companyID)) {
			$query->where('client_inductions.company_id', $companyID);
		}
		})
		->where(function ($query) use ($createdBy) {
		if (!empty($createdBy)) {
			$query->where('client_inductions.create_by', $createdBy);
		}
		})
		->where(function ($query) use ($inductionTitle) {
			if (!empty($inductionTitle)) {
				$query->where('client_inductions.induction_title', 'ILIKE', "%$inductionTitle%");
			}
		})
		->orderBy('client_inductions.induction_title')
		->get();
		//return $inductions;
		//$data['contract_doc'] = (!empty($contractDoc)) ? Storage::disk('local')->url("programmes/$contractDoc") : '';
        $data['inductions'] = $inductions;
		$data['page_title'] = "Induction Search Results";
        $data['page_description'] = "Induction Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction Search', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Search Results', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Induction Search';
		
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Induction Search';
		AuditReportsController::store('Induction', 'View Induction Search Results', "view Induction Results", 0);
        return view('induction.induction_results')->with($data);
    }
	
	public function reports()
    {
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $data['page_title'] = "Induction Reports";
        $data['page_description'] = "Induction Reports";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Reports';
		
		$data['users'] = $users;
		$data['companies'] = $companies;
		AuditReportsController::store('Induction', 'View Induction Reports', "Accessed By User", 0);
        return view('induction.reports.reports')->with($data);
    }
	// draw tasks report acccording to search criteria
	public function getReport(Request $request)
    {
		$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
		$completionFrom = $completionTo = $creationFrom = $creationTo = 0;
		$completionDate = $request->completion_date;
		$creationDate = $request->creation_date;
		$companyID = $request->company_id;
		$employeeID = $request->employee_id;
		$inductionTitle = $request->induction_title;
		$status = $request->status;
		if (!empty($completionDate))
		{
			$completionExplode = explode('-', $completionDate);
			$completionFrom = strtotime($completionExplode[0]);
			$completionTo = strtotime($completionExplode[1]);
		}
		if (!empty($creationDate))
		{
			$creationExplode = explode('-', $creationDate);
			$creationFrom = strtotime($creationExplode[0]);
			$creationTo = strtotime($creationExplode[1]);
		}
		$employeesTasks = DB::table('employee_tasks')
		->select('employee_tasks.*','client_inductions.induction_title'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname'
				, 'contact_companies.name as comp_name')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->leftJoin('client_inductions', 'employee_tasks.induction_id', '=', 'client_inductions.id')
		->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
		->where(function ($query) use ($completionFrom, $completionTo) {
		if ($completionFrom > 0 && $completionTo  > 0) {
			$query->whereBetween('employee_tasks.date_completed', [$completionFrom, $completionTo]);
		}
		})
		->where(function ($query) use ($creationFrom, $creationTo) {
		if ($creationFrom > 0 && $creationTo  > 0) {
			$query->whereBetween('employee_tasks.due_date', [$creationFrom, $creationTo]);
		}
		})
		->where(function ($query) use ($status) {
		if (!empty($status)) {
			$query->where('employee_tasks.status', $status);
		}
		})
		->where(function ($query) use ($companyID) {
		if (!empty($companyID)) {
			$query->where('client_inductions.company_id', $companyID);
		}
		})
		->where(function ($query) use ($employeeID) {
		if (!empty($employeeID)) {
			$query->where('employee_tasks.employee_id', $employeeID);
		}
		})
		->where(function ($query) use ($inductionTitle) {
			if (!empty($inductionTitle)) {
				$query->where('client_inductions.induction_title', 'ILIKE', "%$inductionTitle%");
			}
		})
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
        $data['induction_title'] = $request->induction_title;
        $data['completion_date'] = $request->completion_date;
        $data['creation_date'] = $request->creation_date;
        $data['company_id'] = $request->company_id;
        $data['employee_id'] = $request->employee_id;
        $data['status'] = $request->status;
        $data['employeesTasks'] = $employeesTasks;
        $data['taskStatus'] = $taskStatus;
		$data['page_title'] = "Tasks Report";
        $data['page_description'] = "Tasks Report";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction', 'path' => '/induction/reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Report';
		AuditReportsController::store('Induction', 'View Induction Search Results', "view Induction Results", 0);
        return view('induction.reports.induction_results')->with($data);
    }
	// Print tasks report acccording to sent criteria
	public function printreport(Request $request)
    {
		$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
		$completionFrom = $completionTo = $creationFrom = $creationTo = 0;
		$completionDate = $request->completion_date;
		$creationDate = $request->creation_date;
		$companyID = $request->company_id;
		$employeeID = $request->employee_id;
		$inductionTitle = $request->induction_title;
		$status = $request->status;
		if (!empty($completionDate))
		{
			$completionExplode = explode('-', $completionDate);
			$completionFrom = strtotime($completionExplode[0]);
			$completionTo = strtotime($completionExplode[1]);
		}
		if (!empty($creationDate))
		{
			$creationExplode = explode('-', $creationDate);
			$creationFrom = strtotime($creationExplode[0]);
			$creationTo = strtotime($creationExplode[1]);
		}
		$employeesTasks = DB::table('employee_tasks')
		->select('employee_tasks.*','client_inductions.induction_title'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname'
				, 'contact_companies.name as comp_name')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->leftJoin('client_inductions', 'employee_tasks.induction_id', '=', 'client_inductions.id')
		->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
		->where(function ($query) use ($completionFrom, $completionTo) {
		if ($completionFrom > 0 && $completionTo  > 0) {
			$query->whereBetween('employee_tasks.date_completed', [$completionFrom, $completionTo]);
		}
		})
		->where(function ($query) use ($creationFrom, $creationTo) {
		if ($creationFrom > 0 && $creationTo  > 0) {
			$query->whereBetween('employee_tasks.due_date', [$creationFrom, $creationTo]);
		}
		})
		->where(function ($query) use ($status) {
		if (!empty($status)) {
			$query->where('employee_tasks.status', $status);
		}
		})
		->where(function ($query) use ($companyID) {
		if (!empty($companyID)) {
			$query->where('client_inductions.company_id', $companyID);
		}
		})
		->where(function ($query) use ($employeeID) {
		if (!empty($employeeID)) {
			$query->where('employee_tasks.employee_id', $employeeID);
		}
		})
		->where(function ($query) use ($inductionTitle) {
			if (!empty($inductionTitle)) {
				$query->where('client_inductions.induction_title', 'ILIKE', "%$inductionTitle%");
			}
		})
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
        $data['employeesTasks'] = $employeesTasks;
        $data['taskStatus'] = $taskStatus;
		$data['page_title'] = "Tasks Report";
        $data['page_description'] = "Tasks Report";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction', 'path' => '/induction/reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Report';
		
		$user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::first();
        $data['company_name'] = $companyDetails->full_company_name;
        $logo = $companyDetails->company_logo;
        $data['company_logo'] = url('/') . Storage::disk('local')->url("logos/$logo");
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Induction', 'Print with Search Results', "Print with Results", 0);
        return view('induction.reports.induction_print')->with($data);
    }
	/*public function excel() {

    // Execute the query used to retrieve the data. In this example
    // we're joining hypothetical users and payments tables, retrieving
    // the payments table's primary key, the user's first and last name, 
    // the user's e-mail address, the amount paid, and the payment
    // timestamp.

    $payments = Payment::join('users', 'users.id', '=', 'payments.id')
        ->select(
          'payments.id', 
          DB::raw("concat(users.first_name, ' ', users.last_name) as `name`"), 
          'users.email', 
          'payments.total', 
          'payments.created_at')
        ->get();

    // Initialize the array which will be passed into the Excel
    // generator.
    $paymentsArray = []; 

    // Define the Excel spreadsheet headers
    $paymentsArray[] = ['id', 'customer','email','total','created_at'];

    // Convert each member of the returned collection into an array,
    // and append it to the payments array.
    foreach ($payments as $payment) {
        $paymentsArray[] = $payment->toArray();
    }

    // Generate and return the spreadsheet
    Excel::create('payments', function($excel) use ($invoicesArray) {

        // Set the spreadsheet title, creator, and description
        $excel->setTitle('Payments');
        $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
        $excel->setDescription('payments file');

        // Build the spreadsheet, passing in the payments array
        $excel->sheet('sheet1', function($sheet) use ($paymentsArray) {
            $sheet->fromArray($paymentsArray, null, 'A1', false, false);
        });

    })->download('xlsx');
}*/
}