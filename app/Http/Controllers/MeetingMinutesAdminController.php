<?php

namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\ContactCompany;
use App\HRPerson;
use App\ContactPerson;
use App\User;
use App\ClientInduction;
use App\EmployeeTasks;
use App\MeetingMinutes;
use App\MeetingAttendees;
use App\RecurringMeetingsAttendees;
use App\RecurringMeetings;
use App\MeetingsMinutes;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InductionGroupTaskEmail;
use App\Mail\emailMinutes;
use App\Http\Requests;

class meetingMinutesAdminController extends Controller
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
        $questions = DB::table('recurring_meetings')
		->where('status', 1)
		->orderBy('meeting_title', 'asc')
		->get();
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
        $data['page_title'] = "Meeting Minutes";
        $data['page_description'] = "Create Meeting Minutes";
        $data['breadcrumb'] = [['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Create Minutes', 'active' => 1, 'is_module' => 0]];
        $data['questions'] = $questions;
        $data['companies'] = $companies;
        $data['active_mod'] = 'Meeting Minutes';
        $data['active_rib'] = 'Create Minutes';

		AuditReportsController::store('Minutes Meeting', 'View Minutes Meeting', "view Audit", 0);
        return view('meeting_minutes.add_new_meeting')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'meeting_name' => 'required_if:meeting_type,1',       
            'meeting_date' => 'required_if:meeting_type,1',       
            'meeting_location' => 'required_if:meeting_type,1',       
            'meeting_agenda' => 'required_if:meeting_type,1',      
            'recurring_id' => 'required_if:meeting_type,2',      
        ]);
		$MeetingData = $request->all();
		unset($MeetingData['_token']);
		//convert dates to unix time stamp
        if (isset($MeetingData['meeting_date'])) {
            $MeetingData['meeting_date'] = str_replace('/', '-', $MeetingData['meeting_date']);
            $MeetingData['meeting_date'] = strtotime($MeetingData['meeting_date']);
        }
		if ($MeetingData['meeting_type'] && $MeetingData['recurring_id'])
		{
			$recurringMeeting = RecurringMeetings::where('id', $MeetingData['recurring_id'])->first();
			$attendees = RecurringMeetingsAttendees::
			where('meeting_id', $MeetingData['recurring_id'])
			->where('status', 1)
			->get();
			$meeting = new MeetingMinutes();
			$meeting->meeting_name = $recurringMeeting->meeting_title;
			$meeting->meeting_date = strtotime(date("Y-m-d"));
			$meeting->meeting_location = $recurringMeeting->meeting_location;
			$meeting->meeting_agenda = $recurringMeeting->meeting_agenda;
			$meeting->save();
			foreach ($attendees as $attendee)
			{
				$meetingAttendee = new MeetingAttendees();
				$meetingAttendee->employee_id = $attendee->employee_id;
				$meetingAttendee->meeting_id = $meeting->id;
				$meetingAttendee->attendance = 1;
				$meetingAttendee->save();
			}
		}
		else
		{
			if (empty($MeetingData['company_id'])) $MeetingData['company_id'] = 0;
			$meeting = new MeetingMinutes($MeetingData);
			$meeting->save();
		}
		AuditReportsController::store('Minutes Meeting', 'New Meeting Added', "Added By User", 0);
        return redirect('/meeting_minutes/view_meeting/' . $meeting->id . '/view')->with('success_add', "The Meeting has been added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MeetingMinutes $meeting)
    {
        $meeting->load('attendees.attendeesInfo', 'attendees.client','tasksMeeting.employeesTasks','MinutesMeet.minutesPerson', 'MinutesMeet.client', 'company.employees');
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		$attendees = DB::table('meeting_attendees')
		->distinct()
		->select('hr_people.id as hr_id','hr_people.first_name as first_name','hr_people.surname as surname')
		->leftJoin('hr_people', 'meeting_attendees.employee_id', '=', 'hr_people.id')
		->where('hr_people.status', 1)
		->where('meeting_attendees.meeting_id', $meeting->id)
		->orderBy('hr_people.first_name', 'asc')->get();
		//return $meeting;
        $externalAttendees = ($meeting->company && $meeting->company->employees && count($meeting->company->employees) > 0) ? $meeting->company->employees : null;
		$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
		$data['page_title'] = "View Meeting Details";
		$data['page_description'] = "Meeting Minutes Details";
		$data['breadcrumb'] = [
		['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
		['title' => 'Meeting Minutes Search', 'path' => '/meeting_minutes/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
		['title' => 'Meeting Minutes Details', 'active' => 1, 'is_module' => 0]
			];
		$data['active_mod'] = 'Meeting Minutes';
		$data['active_rib'] = 'Search Meeting ';
        $data['meeting'] = $meeting;
		$data['companies'] = $companies;
		$data['employees'] = $employees;
        $data['attendees'] = $attendees;
		$data['externalAttendees'] = $externalAttendees;
		$data['taskStatus'] = $taskStatus;

		AuditReportsController::store('Minutes Meeting', 'Minutes Meeting Details Page Accessed', "Accessed by User", 0);
		return view('meeting_minutes.view_meeting')->with($data);
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
	public function update(Request $request, MeetingMinutes $meeting)
    {

        $this->validate($request, [       
            'meeting_name' => 'required',        
            'meeting_location' => 'required',        
            'meeting_agenda' => 'required',        
            'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		unset($request['_token']);
		$meetingDate = $request->input('meeting_date');
		if (isset($meetingDate)) {
            $meetingDate = str_replace('/', '-', $meetingDate);
			$meetingDate = strtotime($meetingDate);
        }
		$meeting->meeting_name = $request->input('meeting_name');
		$meeting->meeting_location = $request->input('meeting_location');
		$meeting->meeting_agenda = $request->input('meeting_agenda');
		$meeting->meeting_date = $meetingDate;
        $meeting->update();
		$meeting_name = $request->input('meeting_name');
        AuditReportsController::store('Minutes Meeting', 'Meeting Informations Updated', "Updated by User", 0);
		return redirect('/meeting_minutes/view_meeting/' . $meeting->id . '/view')->with('success_add', "The Meeting has been updated successfully.");
	  // return response()->json(['new_meeting_name' => $meeting_name], 200);
    }
	public function updateAttendee(Request $request, MeetingAttendees $attendee)
    {
        $this->validate($request, [       
            //'employee_id' => 'bail|required|integer|min:1',        
            'attendance_edit' => 'bail|required|integer|min:1',        
            'apology' => 'required_if:attendance_edit,2',        
            'attendee_id' => 'bail|required|integer|min:1',        
        ]);
		unset($request['_token']);
        $attendee->employee_id = ($request->input('employee_id') > 0) ? $request->input('employee_id') : null;
		$attendee->client_id = ($request->input('client_id') > 0) ? $request->input('client_id') : null;
		$attendee->attendance = $request->input('attendance_edit');
		$attendee->apology = $request->input('apology');
        $attendee->update();
		$meeting_name = $request->input('employee_id');
        AuditReportsController::store('Minutes Meeting', 'Meeting Attendee Attendance Updated', "Updated by User", 0);
        return response()->json(['new_meeting_name' => $meeting_name], 200);
    }
	public function saveAttendee(Request $request, MeetingMinutes $meeting)
    {
		// Add rule that employee must not be added twice to the same meeting.
		//use Illuminate\Validation\Rule;
		/*'email' => Rule::unique('users')->where(function ($query) {
    $query->where('account_id', 1);
})*/
        $this->validate($request, [       
            'apology' => 'required_if:attendance,2',
            'attendance' => 'bail|required|integer|min:1',      
            //'employee_id' => 'bail|required|integer|min:1',        
            'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		$attendeeData = $request->all();
		unset($attendeeData['_token']);

        $employees = $attendeeData['employee_id'];
        $clients = !empty($attendeeData['client_id']) ? $attendeeData['client_id'] : array();
        foreach ($employees as $empID) {
           $attendee = new MeetingAttendees();
           $attendee->employee_id = $empID;
           $attendee->apology = $attendeeData['apology'];
           $attendee->attendance = $attendeeData['attendance'];
           $attendee->meeting_id = $attendeeData['meeting_id'];
           $attendee->save();
           $attendance = $request->input('attendance'); 
        }

        if (count($clients) > 0) {
            foreach ($clients as $clientID) {
                $attendee = new MeetingAttendees();
                $attendee->client_id = $clientID;
                $attendee->apology = $attendeeData['apology'];
                $attendee->attendance = $attendeeData['attendance'];
                $attendee->meeting_id = $attendeeData['meeting_id'];
                $attendee->save();
                $attendance = $request->input('attendance');
            }
        }
	
        AuditReportsController::store('Minutes Meeting', 'Meeting Attendee Added', "Added by User", 0);
        return response()->json(['new_attendance' => $attendance], 200);
    }
	public function saveMinute(Request $request, MeetingMinutes $meeting)
    {
        $this->validate($request, [       
            'minutes' => 'required',      
            //'employee_id' => 'bail|required|integer|min:1',        
            'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		$minuteData = $request->all();
		unset($minuteData['_token']);
		
		$minute = new MeetingsMinutes($minuteData);
        $minute->save();
		$minutes = $request->input('minutes');
        AuditReportsController::store('Minutes Meeting', 'Meeting minute Added', "Added by User", 0);
        return response()->json(['new_minutes' => $minutes], 200);
    }
	public function saveTask(Request $request, MeetingMinutes $meeting)
    {
        $this->validate($request, [       
            'description' => 'required',      
            'due_date' => 'required',      
            'employee_id' => 'required',   
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
        $employees = $taskData['employee_id'];
        foreach ($employees as $empID) {
        $startDate = strtotime(date('Y-m-d'));
        // $employeeID = $taskData['employee_id'];
        $escalationPerson = HRPerson::where('id', $empID)->first();
        $managerID = !empty($escalationPerson->manager_id) ? $escalationPerson->manager_id: 0;
        TaskManagementController::store($taskData['description'],$duedate,$startDate,$managerID,$empID,2
                    ,0,0,0,0,$meeting->id,0,0,0,0,0,0,0,$duetime);
        $description = $request->input('description');
         }
        AuditReportsController::store('Minutes Meeting', 'Meeting Task Added', "Added by User", 0);
        return response()->json(['new_task' => $description], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
		$data['page_title'] = "Meeting Minutes";
        $data['page_description'] = "Search Meeting Minutes";
        $data['breadcrumb'] = [
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search Minutes', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Meeting Minutes';
        $data['active_rib'] = 'Search Minutes';

		AuditReportsController::store('Minutes Meeting', 'View Meeting Search Page', "Updated by User", 0);
        return view('meeting_minutes.meeting_search')->with($data);
    }
	public function searchResults(Request $request)
    {
        $From = $To = 0;
		$meetingDate = $request->meeting_date;
		$companyID = $request->company_id;
		$meetingTitle = $request->meeting_title;
		$createdBy = $request->created_by;

		if (!empty($meetingDate))
		{
			$dateExplode = explode('-', $meetingDate);
			$From = strtotime($dateExplode[0]);
			$To = strtotime($dateExplode[1]);
		}

		$meetings = DB::table('meeting_minutes')
		->select('meeting_minutes.*','contact_companies.name as compname')
		->leftJoin('contact_companies', 'meeting_minutes.company_id', '=', 'contact_companies.id')
		->where(function ($query) use ($From, $To) {
		if ($From > 0 && $To  > 0) {
			$query->whereBetween('meeting_minutes.meeting_date', [$From, $To]);
		}
		})
		->where(function ($query) use ($meetingTitle) {
			if (!empty($meetingTitle)) {
				$query->where('meeting_minutes.meeting_name', 'ILIKE', "%$meetingTitle%");
			}
		})
		->orderBy('meeting_minutes.meeting_name')
		->get();

         
		$data['page_title'] = "Meeting Minutes";
        $data['page_description'] = "Search Meeting Minutes";
        $data['breadcrumb'] = [
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search Minutes', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Meeting Minutes';
        $data['active_rib'] = 'Search Minutes';
        $data['meetings'] = $meetings;
		AuditReportsController::store('Minutes Meeting', 'View Meeting Search Resutls Page', "View by User", 0);
        return view('meeting_minutes.meeting_results')->with($data);
    }
	public function printMinutes(MeetingMinutes $meeting)
    {
		$minutesMeeting = DB::table('meetings_minutes')
		->select('meetings_minutes.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'meetings_minutes.employee_id', '=', 'hr_people.id')
		->where('meetings_minutes.meeting_id', $meeting->id)
		->orderBy('meetings_minutes.id')
		->get();
		
		$employeesTasks = DB::table('employee_tasks')
		->select('employee_tasks.*'
				,'hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->where('employee_tasks.task_type', '=', 2)
		->where('employee_tasks.meeting_id', $meeting->id)
		->orderBy('employee_tasks.employee_id')
		->orderBy('employee_tasks.order_no')
		->get();
		
		$data['page_title'] = "Meeting Minutes";
        $data['page_description'] = "Meeting Minutes";
        $data['breadcrumb'] = [
		['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
		['title' => 'Meeting Minutes Search', 'path' => '/meeting_minutes/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
		['title' => 'Meeting Minutes Details', 'active' => 1, 'is_module' => 0]
			];
        $data['active_mod'] = 'Meeting Minutes';
		$data['active_rib'] = 'Search Meeting ';
		
		$user = Auth::user()->load('person');
		$companyDetails = CompanyIdentity::first();
        $data['company_name'] = $companyDetails->full_company_name;
        $logo = $companyDetails->company_logo;
        $data['company_logo'] = url('/') . Storage::disk('local')->url("logos/$logo");
		$data['date'] = date("d-m-Y");
        $data['minutesMeeting'] = $minutesMeeting;
        $data['employeesTasks'] = $employeesTasks;
        $data['meeting'] = $meeting;
		$data['user'] = $user;
		AuditReportsController::store('Minutes Meeting', 'Minutes Meeting Details Print', "Accessed by User", 0);
        return view('meeting_minutes.minutes_print')->with($data);
    }
	public function emailMinutes(MeetingMinutes $meeting)
    {
		if (!empty($meeting->id))
		{
			$attendees = DB::table('meeting_attendees')
			->distinct()
			->where('meeting_attendees.meeting_id', $meeting->id)
			->orderBy('meeting_attendees.employee_id', 'asc')
			->get();
			
			$minutesMeeting = DB::table('meetings_minutes')
			->select('meetings_minutes.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
			->leftJoin('hr_people', 'meetings_minutes.employee_id', '=', 'hr_people.id')
			->where('meetings_minutes.meeting_id', $meeting->id)
			->orderBy('meetings_minutes.id')
			->first();
			
			# Send Email to attendees if minutes exist
			if (!empty($minutesMeeting->id))
			{
				foreach($attendees as $attendee)
				{
                    $employee = null;
                    if ($attendee->employee_id > 0) $employee = HRPerson::where('id', $attendee->employee_id)->first();
                    elseif ($attendee->client_id > 0) $employee = ContactPerson::where('id', $attendee->client_id)->first();
                    if ($employee) Mail::to($employee->email)->send(new emailMinutes($employee,$meeting));
				}
			}
			else
				return back()->with('success_error', "No Minutes have been added to this meetiing. Please start by adding minutes.");
			
		}
		else
			return back()->with('success_error', "Meeting Details not found. Please contact your system administrator");
		AuditReportsController::store('Minutes Meeting', 'email minutes to attendees', "Email Minutes", 0);
        return back()->with('success_email', "Meeting Minutes Emails Has Successfully Been Sent To Attendees.");
    }
}