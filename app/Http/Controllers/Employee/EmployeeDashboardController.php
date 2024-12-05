<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\Mail\SendHRChangesApproval;
use App\Mail\SendRejectChanges;
use App\ManualClockin;
use App\EmployeesTimeAndAttendance;
use App\TrainingDocuments;
use App\DivisionLevel;
use App\EmployeeTasks;
use App\HRPerson;
use App\HrPeopleChange;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveApplicationController;
use App\Models\AssetTransfers;
use App\Models\LicencesAllocation;
use App\Models\StoreRoom;
use App\Models\Video;
use App\modules;
use App\module_access;
use App\Province;
use App\employee_documents;
use App\Traits\BreadCrumpTrait;
use App\User;
use App\doc_type;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class EmployeeDashboardController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	  // clockin Report
    public function clockinReports(Request $request)
    {
        //Inputs
        $employeID = !empty($request['employee_number']) ? $request['employee_number'] : 0;
        $date = !empty($request['action_date']) ? $request['action_date'] : 0;
        $clocktypes = !empty($request['clockin_type']) ? $request['clockin_type'] : 0;
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->get();
		$levels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['employee_number']) ? $request['employee_number'] : 0;
		$employeesCol = array();
		if (!empty($userID))
            $employeesCol = $userID;
        elseif (!empty($div1))
            $employeesCol = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employeesCol = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employeesCol = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employeesCol = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employeesCol = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			//return $employeesCol;
        // get data
        $clockins = ManualClockin::getAllattendance($clocktypes, $employeesCol, $date);
        //return $clockins;
        $data = $this->breadCrump(
            "Time & Attendance",
            "Clockin Report", "fa fa-lock",
            "Employee",
            "Clockin Report",
            "employee/clockin_report",
            "Time & Attendance",
            "Time & Attendance"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['clockins'] = $clockins;
		$data['division_levels'] = $divisionLevels;
		$data['levels'] = $levels;
        $data['employees'] = $employees;

        return view('Employees.clockin_report')->with($data);
    }
	
	 // clockin Report
    public function attendanceReports(Request $request)
    {
        //Inputs
        $employeID = !empty($request['employee_number']) ? $request['employee_number'] : 0;
        $date = !empty($request['date_of_action']) ? $request['date_of_action'] : 0;
        $clocktypes = !empty($request['clockin_type']) ? $request['clockin_type'] : 0;
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->get();
		$levels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->limit(2)->get();
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $div2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $div1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;
		$userID = !empty($request['employee_number']) ? $request['employee_number'] : 0;
		$late_arrival = !empty($request['late_arrival']) ? $request['late_arrival'] : 0;
		$early_clockout = !empty($request['early_clockout']) ? $request['early_clockout'] : 0;
		$absent = !empty($request['absent']) ? $request['absent'] : 0;
		$onleave = !empty($request['onleave']) ? $request['onleave'] : 0;
		$employeesCol = array();
		if (!empty($userID))
            $employeesCol = $userID;
        elseif (!empty($div1))
            $employeesCol = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employeesCol = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employeesCol = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employeesCol = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employeesCol = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			//return $employeesCol;
        // get data
        $attendances = EmployeesTimeAndAttendance::getAllattendance($employeesCol, $date, $late_arrival, $early_clockout, $absent, $onleave);
        //return $attendances;
        $data = $this->breadCrump(
            "Time & Attendance",
            "Clockin Report", "fa fa-lock",
            "Employee",
            "Attendance Report",
            "employee/clockin_report",
            "Time & Attendance",
            "Time & Attendance"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['attendances'] = $attendances;
		$data['division_levels'] = $divisionLevels;
		$data['levels'] = $levels;
        $data['employees'] = $employees;

        return view('Employees.attendance_report')->with($data);
    }
	
	 // clockin Report
    public function employeeDashboard(Request $request)
    {
        //Inputs
		$user = Auth::user()->load('person');
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get(); //->load('divisionLevelGroup');
		
		
		$employeesCol = array();
		if (!empty($userID))
            $employeesCol = $userID;
        elseif (!empty($div1))
            $employeesCol = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($div2))
            $employeesCol = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employeesCol = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employeesCol = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employeesCol = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
			//return $employeesCol;
        // get data
        $clockins = ManualClockin::getAllattendance($clocktypes, $employeesCol, $date);
        //return $clockins;
        $data = $this->breadCrump(
            "Time & Attendance",
            "Clockin Report", "fa fa-lock",
            "Employee",
            "Clockin Report",
            "employee/clockin_report",
            "Time & Attendance",
            "Time & Attendance"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['clockins'] = $clockins;
		$data['division_levels'] = $divisionLevels;
		$data['levels'] = $levels;
        $data['employees'] = $employees;

        return view('Employees.attendance_dashboard')->with($data);
    }
}
