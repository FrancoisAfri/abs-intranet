<?php

namespace App\Http\Controllers\Crons;

use App\HRPerson;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\Mail\LeaveBalanceReminder;
use App\Mail\managerReminder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SendLeaveBalanceToUsers extends Controller
{
    /**
     * @return void
     */
    public function execute()
    {
        $users = HRPerson::where('status', 1)->pluck('id');

        foreach ($users as $empID) {

            $leaveBalance = leave_credit::where('hr_id', $users)->pluck('leave_balance')->first();
            $hrDetails = HRPerson::where(
                [
                    'user_id' => $empID,
                    'status' => 1
                ]
            )
                ->first();

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
            $leaveApplications = leave_application::where('hr_id', $empID)->first();
            if (isset($leaveApplications->manager_id))
                $managerId = $leaveApplications->manager_id;

            $hrDetails = HRPerson::where(
                [
                    'user_id' => $managerId,
                    'status' => 1
                ]
            )->first();

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
       
    }
}