<?php
namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\ContactCompany;
use App\HRPerson;
use App\User;
use App\RecurringMeetingsAttendees;
use App\RecurringMeetings;
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

class RecurringMeetingsController extends Controller
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
		$recurringMeetings = RecurringMeetings::orderBy('meeting_title', 'asc')->get();
		if (!empty($recurringMeetings)) $recurringMeetings->load('recurringAttendees');
		
        // 
        $data['page_title'] = "Recurring Meeting";
        $data['page_description'] = "Recurring Meeting";
        $data['breadcrumb'] = [
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/recurring', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/recurring', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Recurring Meetings', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Meeting Minutes';
        $data['active_rib'] = 'Recurring Meetings';
        $data['recurringMeetings'] = $recurringMeetings;
		
		
		AuditReportsController::store('Minutes Meeting', 'View Recurring Meetings', "view Recurring Meetings", 0);
        return view('meeting_minutes.recurring_meeting')->with($data);
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
            'meeting_title' => 'required',       
            'meeting_location' => 'required',       
            'meeting_agenda' => 'required',      
        ]);
		$MeetingData = $request->all();
		unset($MeetingData['_token']);
		//convert dates to unix time stamp
        /*if (isset($MeetingData['meeting_date'])) {
            $MeetingData['meeting_date'] = str_replace('/', '-', $MeetingData['meeting_date']);
            $MeetingData['meeting_date'] = strtotime($MeetingData['meeting_date']);
        }*/
		$meeting = new RecurringMeetings($MeetingData);
        $meeting->status = 1;
        $meeting->save();
		AuditReportsController::store('Minutes Meeting', 'New Recurring Meeting Added', "Added By User", 0);
        return response()->json(['new_attendance' => $meeting->meeting_title], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RecurringMeetings $recurring)
    {
        $recurring->load('recurringAttendees.attendeesDetails');
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		//return $recurring;
		$data['page_title'] = "View Recurring Meeting Details";
		$data['page_description'] = "Recurring Meeting Details";
		$data['breadcrumb'] = [
		['title' => 'Recurring Meeting Minutes', 'path' => '/meeting_minutes/recurring', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
		['title' => 'Recurring Meeting Minutes Details', 'active' => 1, 'is_module' => 0]
			];
		$data['active_mod'] = 'Meeting Minutes';
		$data['active_rib'] = 'Recurring Meetings';
		$data['recurring'] = $recurring;
		$data['employees'] = $employees;

		AuditReportsController::store('Minutes Meeting', 'Recurring Minutes Meeting Details Page Accessed', "Accessed by User", 0);
		return view('meeting_minutes.view_recurring_meeting')->with($data);
    }
	# Act/deac meeting
	public function meetingAct(RecurringMeetings $recurring) 
	{
		if ($recurring->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$recurring->status = $stastus;	
		$recurring->update();
		AuditReportsController::store('Minutes Meeting', "Recurring Meeting Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	# Act/deac attendees
	public function attendeeAct(RecurringMeetingsAttendees $recurring) 
	{
		if ($recurring->status == 1) $stastus = 0;
		else $stastus = 1;
		/*echo $recurring;
		die ;*/
		$recurring->status = $stastus;	
		$recurring->update();
		AuditReportsController::store('Minutes Meeting', "Recurring Meeting Attendee Status Changed: $stastus", "Edited by User", 0);
		return back();
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
    public function update(Request $request, RecurringMeetings $recurring)
    {
         $this->validate($request, [       
            'meeting_title' => 'required',        
            'meeting_location' => 'required',        
            'meeting_agenda' => 'required',        
            'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		unset($request['_token']);
		$recurring->meeting_title = $request->input('meeting_title');
		$recurring->meeting_location = $request->input('meeting_location');
		$recurring->meeting_agenda = $request->input('meeting_agenda');
        $recurring->update();
		$meetingTitle = $request->input('meeting_title');
        AuditReportsController::store('Minutes Meeting', 'Recurring Meeting Informations Updated', "Updated by User", 0);
        return response()->json(['new_meeting_title' => $meetingTitle], 200);
    }
	
	public function saveRecurringAttendee(Request $request)
    {
        $this->validate($request, [
            'employee_id.*' => 'bail|required|integer|min:1',        
            'meeting_id' => 'bail|required|integer|min:1',        
        ]);
		$attendeeData = $request->all();
		unset($attendeeData['_token']);
        foreach ($attendeeData['employee_id'] as $employeeID) {
			$previous = RecurringMeetingsAttendees::where('employee_id', '=',$employeeID)
			->where('meeting_id', '=',$attendeeData['meeting_id'])
			->first();
			if (empty($previous)) {
			   $attendee = new RecurringMeetingsAttendees();
				$attendee->employee_id = $employeeID;
				$attendee->meeting_id = $attendeeData['meeting_id'];
				$attendee->status = 1;
				$attendee->save();
			}
        }
		$attendance = $attendeeData['meeting_id'];
        AuditReportsController::store('Minutes Meeting', 'Recurring Meeting Attendee Added', "Added by User", 0);
        return response()->json(['new_attendance' => $attendance], 200);
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