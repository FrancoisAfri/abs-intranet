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
use App\LeaveAllocation;
use App\HRPerson;
use App\hr_person;
use App\modules;
use App\leave_credit;
use App\leave_application;
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
        $employees = HRPerson::where('status', 1)->orderBy('first_name','asc')->orderBy('surname','asc')->get();
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
    public function resetLeave(Request $request, LeaveType $lev) {
		
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
		$user = Auth::user()->load('person');
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
			// check if this leave have already been allocated for this month
			$allocation = LeaveAllocation::where('hr_id', $empID)
				->where('leave_type_id', $LevID)
				->where('month_allocated', date('n'))
				->first();
				
			if (empty($allocation))
			{
				$customDays = $days = $maximum = 0;
				$custLeave = leave_custom::where('hr_id', $empID)->first();
				if (!empty($custLeave->id) && $custLeave->number_of_days > 0) {
					$customDays = ($custLeave->number_of_days / 12) * 8;
				}
				// return leave profile id based on an user id;
				// get min value from pivot
				#get leaveprofile ID
				$LevProfID = HRPerson::where('id', $empID)->first();
				if (!empty($LevProfID))
				{
					$proId = $LevProfID->leave_profile;
					$minimum = type_profile::where('leave_type_id', $LevID)
							->where('leave_profile_id', $proId)
							->first();
		
					if (count($minimum) > 0) {
						if (!empty($minimum->min))
							$days = ($minimum->min / 12) * 8;
						if (!empty($minimum->max))
							$maximum = $minimum->max * 8;
					}
				}
				if (!empty($customDays)) $days = $customDays;
				if (!empty($days))
				{
					$credits = leave_credit::where('hr_id', $empID)
						->where('leave_type_id', $LevID)
						->first();
					

					if (count($credits) > 0)
					{
						$previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
						$currentBalance =  $previousBalance + $days;
						if ($maximum > $currentBalance)
						{
							$credits->leave_balance = $currentBalance;
							$credits->update();
							LeaveHistoryAuditController::store('leave days allocation','leave days allocation', $previousBalance ,$days,$currentBalance,$LevID,$empID);
							//insert into allocation table
							$leaveAllocation = new LeaveAllocation();
							$leaveAllocation->hr_id = $empID;
							$leaveAllocation->month_allocated = date('n');
							$leaveAllocation->leave_type_id = $LevID;
							$leaveAllocation->allocated_by = $user->person->id;;
							$leaveAllocation->date_allocated = time();
							$leaveAllocation->balance_before = $previousBalance;
							$leaveAllocation->current_balance = $currentBalance;
							$leaveAllocation->save();
						}
					}
					else
					{
						$previousBalance = 0;
						$currentBalance = $days;
						$credit = new leave_credit();
						$credit->leave_balance = $days;
						$credit->hr_id = $empID;
						$credit->leave_type_id = $LevID;
						$credit->save();
						LeaveHistoryAuditController::store('leave days allocation','leave days allocation', 0 ,$days,$currentBalance,$LevID,$empID);
						//insert into allocation table
						$leaveAllocation = new LeaveAllocation();
						$leaveAllocation->hr_id = $empID;
						$leaveAllocation->month_allocated = date('n');
						$leaveAllocation->leave_type_id = $LevID;
						$leaveAllocation->allocated_by = $user->person->id;;
						$leaveAllocation->date_allocated = time();
						$leaveAllocation->balance_before = $previousBalance;
						$leaveAllocation->current_balance = $currentBalance;
						$leaveAllocation->save();
					}
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
							$employees = HRPerson::where('employee_number', $value['employee_number'])->where('status',1)->first();
							if (!empty($employees))
							{
								$days = !empty($value['special'])? $value['special'] : 0 ;
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
	// leave application upload 
	public function leaveUploadApplications(Request $request)
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
							$employees = HRPerson::where('employee_number', $value['employee_number'])->where('status',1)->first();
							if (!empty($employees))
							{
								$fromDate = !empty($value['from_date'])? $value['from_date'] : 0 ;
								$toDate = !empty($value['to_date'])? $value['to_date'] : 0 ;
								$fromDate = str_replace('/', '-', $fromDate);
								//$startDate = strtotime($startDate);
								$toDate = str_replace('/', '-', $toDate);
								$dayRequested = LeaveSetupController::calculatedays($fromDate,$toDate);
								$dayRequested = $dayRequested * 8;
								$startDate = strtotime($fromDate);
								$endDate = strtotime($toDate);

								if (!empty($value['leave_type']) && $value['leave_type'] == 'Special')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 4;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 4)
										->first();
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 4;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 4, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Annual')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 1;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 1)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 1;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 1, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Sick')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 5;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 5)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 5;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 5, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Family')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 2;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 2)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 2;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 2, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Maternity')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 3;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 3)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 3;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 3, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Unpaid')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 7;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
									$newBalance = $leaveBalance - $dayRequested;
									$levcre = new leave_credit();
									$levcre->leave_type_id = 7;
									$levcre->leave_balance = $newBalance;
									$levcre->hr_id = $employees->id;
									$levcre->save();
									// Query leave credit to get available leave days
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 7)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 7;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 7, $employees->id);
								}
								elseif (!empty($value['leave_type']) && $value['leave_type'] == 'Study')
								{
									// get leave application
									$levApp = new leave_application();
									$levApp->leave_type_id = 6;
									$levApp->start_date = $startDate;
									$levApp->end_date = $endDate;
									$levApp->leave_taken = $dayRequested;
									$levApp->hr_id = $employees->id;
									$levApp->notes = '';
									$levApp->status = 1;
									$levApp->manager_id = !empty($employees->manager_id) ? $employees->manager_id : 0;
									$levApp->save();
									// #Query the the leave_config days for value
									$credit = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 6)
										->first();
									// new improved code 
									if (!empty($credit))
									{
										$leaveBalance = !empty($credit->leave_balance) ? $credit->leave_balance: 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$credit->leave_balance = $newBalance;
										$credit->update();	
									}
									else
									{
										$leaveBalance = 0;
										#subract current balance from the one applied for
										$newBalance = $leaveBalance - $dayRequested;
										$levcre = new leave_credit();
										$levcre->leave_type_id = 6;
										$levcre->leave_balance = $newBalance;
										$levcre->hr_id = $employees->id;
										$levcre->save();
									}
									// update leave history
									LeaveHistoryAuditController::store("leave application Approved", '', $leaveBalance, $dayRequested, $newBalance, 6, $employees->id);
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
	
	# calculate leave days
	public function calculatedays($dateFrom, $dateTo)
    {
		
        //convert dates
        $startDate = strtotime($dateFrom);
        $endDate = strtotime($dateTo);
		
		$onceOffHoliday = date("Y", $startDate);
        // calculate public holidays and weekends
        $numweek = 0;
        $publicHolidays = array();
        $publicHolidays = DB::table('public_holidays')
							->where(function ($query)  use ($onceOffHoliday) {
								$query->whereNull('year')
									  ->orWhere('year', '=', 0)
									  ->orWhere('year', '=', $onceOffHoliday);
							})
							->pluck('day');
							//return $publicHolidays;
        # Add Easter Weekend to list of public holidays
		$easterSunday =  easter_date(date("Y",$endDate));
		$publicHolidays[] = $easterSunday - (2*3600*24);
		$publicHolidays[] = $easterSunday + (3600*24);
		
		for ($i = $startDate; $i <= $endDate; $i = $i+86400)
		{
			$publicArray = array();
			foreach ($publicHolidays as $key => $value)
			{
				$day = date("Y",$i)."-".date("m",$value)."-".date("d",$value);
				$day = strtotime($day);
				$publicArray[$day] = 0;
			}
			if (((date("w",$i) == 6) || (date("w",$i) == 0))) $numweek++;
			if (array_key_exists($i,$publicArray) && ((date("w",$i) != 6) && (date("w",$i) != 0))) $numweek++;
			
			if (array_key_exists($i-86400,$publicArray) && (date("w",$i) == 1))
				if (array_key_exists($i,$publicArray)) {}
				else $numweek++;
		}
        $diff = $endDate - $startDate;
		$days = ($diff / 86400) - $numweek + 1;
		return $days;
    }
	// upload
	public function leaveUploadPaid(Request $request)
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
						if (!empty($value['employee_number']) && !empty($value['leave_type']) )	
						{
							$employees = HRPerson::where('employee_number', $value['employee_number'])->where('status',1)->first();
							$leaveType = LeaveType::where('name', $value['leave_type'])->where('status',1)->first();
							if (!empty($employees) && !empty($leaveType))
							{
								$credit = leave_credit::where('hr_id', $employees->id)->where('leave_type_id', $leaveType->id)->first();
								$days = !empty($value['days'])? $value['days'] : 0 ;
								$currentDays = !empty($credit['leave_balance'])? $credit['leave_balance'] : 0 ;
								if (!empty($days))
								{
									$credit->leave_balance = $currentDays - ($days * 8);
									$credit->update();
									LeaveHistoryAuditController::store('Added annual leave Days Paid','Annual leave Days Paid', 0 ,($days * 8),($days * 8),1, $employees->id);
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
        $data['page_description'] = "Upload Leave Paid Out";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/upload', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Paid Out', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Upload';
        AuditReportsController::store('Leave Management', "Leave Balance uploaded", "Accessed by User", 0);
    }
	
	// upload
	public function leaveUploadReactivation(Request $request)
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
						if (!empty($value['employee_number']) && !empty($value['date']))	
						{
							$date1 = $value['date'];
							$date2 = '2021-05-01';
							$ts1 = strtotime($date1);
							$ts2 = strtotime($date2);
							$year1 = date('Y', $ts1);
							$year2 = date('Y', $ts2);
							$month1 = date('m', $ts1);
							$month2 = date('m', $ts2);
							$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
							
							$employees = HRPerson::where('employee_number', $value['employee_number'])->where('status',1)->first();
							if (!empty($employees))
							{
								$customDays = $days = $maximum = 0;
								$custLeave = leave_custom::where('hr_id', $employees->id)->first();
								if (!empty($custLeave->id) && $custLeave->number_of_days > 0) {
									$customDays = (($custLeave->number_of_days / 12) * $diff) * 8;
								}
								// return leave profile id based on an user id;
								// get min value from pivot
								#get leaveprofile ID
								$LevProfID = HRPerson::where('id', $employees->id)->first();
								if (!empty($LevProfID))
								{
									$proId = $LevProfID->leave_profile;
									$minimum = type_profile::where('leave_type_id', 1)
											->where('leave_profile_id', $proId)
											->first();
						
									if (count($minimum) > 0) {
										if (!empty($minimum->min))
											$days = (($minimum->min / 12) * $diff)* 8;
									}
								}
								if (!empty($customDays)) $days = $customDays;
								if (!empty($days))
								{
									$credits = leave_credit::where('hr_id', $employees->id)
										->where('leave_type_id', 1)
										->first();
									if (count($credits) > 0)
									{
										$previousBalance = !empty($credits->leave_balance) ? $credits->leave_balance : 0;
										$currentBalance =  $previousBalance + $days;
										$credits->leave_balance = $currentBalance;
										$credits->update();
										LeaveHistoryAuditController::store('leave days reactivation','leave days reactivation', $previousBalance ,$days,$currentBalance,1,$employees->id);
									}
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
        $data['page_description'] = "Upload Leave Reactivation";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/upload', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Reactivation', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Upload';
        AuditReportsController::store('Leave Management', "Leave Reactivation uploaded", "Accessed by User", 0);
    }
}