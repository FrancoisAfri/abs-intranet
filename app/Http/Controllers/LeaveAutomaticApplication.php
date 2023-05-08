<?php

namespace App\Http\Controllers;


use App\DivisionLevelTwo;
use App\HRPerson;
use App\DivisionLevel;
use App\CompanyIdentity;
use App\Http\Requests;
use App\DivisionLevelFive;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_custom;
use App\leave_history;
use App\LeaveType;
use App\Mail\Accept_application;
use App\Mail\SendLeaveApplicationToManager;
use App\Mail\SendLeaveApplicationToHrManager;
use App\Mail\leave_applications;
use App\Mail\LeaveRejection;
use App\Traits\uploadFilesTrait;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Throwable;


class LeaveAutomaticApplication extends Controller
{
    //$date = Carbon::today()->subDays($daysToEscalation);

        //$user = leave_application::where('status', '>=', 2)
        //    ->where('created_at', '>=', $date)
         //   ->pluck('hr_id');
		 
		 public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employees = HRPerson::where('status',1)->get();

        $leaveTypes = LeaveType::where('status', 1)
            ->orderBy('name', 'asc')
            ->get()->load(
                ['leave_profle' => function ($query) {
                }]);
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;

        $data['page_title'] = "Leave Management";
        $data['page_description'] = "Bulk Leave Application";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Bulk Leave Application', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Bulk Application';

        AuditReportsController::store(
            'Leave Management',
            'Leave Type Page Accessed',
            "Accessed By User",
            0
        );
        return view('leave.application_automate')->with($data);
    }
	public function deleteApp()
    {
        $employees = HRPerson::where('status',1)->get();

        $leaveTypes = LeaveType::where('status', 1)
            ->orderBy('name', 'asc')
            ->get()->load(
                ['leave_profle' => function ($query) {
                }]);
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;

        $data['page_title'] = "Leave Management";
        $data['page_description'] = "Bulk Leave Application";
        $data['breadcrumb'] = [
            ['title' => 'Leave Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Bulk Leave Application', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Bulk Application';

        AuditReportsController::store(
            'Leave Management',
            'Leave Type Page Accessed',
            "Accessed By User",
            0
        );
        return view('leave.application_automate_delete')->with($data);
    }
	// save bulk application 
	public function store(Request $request)
    {
		//die('fffffffffffff');
        //Validation
		$this->validate($request, [
			 'division_level_5' => 'required',
            'leave_type' => 'required',
            'hours' => 'required',
            'date_applied' => 'required',
        ]);

        $leaveApp = $request->all();
        unset($leaveApp['_token']);
		
		// declare variable
        $date_applied = strtotime($leaveApp['date_applied']);
        $hrID = $leaveApp['hr_person_id'];
        $typID = $leaveApp['leave_type'];
        $hours = $leaveApp['hours'];
		
		$div5 = !empty($leaveApp['division_level_5']) ? $leaveApp['division_level_5'] : 0;
        $div4 = !empty($leaveApp['division_level_4']) ? $leaveApp['division_level_4'] : 0;
        $div3 = !empty($leaveApp['division_level_3']) ? $leaveApp['division_level_3'] : 0;
        $div2 = !empty($leaveApp['division_level_2']) ? $leaveApp['division_level_2'] : 0;
        $div1 = !empty($leaveApp['division_level_1']) ? $leaveApp['division_level_1'] : 0;
        $empl = !empty($leaveApp['hr_person_id']) ? $leaveApp['hr_person_id'] : 0;
		
		// get user list
        if (!empty($empl))
            $employees = HRPerson::where('id', $empl)->where('status', 1)->pluck('id');
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');

        foreach ($employees as $empID) {

			// check if an application have already been made
			// previous application
			$previousApp = leave_application::where('start_date', '=', $date_applied)->where('hr_id',$empID)->first();
			//return $previousApp;
			$managerDetails = HRPerson::getManagerDetails($empID);
			
			if (!empty($previousApp->leave_taken))
			{
			
				// increase the leave balance with days taken
				// get user current balance
				$credits = leave_credit::where('hr_id', $empID)->where('leave_type_id', $typID)->first();
				
				//update balance with leave application taken
				if (!empty($credits->leave_balance)) 
				{
					// update for existing balance
					$prevBalance = $credits->leave_balance;
					$currentBalance = $credits->leave_balance + $previousApp->leave_taken;
					$credits->leave_balance = $currentBalance;
					$credits->update();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', $prevBalance, $previousApp->leave_taken, $currentBalance, $typID, $empID);
				} 
				else 
				{
					//add leave for new balance 
					$credit = new leave_credit();
					$credit->leave_balance = $previousApp->leave_taken;
					$credit->hr_id = $empID;
					$credit->leave_type_id = $typID;
					$credit->save();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', 0, $previousApp->leave_taken, $previousApp->leave_taken, $typID, $empID);
				}
				// delete leave application
				$app = leave_application::find($previousApp->id);
				$app->delete();
				
				// apply correct leave days
				$managerID = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;
				
				// create leave application
				$levApp = leave_application::create([
					'leave_type_id' => $typID,
					'start_date' => $date_applied,
					'end_date' => $date_applied,
					'leave_taken' => $hours,
					'hr_id' => $empID,
					'notes' => 'Annual Mandotory Leave Application',
					'status' => 1,
					'manager_id' => $managerID,
				]);
				// save audit
					AuditReportsController::store(
					'Leave Management',
					'Leave day application',
					"Accessed By User",
					0
				);

				/**
				 * leave history audit
				 */
				
				// deduct leave balance
				$credit = leave_credit::where('hr_id', $empID)->where('leave_type_id', $typID)->first();
				//update balance with leave application taken
				if (!empty($credit->leave_balance)) 
				{
					// update for existing balance
					$prevBalance = $credit->leave_balance;
					$currentBalance = $credit->leave_balance - $hours;
					$credit->leave_balance = $currentBalance;
					$credit->update();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', $prevBalance, $hours, $currentBalance, $typID, $empID);
				} 
				else 
				{
					//add leave for new balance 
					$credit = new leave_credit();
					$credit->leave_balance = $hours;
					$credit->hr_id = $empID;
					$credit->leave_type_id = $typID;
					$credit->save();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', 0, $hours, $hours, $typID, $empID);
				}
			}
			else
			{
				
				// apply for leave days
				
				$managerID = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;
				
				// create leave application
				$levApp = leave_application::create([
					'leave_type_id' => $typID,
					'start_date' => $date_applied,
					'end_date' => $date_applied,
					'leave_taken' => $hours,
					'hr_id' => $empID,
					'notes' => 'Annual Mandotory Leave Application',
					'status' => 1,
					'manager_id' => $managerID,
				]);
				// save audit
					AuditReportsController::store(
					'Leave Management',
					'Leave day application',
					"Accessed By User",
					0
				);

				/**
				 * leave history audit
				 */
				
				// deduct leave balance
				$credit = leave_credit::where('hr_id', $empID)->where('leave_type_id', $typID)->first();
				//update balance with leave application taken
				if (!empty($credit->leave_balance)) 
				{
					// update for existing balance
					$prevBalance = $credit->leave_balance;
					$currentBalance = $credit->leave_balance - $hours;
					$credit->leave_balance = $currentBalance;
					$credit->update();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', $prevBalance, $hours, $currentBalance, $typID, $empID);
				} 
				else 
				{
					//add leave for new balance 
					$credit = new leave_credit();
					$credit->leave_balance = $hours;
					$credit->hr_id = $empID;
					$credit->leave_type_id = $typID;
					$credit->save();
					LeaveHistoryAuditController::store('Added annul leave Days', 'Annul leave Days', 0, $hours, $hours, $typID, $empID);
				}
			}
        }

        Alert::toast('Bulk applications were successfully added ', 'success');
		 
        return back()->with('success_application', "Bulk applications were successfully added.");

    }
	// save bulk application 
	public function delete(Request $request)
    {
        //Validation
		$this->validate($request, [
			 'division_level_5' => 'required',
            'leave_type' => 'required',
            'hours' => 'required',
            'date_applied' => 'required',
        ]);
		//die('leave app delete');
        $leaveApp = $request->all();
        unset($leaveApp['_token']);
		
		// declare variable
        $date_applied = date('Y-m-d', strtotime($leaveApp['date_applied']));
		//return $date_applied;
        $hrID = $leaveApp['hr_person_id'];
        $typID = $leaveApp['leave_type'];
        $hours = $leaveApp['hours'] * 8;
		
		$div5 = !empty($leaveApp['division_level_5']) ? $leaveApp['division_level_5'] : 0;
        $div4 = !empty($leaveApp['division_level_4']) ? $leaveApp['division_level_4'] : 0;
        $div3 = !empty($leaveApp['division_level_3']) ? $leaveApp['division_level_3'] : 0;
        $div2 = !empty($leaveApp['division_level_2']) ? $leaveApp['division_level_2'] : 0;
        $div1 = !empty($leaveApp['division_level_1']) ? $leaveApp['division_level_1'] : 0;
        $empl = !empty($leaveApp['hr_person_id']) ? $leaveApp['hr_person_id'] : 0;
		
		// get user list
        if (!empty($empl))
            $employees = HRPerson::where('id', $empl)->where('status', 1)->pluck('id');
        elseif (!empty($div1))
            $employees = HRPerson::where('division_level_1', $div1)->where('status', 1)->pluck('id');
        elseif (!empty($div2))
            $employees = HRPerson::where('division_level_2', $div2)->where('status', 1)->pluck('id');
        elseif (!empty($div3))
            $employees = HRPerson::where('division_level_3', $div3)->where('status', 1)->pluck('id');
        elseif (!empty($div4))
            $employees = HRPerson::where('division_level_4', $div4)->where('status', 1)->pluck('id');
        elseif (!empty($div5))
            $employees = HRPerson::where('division_level_5', $div5)->where('status', 1)->pluck('id');

        foreach ($employees as $empID) {

			// check if an application have already been made
			$previousApp = leave_history::whereDate('created_at', '=', $date_applied)->where('hr_id',$empID)->where('transcation',$hours)->first();

			if (!empty($previousApp->transcation))
			{
				// get user current balance
				$credits = leave_credit::where('hr_id', $empID)->where('leave_type_id', $typID)->first();
				//return $credits;
				//update balance with leave application taken
				if (!empty($credits->leave_balance)) 
				{
					// update for existing balance
					$prevBalance = $credits->leave_balance;
					$currentBalance = $credits->leave_balance - $hours;
					$credits->leave_balance = $currentBalance;
					$credits->update();
					LeaveHistoryAuditController::store('deducted annual leave Days', 'Annual leave Days', $prevBalance, $hours, $currentBalance, $typID, $empID);
				}
			}
        }

        Alert::toast('Bulk applications were successfully added ', 'success');
		 
        return back()->with('success_application', "The transaction were successful.");

    }
}
