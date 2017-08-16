<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\CompanyIdentity;
use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use App\ContactCompany;
use App\EmployeeTasks;
use App\System;
use App\EmployeeTasksDocuments;
use App\Mail\EmployeesTasksMail;
use App\Mail\NextTaskNotifications;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskManagementController extends Controller
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
        $companies = ContactCompany::where('status', 2)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $data['page_title'] = "Task Search";
        $data['page_description'] = "Task Search";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/search_task', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Tasks Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Search Task';
		
		$data['users'] = $users;
		$data['companies'] = $companies;
		AuditReportsController::store('Task Management', 'View Search Search Page', "Accessed By User", 0);
        return view('tasks.search')->with($data);
    }
	// Add task
	public function addTask()
    {
        $companies = ContactCompany::where('status', 2)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
                $data['page_title'] = "Add Task";
        $data['page_description'] = "Add Task";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/search_task', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add Task', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Add Task';
		
		$data['users'] = $users;
		$data['companies'] = $companies;
		AuditReportsController::store('Task Management', 'Add Task Page', "Accessed By User", 0);
        return view('tasks.add_new_task')->with($data);
    }
	# Start task
	public function startTask(EmployeeTasks $task) 
	{
		$user = Auth::user()->load('person');
		/*if ($task->is_dependent == 1 && !empty($task->induction_id))
		{
			$order = $task->order_no - 1;
			$oldTask = DB::table('employee_tasks')
			->select('employee_tasks.status','employee_tasks.order_no','employee_tasks.id')
			->where('employee_tasks.induction_id', $task->induction_id)
			->where('employee_tasks.order_no', $order)
			->orderBy('employee_tasks.order_no')
			->first();
			
			if (!empty($oldTask->status) && $oldTask->status != 4) 
				return redirect('/')->with('error_starting', "You can not start this task, The task it depends on have not been completed yet.");
		}*/
		$OnProgress = DB::table('employee_tasks')
		->select('employee_tasks.id')
		->where('employee_tasks.employee_id', $user->person->id)
		->where('employee_tasks.status', 2)
		->first();
		if (!empty($OnProgress->id))
			return redirect('/')->with('error_starting', "You can not start this task, You have another task in progess.");
		$stastus = 2;
		$task->status = $stastus;	
		$task->date_started = time();
		$task->update();
		AuditReportsController::store('Task Management', "Task Started", "Edited by User", 0);
		return back();
    }
	# Pause task
	public function pauseTask(EmployeeTasks $task) 
	{
		$stastus = 3;
		$task->status = $stastus;	
		$task->date_paused =  time();
		$task->update();
		AuditReportsController::store('Task Management', "Task Paused", "Edited by User", 0);
		return back();
    }
	# End task
	public function endTask(Request $request) 
	{
		$this->validate($request, [
            'task_id' => 'bail|required|numeric|min:1',
            'employee_id' => 'bail|required|numeric|min:1',
			 'document' => 'required_if:upload_required,2',
        ]);
		$user = Auth::user();
		$endData = $request->all();

        //Exclude empty fields from query
        foreach ($endData as $key => $value)
        {
            if (empty($endData[$key])) {
                unset($endData[$key]);
            }
        }
        //Upload task doc
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'xlsx', 'doc', 'xltm']) && $request->file('document')->isValid()) {
                $fileName = $endData['employee_id'] . "_task_doc_" . '.' . $fileExt;
                $request->file('document')->storeAs('tasks', $fileName);
                //Update file name in the appraisal_perks table
                $endtask = new EmployeeTasksDocuments();
				$endtask->task_id = $endData['task_id'];
				$endtask->employee_id = $endData['employee_id'];
				$endtask->added_by = $user->id;
				$endtask->status = 1;
				$endtask->document = $fileName;
				$endtask->save();
            }
        }
		# update Task
        $dateCompleted = strtotime(date('Y-m-d'));
		$notes = !empty($endData['notes']) ? $endData['notes'] : '';
		DB::table('employee_tasks')
		->where('id', $endData['task_id'])
		->update([
			'status' => 4,
			'date_completed' => $dateCompleted,
			'notes' => $notes
		]);
		/*$task = EmployeeTasks::where('id', $endData['task_id'])->first();
		if (!empty($task->is_dependent) && $task->is_dependent == 1&& !empty($task->induction_id))
		{
			$next = $task->order_no + 1;
			$nextTask = DB::table('employee_tasks')
			->select('employee_tasks.employee_id')
			->where('employee_tasks.induction_id', $task->induction_id)
			->where('employee_tasks.order_no', $next)
			->first();
			if(!empty($nextTask->employee_id))
			{
				# Send Email to employee
				$employee = HRPerson::where('id', $nextTask->employee_id)->first();
				Mail::to($employee->email)->send(new NextTaskNotifications($employee, $task));
				
			}
		}*/
		AuditReportsController::store('Task Management', "Task Ended", "Edited by User", 0);
		return response()->json(['employee_id' => $endData['employee_id']], 200);
    }
	# Check task
	public function checkTask(Request $request) 
	{
		$checkData = $request->all();
        //Exclude empty fields from query
        foreach ($checkData as $key => $value)
        {
            if (empty($checkData[$key])) {
                unset($checkData[$key]);
            }
        }
		# update Task
        $dateChecked = strtotime(date('Y-m-d'));
		$notes = !empty($checkData['notes']) ? $checkData['notes'] : '';
		DB::table('employee_tasks')
		->where('id', $checkData['task_id'])
		->update([
			'checked' => 1,
			'check_date' => $dateChecked,
			'check_comments' => $notes
		]);
		AuditReportsController::store('Task Management', "Task Checked", "Checked by User", 0);
	return response()->json(['employee_id' => $checkData['task_id']], 200);
    }
	# Add task
	public function addNewTask(Request $request) 
	{
		$this->validate($request, [
            'employee_id' => 'bail|required|integer|min:1',       
            'description' => 'required',     
        ]);
		$AddData = $request->all();
        //Exclude empty fields from query
        foreach ($AddData as $key => $value)
        {
            if (empty($AddData[$key])) {
                unset($AddData[$key]);
            }
        }
		
		if (!empty($AddData['start_date'])) {
            $AddData['start_date'] = str_replace('/', '-', $AddData['start_date']);
            $startDate = strtotime($AddData['start_date']);
        }
		if (!empty($AddData['due_date'])) {
            $AddData['due_date'] = str_replace('/', '-', $AddData['due_date']);
            $duedate = strtotime($AddData['due_date']);
        }
		//return $AddData;
		# Add Task 
		$employeeID = $AddData['employee_id'];
		$companyID = !empty($AddData['company_id']) ? $AddData['company_id'] : 0;
		$managerDuration = $AddData['manager_duration'];
		$escalationPerson = HRPerson::where('id', $employeeID)->first();
		$managerID = !empty($escalationPerson->manager_id) ? $escalationPerson->manager_id: 0;			
		$description = $AddData['description'];
		
		TaskManagementController::store($description,$duedate,$startDate,$managerID,$employeeID,3
					,0,0,0,0,0,0,0,0,$companyID, $managerDuration);
		AuditReportsController::store('Task Management', "Task Added", "Added By User", 0);
	return Back();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public static function store($description='',$duedate=0,$startDate=0,$escalationID=0,$employeeID=0,$taskType=0
	,$orderNo=0,$libraryID=0,$priority=0,$uploadRequired=0,$meetingID=0,$inductionID=0,$administratorID=0
	,$checkByID=0,$clientID=0,$managerDuration=0 , $helpDeskID = 0 , $ticketID = 0)
    {
		//convert dates to unix time stamp
        /*if (!empty($duedate)) {
            $duedate = str_replace('/', '-', $duedate);
            $intduedate = strtotime($duedate);
        }
		else $intduedate = 0;
		if (!empty($startDate)) {
            $startDate = str_replace('/', '-', $startDate);
            $intstartDate = strtotime($startDate);
        }
		else $intstartDate = 0;*/
	//echo $managerDuration;
	//die();
		$user = Auth::user();
		$EmployeeTasks = new EmployeeTasks();
		$EmployeeTasks->induction_id = $inductionID;
		$EmployeeTasks->meeting_id = $meetingID;
		$EmployeeTasks->is_dependent = ($taskType == 1) ? 1 : 0;
		$EmployeeTasks->order_no = $orderNo;
		$EmployeeTasks->added_by = $user->id;
		$EmployeeTasks->escalation_id = $escalationID;
		$EmployeeTasks->upload_required = $uploadRequired;
		$EmployeeTasks->priority = $priority;
		$EmployeeTasks->status = 1;
		$EmployeeTasks->ticket_id = $ticketID;
		$EmployeeTasks->task_type = $taskType;
		$EmployeeTasks->employee_id = $employeeID;
		$EmployeeTasks->library_id = $libraryID;
		$EmployeeTasks->description = $description;
		$EmployeeTasks->due_date = $duedate;
		$EmployeeTasks->start_date = $startDate;
		$EmployeeTasks->administrator_id = $administratorID;
		$EmployeeTasks->check_by_id = $checkByID;
		$EmployeeTasks->client_id = $clientID;
		$EmployeeTasks->manager_duration = $managerDuration;
		$EmployeeTasks->helpdesk_id = $helpDeskID;
		//Save task
        $EmployeeTasks->save();
		if (empty($inductionID))
		{
			# Send Email to employee
			$employee = HRPerson::where('id', $employeeID)->first();
			Mail::to($employee->email)->send(new EmployeesTasksMail($employee));
		}
		AuditReportsController::store('Task Management', 'Task Successfully Added', "Added by user", 0);

		



		//if ($taskType == 3)
			//return redirect('/education/activity/' . $activity->id . '/view')->with('success_add', "The task has been added successfully");
    }
	
	public function update(Request $request, EmployeeTasks $task)
    {
		//convert dates to unix time stamp
        $this->validate($request, [       
            'description' => 'required',       
            'order_no' => 'bail|required|integer|min:1',       
            'upload_required' => 'bail|required|integer|min:1',       
            'employee_id' => 'bail|required|integer|min:1',        
            'administrator_id' => 'bail|required|integer|min:1',        
        ]);
		
		$task->order_no = $request->input('order_no');
		$task->upload_required = $request->input('upload_required');
		$task->employee_id = $request->input('employee_id');
		$task->description = $request->input('description');
		$task->administrator_id = $request->input('administrator_id');

        $task->update();
		$description = $request->input('description');
        AuditReportsController::store('Task Management', 'task Informations Updated', "Updated by User", 0);
        return response()->json(['new_description' => $description], 200);
    }
	
	// Search results
	public function searchResults(Request $request)
    {
		$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
		$completionFrom = $completionTo = $creationFrom = $creationTo = 0;
		$completionDate = $request->completion_date;
		$dueDate = $request->due_date;
		$employeeID = $request->employee_id;
		$taskNumber = $request->task_number;
		$status = $request->status;
		if (!empty($completionDate))
		{
			$completionExplode = explode('-', $completionDate);
			$completionFrom = strtotime($completionExplode[0]);
			$completionTo = strtotime($completionExplode[1]);
		}
		if (!empty($dueDate))
		{
			$creationExplode = explode('-', $dueDate);
			$creationFrom = strtotime($creationExplode[0]);
			$creationTo = strtotime($creationExplode[1]);
		}
		$employeesTasks = DB::table('employee_tasks')
		->select('employee_tasks.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
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
		->where(function ($query) use ($employeeID) {
		if (!empty($employeeID)) {
			$query->where('employee_tasks.employee_id', $employeeID);
		}
		})
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
        $data['employeesTasks'] = $employeesTasks;
        $data['taskStatus'] = $taskStatus;
		$data['page_title'] = "Tasks Search Results";
        $data['page_description'] = "Show Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/search_task', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Task Management', 'path' => '/tasks/search_task', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Task Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Search Task';
		AuditReportsController::store('Task Management', 'View Task Search Results', "view Task Results", 0);
        return view('tasks.tasks_search_results')->with($data);
    }
	// Report Search
	public function report()
    {
		$companies = ContactCompany::where('status', 2)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
        $data['page_title'] = "Task Report";
        $data['page_description'] = "Task Report";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/task_report', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            //['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Tasks Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Report';
		
		$data['users'] = $users;
		$data['companies'] = $companies;
		AuditReportsController::store('Task Management', 'View Report Search Page', "Accessed By User", 0);
        return view('tasks.reports.reports')->with($data);
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
		$meetingName = $request->meeting_name;
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
		->select('employee_tasks.*','meeting_minutes.meeting_name'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->leftJoin('meeting_minutes', 'employee_tasks.meeting_id', '=', 'meeting_minutes.id')
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
		->where(function ($query) use ($employeeID) {
		if (!empty($employeeID)) {
			$query->where('employee_tasks.employee_id', $employeeID);
		}
		})
		->where(function ($query) use ($meetingName) {
			if (!empty($meetingName)) {
				$query->where('meeting_minutes.meeting_name', 'ILIKE', "%$meetingName%");
			}
		})
		->where('employee_tasks.task_type', '=', 2)
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
        $data['meeting_name'] = $request->meeting_name;
        $data['completion_date'] = $request->completion_date;
        $data['creation_date'] = $request->creation_date;
        $data['employee_id'] = $request->employee_id;
        $data['status'] = $request->status;
        $data['employeesTasks'] = $employeesTasks;
        $data['taskStatus'] = $taskStatus;
		$data['page_title'] = "Tasks Report";
        $data['page_description'] = "Tasks Report";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/task_report', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Task Management', 'path' => '/tasks/task_report', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Task Management Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Report';
		AuditReportsController::store('Task Management', 'View Task Search Results', "view Task Results", 0);
        return view('tasks.reports.tasks_results')->with($data);
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
		$meetingName = $request->meeting_name;
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
		->select('employee_tasks.*','meeting_minutes.meeting_name'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->leftJoin('meeting_minutes', 'employee_tasks.meeting_id', '=', 'meeting_minutes.id')
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
		->where(function ($query) use ($employeeID) {
		if (!empty($employeeID)) {
			$query->where('employee_tasks.employee_id', $employeeID);
		}
		})
		->where(function ($query) use ($meetingName) {
			if (!empty($meetingName)) {
				$query->where('meeting_minutes.meeting_name', 'ILIKE', "%$meetingName%");
			}
		})
		->where('employee_tasks.task_type', '=', 2)
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
        $data['employeesTasks'] = $employeesTasks;
        $data['taskStatus'] = $taskStatus;
		$data['page_title'] = "Tasks Report";
        $data['page_description'] = "Tasks Report";
        $data['breadcrumb'] = [
            ['title' => 'Task Management', 'path' => '/tasks/task_report', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Task Management', 'path' => '/tasks/task_report', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Task Management Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Task Management';
        $data['active_rib'] = 'Report';
		
		$user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::first();
        $data['company_name'] = $companyDetails->full_company_name;
        $logo = $companyDetails->company_logo;
        $data['company_logo'] = url('/') . Storage::disk('local')->url("logos/$logo");
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Induction', 'Print with Search Results', "Print with Results", 0);
        return view('tasks.reports.tasks_print')->with($data);
    }
	function convertsecond($seconds)
	{
		$hours = '';
		$secondsInAMinute = 60;
		$secondsInAnHour = 60 * $secondsInAMinute;
		if ($seconds >= $secondsInAnHour)
		{
			$hours = floor($seconds / $secondsInAnHour);
			$minuteSeconds = ($seconds - ($hours * $secondsInAnHour));
			$minutes = ceil($minuteSeconds / 60);
		}
		else $minutes = floor($seconds / $secondsInAMinute);

		// Return the final
		$time  = !empty($hours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . 'h' : str_pad($hours, 2, '0', STR_PAD_BOTH). 'h';
		$time .= !empty($minutes) ? str_pad($minutes, 2, '0', STR_PAD_LEFT) .  '' : str_pad($minutes, 2, '0', STR_PAD_BOTH);
		return $time;
	}
	function convertMinutes($minutes)
	{
		$hours = floor($minutes/60);
		$minute = ($minutes - ($hours * 60));
		$minute = ceil($minute);
		
		// Return the final
		$time  = !empty($hours) ? str_pad($hours, 2, '0', STR_PAD_LEFT) . 'h' : str_pad($hours, 2, '0', STR_PAD_BOTH). 'h';
		$time .= !empty($minute) ? str_pad($minute, 2, '0', STR_PAD_LEFT) .  '' : str_pad($minute, 2, '0', STR_PAD_BOTH);
	}
}