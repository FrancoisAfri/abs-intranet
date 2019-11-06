<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveHistoryAuditController;
use App\Http\Requests;
use App\LeaveType;
use App\Users;
use App\DivisionLevel;
use App\leave_custom;
use App\leave_configuration;
use App\HRPerson;
use App\hr_person;
use App\modules;
use App\leave_credit;
use App\leave_history;
use App\type_profile;
use App\leave_profile;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Excel;
class LeaveSetupController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

     */
    public function __construct() {

        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setuptypes() {
        //
        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');

        //return $leave_customs;
        $leaveTypes = DB::table('leave_types')->orderBy('name','asc')->get();
        $employees = HRPerson::where('status', 1)->get();
        $data['page_title'] = "leave Types";
        $data['page_description'] = "leave types";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/types', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Leave Types ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave Types';
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leave_customs'] = $leave_customs;

        AuditReportsController::store('Leave Management', 'Leave Type Page Accessed', "Accessed By User", 0);

        return view('leave.leave_types')->with($data);
    }

    //#leave allocation
    public function show() {

        $data['page_title'] = "Manage Leave";
        $data['page_description'] = "Allocate leave types ";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/Allocate_leave_types', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Allocate leave ', 'active' => 1, 'is_module' => 0]
        ];
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        $leave_credit = DB::table('leave_credit')->orderBy('id', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $leveType = LeaveType::where('status', 1)->get()->load(['leave_profle' => function($query) {
                $query->orderBy('name', 'asc');
            }]);

        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');


        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Manage Leave';
        $data['leaveTypes'] = $leaveTypes;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
        $data['leave_credit'] = $leave_credit;
        $data['leave_profile'] = $leave_profile;
        AuditReportsController::store('Leave Management', 'Leave Management Page Accessed', "Accessed By User", 0);
        return view('leave.leave_allocation')->with($data);
    }

    public function showSetup(Request $request) {
        $leaveTypes = LeaveType::orderBy('name', 'asc')->get()->load(['leave_profle' => function($query) {
                $query->orderBy('id', 'asc');
            }]);

        $type_profile = DB::table('type_profile')->orderBy('min', 'asc')->get();
        $leave_configuration = DB::table('leave_configuration')->where("id", 1)->get()->first();
        $employees = HRPerson::where('status', 1)->get();

        $data['page_title'] = "leave type";
        $data['page_description'] = "leave set up ";
        $data['breadcrumb'] = [
                ['title' => 'Leave Management', 'path' => '/leave/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'setup';
        $data['leave_configuration'] = $leave_configuration;
        $data['leaveTypes'] = $leaveTypes;
        $data['type_profile'] = $type_profile;
        $data['employees'] = $employees;
        if (isset($person['leave_profile'])) {
            $person['leave_profile'] = (int) $person['leave_profile'];
        }
        AuditReportsController::store('Leave Management', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('leave.setup')->with($data);
    }

    public function addAnnual(Request $request, $id) {
        $this->validate($request, [
            'number_of_days_annual' => 'required|numeric',
                // 'leave_type' => 'bail|required',
                // 'division_level_2' => 'bail|required',
                // 'division_level_1' => 'bail|required',
                // 'hr_person_id' => 'bail|required',
                // 'resert_days' => 'bail|required',
        ]);
        $lateData = $request->all();
        unset($lateData['_token']);

        $row = leave_configuration::count();
        if ($row > 0) {
            DB::table('leave_configuration')->where('id', $id)->update($lateData);
        } else {
            $leave_configuration = new leave_configuration($lateData);
            $leave_configuration->save();
        }
        return response()->json();
    }

    public function addSick(Request $request, $id) {

        $this->validate($request, [
            'number_of_days_sick' => 'required|numeric',
        ]);
        $lateData = $request->all();
        unset($lateData['_token']);

        $row = leave_configuration::count();
        if ($row > 0) {
            DB::table('leave_configuration')->where('id', $id)->update($lateData);
        } else {
            $leave_configuration = new leave_configuration($lateData);
            $leave_configuration->save();
        }
        AuditReportsController::store('Leave Management', 'leave custom Added', "Actioned Performed By User", 0);
        return response()->json();
    }

    public function Adjust(Request $request, HRPerson $person, LeaveType $lev) {
        $this->validate($request, [
                 'division_level_5' => 'required',
                 'leave_types_id' => 'required',
                 'adjust_days' => 'required',
        ]);

        $allData = $request->all();
        unset($allData['_token']);
        $leveTyp = $allData['leave_types_id'];
        $days = $allData['adjust_days'];
		$div5 = !empty($allData['division_level_5']) ? $allData['division_level_5']: 0;
		$div4 = !empty($allData['division_level_4']) ? $allData['division_level_4']: 0;
		$div3 = !empty($allData['division_level_3']) ? $allData['division_level_3']: 0;
		$div2 = !empty($allData['division_level_2']) ? $allData['division_level_2']: 0;
		$div1 = !empty($allData['division_level_1']) ? $allData['division_level_1']: 0;
		$empl = !empty($allData['hr_person_id']) ? $allData['hr_person_id']: 0;

		if(!empty($empl))
			$employees = $empl;
		elseif(!empty($div1))
			$employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
		elseif(!empty($div2))
			$employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
		elseif(!empty($div3))
			$employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
		elseif(!empty($div4))
			$employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
		elseif(!empty($div5))
			$employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');

        foreach ($employees as $empID) {
			
			$credits = leave_credit::where('hr_id', $empID)
					->where('leave_type_id', $leveTyp)
					->first();
			if (!empty($credits->leave_balance)) {
				$prevBalance = $credits->leave_balance;
				$currentBalance = $credits->leave_balance + ($days * 8);
				$credits->leave_balance = $currentBalance;
				$credits->update();
				LeaveHistoryAuditController::store('Added annul leave Days','Annul leave Days', $prevBalance ,($days * 8),$currentBalance,$leveTyp, $empID);
			}
			else
			{
				$credit = new leave_credit();
				$credit->leave_balance = ($days * 8);
				$credit->hr_id = $empID;
				$credit->leave_type_id = $leveTyp;
				$credit->save();
				LeaveHistoryAuditController::store('Added annul leave Days','Annul leave Days', 0 ,($days * 8),($days * 8),$leveTyp, $empID);
			}
			AuditReportsController::store('Leave Management', 'leave days adjusted ', "Edited by User");
        }
        return back()->with('success_application', "leave action was successful adjusted.");
    }

    //leavecredit
    public function resert(Request $request, LeaveType $lev) {
		
		$this->validate($request, [
                 'division_level_5' => 'required',
                 'leave_types_id' => 'required',
                 'resert_days' => 'required',
        ]);
        $resertData = $request->all();
        unset($resertData['_token']);
		//return $resertData;
        $resertDays = $resertData['resert_days'];
        $typID = $resertData['leave_types_id'];
        $resert_days = $resertDays * 8;
		$div5 = !empty($resertData['division_level_5']) ? $resertData['division_level_5']: 0;
		$div4 = !empty($resertData['division_level_4']) ? $resertData['division_level_4']: 0;
		$div3 = !empty($resertData['division_level_3']) ? $resertData['division_level_3']: 0;
		$div2 = !empty($resertData['division_level_2']) ? $resertData['division_level_2']: 0;
		$div1 = !empty($resertData['division_level_1']) ? $resertData['division_level_1']: 0;
		$empl = !empty($resertData['hr_person_id']) ? $resertData['hr_person_id']: 0;

		if(!empty($empl))
			$employees = $empl;
		elseif(!empty($div1))
			$employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('hr_id');
		elseif(!empty($div2))
			$employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
		elseif(!empty($div3))
			$employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
		elseif(!empty($div4))
			$employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
		elseif(!empty($div5))
			$employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');

        foreach ($employees as $empID) {
			$emp = HRPerson::find($empID);
			$emp->leave_types()->detach($typID);
			$emp->leave_types()->attach($typID, ['leave_balance' => $resert_days]);
			//$emp->leave_types()->where('leave_type_id',$typID)->sync([$empID => ['leave_balance' => $resert_days]]);

			AuditReportsController::store('Leave Management', 'leave days reset Edited', "Edited by User: $lev->name", 0);
			LeaveHistoryAuditController::store('leave days reseted','leave days reseted', 0 ,$resert_days,$resert_days,$typID, $empID);
		}
        return back()->with('success_application', "leave allocation was successful resert.");
    }

    public function allocate(Request $request, LeaveType $lev) {
		
		$this->validate($request, [
                 'division_level_5' => 'required',
                 'leave_types_id' => 'required',
        ]);
		//hr_person_id
        $allData = $request->all();
        unset($allData['_token']);
		$div5 = !empty($allData['division_level_5']) ? $allData['division_level_5']: 0;
		$div4 = !empty($allData['division_level_4']) ? $allData['division_level_4']: 0;
		$div3 = !empty($allData['division_level_3']) ? $allData['division_level_3']: 0;
		$div2 = !empty($allData['division_level_2']) ? $allData['division_level_2']: 0;
		$div1 = !empty($allData['division_level_1']) ? $allData['division_level_1']: 0;
		$empl = !empty($allData['hr_person_id']) ? $allData['hr_person_id']: 0;
        $LevID = $allData['leave_types_id'];

		if(!empty($empl))
			$employees = $empl;
		elseif(!empty($div1))
			$employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('id');
		elseif(!empty($div2))
			$employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
		elseif(!empty($div3))
			$employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
		elseif(!empty($div4))
			$employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
		elseif(!empty($div5))
			$employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');
		
			//return $employees;
		foreach ($employees as $empID) {
			$emp = HRPerson::find($empID);
			$custLeave = leave_custom::where('hr_id', $empID)->first();
			$customDays = 0;
			if (!empty($custLeave->id) && $custLeave->number_of_days > 0) {
				$customDays = round(($custLeave->number_of_days / 12) * 8, 2);
			}
			// return leave profile id based on an user id;
			// get min value from pivot
			#get leaveprofile ID
			$LevProfID = HRPerson::where('user_id', $empID)->first();
			$proId = $LevProfID->leave_profile;
			$minimum = type_profile::where('leave_type_id', $LevID)
					->where('leave_profile_id', $proId)
					->first();
			$days = 0;
			if (count($minimum) > 0) {
				if (!empty($minimum->min))
					$days = round(($minimum->min / 12) * 8, 2);
			}
			if (!empty($customDays)) $days = $customDays;
			if ($days)
			{
				$credits = leave_credit::where('hr_id', $empID)
					->where('leave_type_id', $LevID)
					->first();
				
				if (count($credits) > 0)
				{
					$previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
					$currentBalance =  $previousBalance + $days;
					$currentBalance =  round($currentBalance, 2);
					$previousBalance =  round($previousBalance, 2);
					
					$credits->leave_balance = $currentBalance;
					$credits->update();
					LeaveHistoryAuditController::store('leave days allocation','leave days allocation', $previousBalance ,$days,$currentBalance,$LevID,$empID);
				}
				else
				{
					$credit = new leave_credit();
					$credit->leave_balance = $days;
					$credit->hr_id = $empID;
					$credit->leave_type_id = $LevID;
					$credit->save();
					LeaveHistoryAuditController::store('leave days allocation','leave days allocation', 0 ,$days,$currentBalance,$LevID,$empID);
				}
			}

			AuditReportsController::store('Leave Management', 'leave days allocation Edited', "Edited by User: $lev->name", 0);
		}
        return back()->with('success_application', "leave allocation was successful.");
    }

    public function editsetupType(Request $request, LeaveType $lev) {
        $this->validate($request, [
            'day5min' => 'numeric|min:2',
            'day5max' => 'numeric|min:2',
            'day6min' => 'numeric|min:2',
            'day6max' => 'numeric|min:2',
            'shiftmin' => 'numeric|min:2',
            'shiftmax' => 'numeric|min:2',
        ]);

        $day5min = (trim($request->input('day5min')) != '') ? (int) $request->input('day5min') : null;
        $day5max = (trim($request->input('day5max')) != '') ? (int) $request->input('day5max') : null;

        $day6min = (trim($request->input('day6min')) != '') ? (int) $request->input('day6min') : null;
        $day6max = (trim($request->input('day6max')) != '') ? (int) $request->input('day6max') : null;

        $shiftmin = (trim($request->input('shiftmin')) != '') ? (int) $request->input('shiftmin') : null;
        $shiftmax = (trim($request->input('shiftmax')) != '') ? (int) $request->input('shiftmax') : null;

        $lev->leave_profle()->sync([
            2 => ['min' => $day5min, 'max' => $day5max],
            3 => ['min' => $day6min, 'max' => $day6max],
            4 => ['min' => $shiftmin, 'max' => $shiftmax]
        ]);
//
        //return $lev;
        AuditReportsController::store('Leave Management', 'leave days Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json();
    }

    //#validate checkboxes
    public function store(Request $request, leave_configuration $levg) {
        $this->validate($request, [
        ]);
        $leavecredit = $request->all();
        unset($leavecredit['_token']);
		//return $leavecredit;
        $levg->update($leavecredit);
        return back();
    }
	// upload leave balance
	public function upload()
    {
        $data['page_title'] = "Leave Management";
        $data['page_description'] = "Upload Leave From Excel Sheet";
        $data['breadcrumb'] = [
            ['title' => 'Leave', 'path' => '/employee_upload', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Balance Upload', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Leave Upload';
        AuditReportsController::store('Leave', 'Upload page accessed', "Accessed by User", 0);
        return view('leave.leave_upload')->with($data);
    }
	// upload
	public function leaveUpload(Request $request)
    {
		if($request->hasFile('input_file'))
		{
			$path = $request->file('input_file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			if(!empty($data) && $data->count())
			{
				foreach ($data->toArray() as $key => $value) 
				{
					if(!empty($value))
					{
						if (!empty($value['employee_number']))	
						{
							$employees = HRPerson::where('employee_number', $value['employee_number'])->first();
							if (!empty($employees))
							{
								$days = !empty($value['Special'])? $value['Special'] : 0 ;
								if (!empty($days))
								{
									$credit = new leave_credit();
									$credit->leave_balance = ($days * 8);
									$credit->hr_id = $employees->id;
									$credit->leave_type_id = 4;
									$credit->save();
									LeaveHistoryAuditController::store('Added annul leave Days','Annul leave Days', 0 ,($days * 8),($days * 8),1, $employees->id);
								}
								AuditReportsController::store('Leave Management', 'leave days adjusted ', "Edited by User");
							}
						}
					}
				}
				return back()->with('success_add',"Records were successfully inserted.");
			}
			else return back()->with('error_add','Please Check your file, Something is wrong there.');
		}
		else return back()->with('error_add','Please Upload A File.');
		
        $data['page_title'] = "Leave Management";
        $data['page_description'] = "Upload Leave Balance";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/upload', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Balance', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Leave Management', "Leave Balance uploaded", "Accessed by User", 0);
    }
}