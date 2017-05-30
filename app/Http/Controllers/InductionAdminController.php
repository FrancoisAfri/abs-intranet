<?php

namespace App\Http\Controllers;

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
					$escalationID = !empty($inductionData['escalation_id_'.$libraryID]) ? $inductionData['escalation_id_'.$libraryID] : 0;
					$employeeID = !empty($inductionData['employee_id_'.$libraryID]) ? $inductionData['employee_id_'.$libraryID] : 0;
					$orderNo = !empty($inductionData['order_no_'.$libraryID]) ? $inductionData['order_no_'.$libraryID] : 0;
					$uploadRequired = !empty($inductionData['upload_required_'.$libraryID]) ? $inductionData['upload_required_'.$libraryID] : 0;
					TaskManagementController::store($description,$duedate,$startDate,$escalationID,$employeeID,1
					,$orderNo,$libraryID,0,$uploadRequired,0,$ClientInduction->id);
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
			$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
			$tasks = DB::table('employee_tasks')
			->select('employee_tasks.description','employee_tasks.order_no','employee_tasks.notes'
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
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction Search', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
           // ['title' => 'Induction Search Results', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Details', 'active' => 1, 'is_module' => 0]
				];
			$data['active_mod'] = 'Induction';
			$data['active_rib'] = 'Induction Search';
			$data['induction'] = $induction;
			$data['tasks'] = $tasks;
			$data['taskStatus'] = $taskStatus;
			//return $tasks;
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
	
	public function search()
    {
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $data['page_title'] = "Induction Search";
        $data['page_description'] = "Induction Search";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            //['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
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
        $actionFrom = $actionTo = 0;
		$actionDate = $request->action_date;
		$companyID = $request->company_id;
		$inductionTitle = $request->induction_title;
		$createdBy = $request->created_by;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}
		$inductions = DB::table('client_inductions')
		->select('client_inductions.*','hr_people.first_name as firstname', 'hr_people.surname as surname'
		, 'contact_companies.name as comp_name')
		->leftJoin('hr_people', 'client_inductions.create_by', '=', 'hr_people.user_id')
		->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
		/*->where(function ($query) use ($actionFrom, $actionTo) {
		if ($actionFrom > 0 && $actionTo  > 0) {
			$query->whereBetween('client_inductions.create_by', [$actionFrom, $actionTo]);
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
        $data['inductions'] = $inductions;
		$data['page_title'] = "Induction Search Results";
        $data['page_description'] = "Induction Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Induction Search', 'path' => '/induction/search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Induction Search Results', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Induction Search';
		
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Induction Search';
		AuditReportsController::store('Induction', 'View Induction Search Results', "view Induction Results", 0);
        return view('induction.induction_results')->with($data);
    }
}