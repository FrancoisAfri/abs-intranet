<?php

namespace App\Http\Controllers\Crons;

use App\CompanyIdentity;
use App\DivisionLevelFour;
use App\DivisionLevelTwo;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_history;
use App\Mail\escalateleaveApplication;
use App\Mail\LeaveBalanceReminder;
use App\Mail\sendManagersListOfAbsentUsersToday;
use App\Mail\managerReminder;
use App\Mail\remindUserToapplyLeave;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Array_;


class SendLeaveBalanceToUsers extends Controller
{
    /**
     * @return void
     */
    public function execute()
    {

        $users = HRPerson::where('status', 1)->pluck('id');

        foreach ($users as $empID) {

            $leaveBalance = leave_credit::where('hr_id', $empID)->pluck('leave_balance');

            $hrDetails =  HRPerson::getManagerDetails($empID);
            $fullnane = $hrDetails->first_name . ' ' . $hrDetails->surname;
            $datanow = Carbon::now()->toDayDateTimeString();


            if (isset($leaveBalance) == false) {
                $leaveBalance = 0;
            }

            if (!empty($hrDetails->email))
                Mail::to($hrDetails->email)->send(new LeaveBalanceReminder($fullnane, $leaveBalance, $hrDetails->email, $datanow));
        }
    }

    /**
     * @return void
     */
    public function managerReminder()
    {

        #check who is the manager
        #send a reminder
        #runs everyday
        $date_now = Carbon::now()->toDayDateTimeString();

        $days = leave_configuration::pluck('number_of_days_to_remind_manager')->first();

        $date = Carbon::today()->subDays($days);

        $user = leave_application::where('status', '>=', 2)
            ->where('created_at', '>=', $date)
            ->pluck('hr_id');

        $users = $user->unique();

        $outputArray = array();
        foreach ($users as $empID) {
            $leaveApplications = leave_application::where(
                'hr_id', $empID
            )->first();
            if (isset($leaveApplications->manager_id))
                $managerId = $leaveApplications->manager_id;

            $hrDetails = HRPerson::getManagerDetails($managerId);

            $outputArray[] = $hrDetails;
        }
        // remeove duplicates so that we only send 1 email to diffrent managers
        $hrDetails = collect($outputArray)->unique();

        //needs improvement
        foreach ($hrDetails as $hrDetail) {
            $unapproved = leave_application::getUnapprovedApplications($date , $hrDetail->id);

            $fullnane = $hrDetail->first_name . ' ' . $hrDetail->surname;
            if (!empty($hrDetail->email))
                Mail::to($hrDetail->email)->send(new managerReminder($fullnane, $hrDetail->email, $date_now, $unapproved));

        }

    }

    /**
     * @return void
     * function to escalate unresolved applications
     */
    public function leaveEscallation()
    {

        #check who is the manager
        #send a reminder
        #runs everyday
        $date_now = Carbon::now()->toDayDateTimeString();

        $daysToEscalation = leave_configuration::pluck('mumber_of_days_until_escalation')->first();


        $date = Carbon::today()->subDays($daysToEscalation);

        $user = leave_application::where('status', '>=', 2)
            ->where('created_at', '>=', $date)
            ->pluck('hr_id');


        $users = $user->unique();

        $outputArray = array();
        foreach ($users as $empID) {
            $leaveApplications = leave_application::where('hr_id', $empID)->first();
            if (isset($leaveApplications->manager_id))
                $managerId = $leaveApplications->manager_id;

            $hrDetails = HRPerson::getManagerDetails($managerId);

            $outputArray[] = $hrDetails;
        }
        // remove duplicates so that we only send 1 email to different managers
        $hrDetails = collect($outputArray)->unique();

        //needs improvement
        $headDep = array();
        foreach ($hrDetails as $hrDetail) {

            $Dept = DivisionLevelFour::where('id', $hrDetail->division_level_4)->first();

            $deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
            ->select('first_name', 'surname', 'email')
            ->first();
         

            $unapproved = leave_application::getUnapprovedApplications($date , $hrDetail->id);

            $fullnane = $hrDetail->first_name . ' ' . $hrDetail->surname;

            if (!empty($hrDetail->email))
                Mail::to($hrDetail->email)->send(new managerReminder($fullnane, $hrDetail->email, $date_now, $unapproved));

            $headName = $deptDetails->first_name . ' ' . $deptDetails->surname;

        }
        if (!empty($deptDetails->email))
            Mail::to($deptDetails->email)->send(new escalateleaveApplication($headName, $hrDetail->email, $date_now, $unapproved , $fullnane));
    }


    /**
     * @return mixed
     * @throws \Throwable
     */
    public function viewBalance()
    {
        $credit =  leave_history::getLeaveBalance();

        $date_now = Carbon::now()->toDayDateTimeString();

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        $data['date'] = $date_now;
        $data['credit'] = $credit;
        $data['file_name'] = 'LeaveBalance';
        $view = view('leave.reports.leave_balance', $data)->render();

        $pdf = resolve('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('enable_html5_parser', true);
        $pdf->loadHTML($view);
        return $pdf->output();

    }


    /**
     * @return \Illuminate\Http\Response
     * function to send reports to the head
     * @return void
     * @throws \Throwable
     */
    public function sendReport(): \Illuminate\Http\Response
    {
        //get the user selected on the settings
        $userId = leave_configuration::pluck('hr_person_id')->first();
        $userDetails = HRPerson::getManagerDetails($userId);
        $fullname = $userDetails->firstname . ' ' . $userDetails->surname;

        $leaveAttachment = $this->viewBalance();

        try {
            Mail::to($userDetails->email)->send(new sendManagersListOfAbsentUsersToday($userDetails->email, $leaveAttachment));
            echo 'Mail send successfully';
        } catch (\Exception $e) {
            echo 'Error - ' . $e;
        }

    }
}