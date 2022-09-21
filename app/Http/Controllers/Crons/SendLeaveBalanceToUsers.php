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

    public function managerReminder()
    {

        $date_now = Carbon::now()->toDayDateTimeString();

        $days = leave_configuration::pluck('number_of_days_to_remind_manager')->first();

        $date = Carbon::today()->subDays($days);

        $users = leave_application::where('status', 2)
            ->where('created_at', '>=', $date)
            ->pluck('hr_id');

        $unapproved = leave_application::getUnapprovedApplications($date);

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

            $fullnane = $hrDetails->first_name . ' ' . $hrDetails->surname;

            if (!empty($hrDetails->email))
                Mail::to($hrDetails->email)->send(new managerReminder($fullnane, $hrDetails->email, $date_now, $unapproved));

        }


        #check who is the manager
        #send a reminder
        #runs everyday
    }
}
