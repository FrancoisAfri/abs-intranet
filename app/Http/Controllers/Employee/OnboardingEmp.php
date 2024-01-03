<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\ManualClockin;
use App\TrainingDocuments;
use App\DivisionLevel;
use App\EmployeeTasks;
use App\HRPerson;
use App\Models\Onboarding;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class OnboardingEmp extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $onboardings = Onboarding::where('status',1)
             ->orderBy('id')
            ->get();
		// arrays
		$MaritalStatus = [1 => 'Single',2 => 'Married',3 => 'Divorced',4 => 'Widower'];
        $ethnicity = [1 => 'African',2 => 'Asian',3 => 'Caucasian',4 => 'Coloured',5 => 'Indian',6 => 'White'];
        $leaveProfiles = [ 1 => 'Employee with no leave',2 => '5 Day Employee',3 => '6 Day Employee',4 => 'Shift Worker'];
        $titles = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
        $disabilities = [ 1 => 'Yes',2 => 'No'];
        $employmentTypes = [ 1 => 'Permanent',2 => 'Temporary'];
        $occupationalLevels = [ 1 => 'Senior Management',2 => 'Middle Management',3 => 'Junior Management',4 => 'Semi Skilled',4 => 'Unskilled'];
        $jobFunctions = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
		$marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        
		AuditReportsController::store(
            'Employee Records',
            'Onboarding Page Accessed',
            "Accessed By User",
            0
        );
		$data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Onboarding';
        $data['onboardings'] = $onboardings;
		$data['titles'] = $titles;
        $data['disabilities'] = $disabilities;
        $data['employmentTypes'] = $employmentTypes;
        $data['occupationalLevels'] = $occupationalLevels;
        $data['jobFunctions'] = $jobFunctions;
        $data['marital_statuses'] = $marital_statuses;
        $data['ethnicity'] = $ethnicity;
        $data['statuses'] = Onboarding::STATUS_SELECT;
		
		$data['page_title'] = "Onboarding";
        $data['page_description'] = "Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => 'employee/onboarding', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Onboarding', 'active' => 1, 'is_module' => 0]
        ];
        return view('Employees.onboarding')->with($data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
	
	public function AcceptLeave(Request $request, leave_application $leaveId)
    {
        #query the hr person table
        $hrDetails = HRPerson::where('id', $leaveId->hr_id)->where('status', 1)->first();
        $daysApplied = $leaveId->leave_taken;
        //check leave approvals setup
        $approvals = leave_configuration::where('id', 1)
            ->select('require_managers_approval', 'require_department_head_approval')
            ->first();
        $ManHed = $approvals->require_managers_approval;
        $DepHead = $approvals->require_department_head_approval;
		
        //update leave application status
        if ($leaveId->status == 2 && $DepHead == 1) {
            $leaveId->status = 3;
            $leaveId->update();

        } elseif ($leaveId->status == 2 && $ManHed == 1) {
			
            // update leave application status
            $leaveId->status = 1;
            $leaveId->update();
            // #Query the  leave_config days for value
            $credit = leave_credit::getLeaveCredit($leaveId->hr_id, $leaveId->leave_type_id);
            $leaveBalance = $credit->leave_balance;
            #subract current balance from the one applied for
            $newBalance = $leaveBalance - $daysApplied;
            $credit->leave_balance = $newBalance;
            $credit->update();
            $leaveAttachment = $this->viewApplication($leaveId);
            #send email to the user informing that the leave has been accepted
            if (!empty($hrDetails->email))
                Mail::to($hrDetails->email)->send(new Accept_application($hrDetails->first_name, $leaveAttachment));

            // send emal to Hr manager / Hr managers
			// get user on setup
			$leaveNotificationsUsers = LeaveNotifications::getListOfUsers();
			if (!empty($leaveNotificationsUsers)) {
				foreach ($leaveNotificationsUsers as $user) {
					if (!empty($user->hr_id)) {
						$details = HRPerson::where('id', $user->hr_id)->where('status', 1)
							->select('first_name', 'email')->first();
						if (!empty($details->email))
							Mail::to($details->email)->send(new SendLeaveApplicationToHrManager($details->first_name, $leaveAttachment));
					}
				}
                
            }
            /*if (!empty($hrDetails->division_level_5)) {
                $Dept = DivisionLevelFive::where('id', $hrDetails->division_level_5)->first();
                if (!empty($Dept->manager_id)) {
                    $deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                        ->select('first_name', 'email')
                        ->first();
                    if (!empty($deptDetails->email))
                        Mail::to($deptDetails->email)->send(new SendLeaveApplicationToHrManager($deptDetails->first_name, $leaveAttachment));
                }
            }*/
            // update leave history
            LeaveHistoryAuditController::store(
                "leave application Approved",
                '',
                $leaveBalance,
                $daysApplied,
                $newBalance,
                $leaveId->leave_type_id,
                $leaveId->hr_id
            );
        }

        AuditReportsController::store(
            'Leave Management',
            'leave_approval Informations accepted',
            "Edited by User: $leaveId->hr_id",
            0
        );

        return back()->with('success_application', "leave application was successful.");
    }

    /**
     * @param Request $request
     * @param leave_application $levReject
     * @return JsonResponse
     */
    public function reject(Request $request, leave_application $levReject)
    {
        $this->validate($request, [
            'description' => 'required',
        ]);
        $leaveData = $request->all();
        unset($leaveData['_token']);

        $usedetails = HRPerson::where(
            'id',
            $levReject->hr_id
        )
            ->select(
                'first_name',
                'surname',
                'email',
                'manager_id'
            )
            ->first();

        $manager_id = !empty($usedetails['manager_id']) ? $usedetails['manager_id'] : 0;

        $levReject->reject_reason = $request->input('description');
        $levReject->status = 6;
        $levReject->update();

        #send rejection email
        Mail::to($usedetails['email'])->send(
            new LeaveRejection
            (
                $usedetails['first_name']
                , $usedetails['surname']
                , $usedetails['email'])
        );

        AuditReportsController::store(
            'Leave Management: ',
            'leave rejected',
            "By User",
            0
        );

        LeaveHistoryAuditController::store(
            "leave application Rejected",
            0,
            0,
            0,
            0,
            $levReject->leave_type_id,
            $manager_id
        );
        return response()->json();
    }
}
