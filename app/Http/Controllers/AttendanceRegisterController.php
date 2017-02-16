<?php

namespace App\Http\Controllers;

use App\educator;
use App\Learner;
use App\public_reg;
use App\Registration;
use App\AttendanceRegister;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('auth');
    }
	
    public function index()
    {
		$programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();

        $data['page_title'] = "Attendance Register";
        $data['page_description'] = "Mark Attendance for a Learner, Educator or Member of the General Public";
        $data['breadcrumb'] = [
            ['title' => 'Attendance Register', 'path' => '/education/attendance', 'icon' => 'fa fa-adn', 'active' => 0, 'is_module' => 1],
            ['title' => 'Mark Attendance', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Attendance Register';
        $data['active_rib'] = 'Mark Attendance';
        $data['programmes'] = $programmes;
		/*DB::table('public_holidays')->insert([
			['day' => 1482789600, 'year' => 0, 'holiday_name' => 'Public Holiday'],
			['day' => 1293228000, 'year' => 0, 'holiday_name' => 'Christmas Day'],
			['day' => 1285279200, 'year' => 0, 'holiday_name' => 'Heritage Day'],
			['day' => 1293314400, 'year' => 0, 'holiday_name' => 'Day of Goodwill'],
			['day' => 1269122400, 'year' => 0, 'holiday_name' => 'Human Rights Day'],
			['day' => 1272319200, 'year' => 0, 'holiday_name' => 'Freedom Day'],
			['day' => 1272664800, 'year' => 0, 'holiday_name' => 'Workers Day'],
			['day' => 1276639200, 'year' => 0, 'holiday_name' => 'Youth Day'],
			['day' => 1281304800, 'year' => 0, 'holiday_name' => 'National Womens Day'],
			['day' => 1292450400, 'year' => 0, 'holiday_name' => 'Day of Reconciliation'],
			['day' => 1262296800, 'year' => 0, 'holiday_name' => 'New Year Day'],
			['day' => 1399413600, 'year' => 2014, 'holiday_name' => 'Voting Day']
		]);
		die('insert done');*/
        AuditReportsController::store('Attendance Register', 'Search Clients', "Actioned By User", 0);
        return view('attendance.load_clients')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchResults(Request $request)
    {
       //Validation
        $validator = Validator::make($request->all(), [
            'registration_type' => 'required',
            'programme_id' => 'required',
            'project_id' => 'required',
            'registration_year' => 'required',
            'course_type' => 'required',
            'registration_semester' => 'required_if:course_type,2',
        ]);
        if ($validator->fails()) {
            return redirect('/education/attendance')
                ->withErrors($validator)
                ->withInput();
        }
		
        $attendanceData = $request->all();
		$regType = (int) $attendanceData['registration_type'];
        $programmeID = (int) $attendanceData['programme_id'];
        $projectID = (int) $attendanceData['project_id'];
        $regYear = (int) $attendanceData['registration_year'];
        $courseType = (int) $attendanceData['course_type'];
        $regSemester = ($attendanceData['registration_semester'] != '') ? (int) $attendanceData['registration_semester'] : 0;
        $registrations = Registration::where('registration_type', $regType)
            ->where('programme_id', $programmeID)
            ->where('project_id', $projectID)
            ->where('registration_year', $regYear)
            ->where('course_type', $courseType)
            ->where(function ($query) use ($courseType, $regSemester) {
                if ($courseType == 2 && $regSemester > 0) {
                    $query->where('registration_semester', $regSemester);
                }
            })
            ->get();
        if ($registrations) $registrations->load('client', 'programme', 'project', 'subjects');
        $data['page_title'] = "Attendance Register";
        $data['page_description'] = "Mark Attendance for a Learner, Educator or Member of the General Public";
        $data['breadcrumb'] = [
            ['title' => 'Attendance Register', 'path' => '/education/attendance', 'icon' => 'fa fa-adn', 'active' => 0, 'is_module' => 1],
            ['title' => 'Mark Attendance', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Attendance Register';
        $data['active_rib'] = 'Mark Attendance';
        $data['registrations'] = $registrations;
		$dayOfTheWeek = date('w');
		$weekStarted = strtotime(date("Y-m-d 00:00:00", strtotime("-$dayOfTheWeek days", strtotime(date("d M Y"))))) + 86400; # subtract current day of the week from current date
		$sweekStarted = date('d M Y', $weekStarted);
		$weekEnds = strtotime(date("Y-m-d 23:59:59", strtotime("+4 days", $weekStarted)));
		$sweekEnds = date('d M Y', $weekEnds);
		$hourDiff	=round(abs($weekStarted - $weekEnds) / (60*60*24),0) + 1;
		$span = $hourDiff + 1;
		if ($regType == 1) $learnerID = 'learner_id';
		elseif ($regType == 2) $learnerID = 'educator_id'; 
		elseif ($regType == 3) $learnerID = 'gen_public_id'; 
		$year = date('Y');
		$publicHolidays = DB::table('public_holidays')
			->where('year', 0)
			->orWhere('year', $year)
			->get();				
		$data['span'] = $span;
		$data['sweekEnds'] = $sweekEnds;
		$data['sweekStarted'] = $sweekStarted;
		$data['weekStarted'] = $weekStarted;
		$data['weekEnds'] = $weekEnds;
		$data['registration_type'] = $regType;
		$data['programme_id'] = $programmeID;
		$data['project_id'] = $projectID;
		$data['registration_year'] = $regYear;
		$data['course_type'] = $courseType;
		$data['regSemester'] = $regSemester;
		$data['learnerID'] = $learnerID;
		$data['publicHolidays'] = $publicHolidays;
		
		AuditReportsController::store('Attendance Register', 'Attendance Register Accessed', "Actioned By User", 0);
		
        return view('attendance.show_clients')->with($data);
    }  
	// Save attendance register
	public function store(Request $request)
    {
		$attendanceData = $request->all();

		//Exclude empty fields from query
		foreach ($attendanceData as $key => $value)
		{
			if (empty($attendanceData[$key])) {
				unset($attendanceData[$key]);
			}
		}
		//return $attendanceData;
		$regType = (int) $attendanceData['registration_type'];
        $programmeID = (int) $attendanceData['programme_id'];
        $projectID = (int) $attendanceData['project_id'];
        $regYear = (int) $attendanceData['registration_year'];
        $learnerID =  $attendanceData['learnerID'];
        $courseType = (int) $attendanceData['course_type'];
        $regSemester = !empty($attendanceData['registration_semester']) ? (int) $attendanceData['registration_semester'] : 0;

		foreach ($attendanceData as $key => $sValue) 
		{
			if (strlen(strstr($key, 'attendance')))
			{
				$aValue = explode("_", $key);
				$unit = $aValue[0];
				$userID = $aValue[1];
				$date = $aValue[2];
				if (($unit == 'attendance') && !empty($date) && !empty($userID))
				{
					$attendance = !empty($attendanceData['attendance_'.$userID.'_'.$date]) ? $attendanceData['attendance_'.$userID.'_'.$date] : 0;
					$registrations = AttendanceRegister::where('registration_type', $regType)
					->where('programme_id', $programmeID)
					->where('project_id', $projectID)
					->where('registration_year', $regYear)
					->where('course_type', $courseType)
					->where('date_attended', $date)
					->where(''.$learnerID.'', $userID)
					->where(function ($query) use ($courseType, $regSemester) {
						if ($courseType == 2 && $regSemester > 0) {
							$query->where('registration_semester', $regSemester);
						}
					})
					->get()
					->first();
					if (!empty($registrations))
					{
						DB::table('attendance_register')
						->where('id', $registrations->id)
						->update(['attendance' => $attendance]);
						AuditReportsController::store('Attendance Register', 'Attendance Register Updated', "Actioned By User", 0);
					}
					else
					{
						$attendanceRegister = new AttendanceRegister();
						$attendanceRegister->attendance = $attendance;
						$attendanceRegister->registration_type = $regType;
						$attendanceRegister->programme_id = $programmeID;
						$attendanceRegister->project_id = $projectID;
						$attendanceRegister->registration_year = $regYear;
						$attendanceRegister->course_type = $courseType;
						$attendanceRegister->registration_semester = $regSemester;
						if ($learnerID == 'learner_id') $attendanceRegister->learner_id = $userID;
						elseif ($learnerID == 'educator_id') $attendanceRegister->educator_id = $userID;
						elseif ($learnerID == 'gen_public_id') $attendanceRegister->gen_public_id = $userID;
						$attendanceRegister->date_attended = $date;
						$attendanceRegister->save();
						AuditReportsController::store('Attendance Register', 'Attendance Register Saved', "Actioned By User", 0);
					}
				}
			}
		}
    }
}
