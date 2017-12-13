<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use App\EmployeeTasks;
use App\EmployeeTasksDocuments;
use App\Mail\InductionCronEmail;
use App\Mail\InductionCronEscalationEmail;
use App\Mail\InductionSignofEmail;
use App\Mail\InductionHandHovinEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InductionCronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function execute()
    {
        //$today = strtotime(date('Y-m-d'));
        $today    = mktime(0, 0, 0, date('m'), date('d')+2, date('Y'));
        $escalationTasks = DB::table('employee_tasks')
            ->select('employee_tasks.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
            ->where('employee_tasks.due_date', '<=', $today)
            ->where('employee_tasks.status', '<', 4)
            ->orderBy('employee_tasks.order_no')
            ->get();
        if (!empty($escalationTasks))
        {
            foreach ($escalationTasks as $task)
            {
                $employeeName = $task->firstname.' '.$task->surname;

                if(!empty($task->employee_id))
                {
                    # Send Email to employee
                    $employee = HRPerson::where('id', $task->employee_id)->first();

                    Mail::to($employee->email)->send(new InductionCronEmail($employee, $task->description));
                    # Send Email to escalation person
                    if(!empty($employee->manager_id))
                    {
                        $manager = HRPerson::where('id', $employee->manager_id)->first();
                        Mail::to($manager->email)->send(new InductionCronEscalationEmail($manager, $task->description, $employee));
                    }
                }
            }
            AuditReportsController::store('Task Management', "Cron Tasks Ran", "Automatic Ran by Server", 0);
        }
        else
            AuditReportsController::store('Task Management', "Cron Tasks Ran", "Automatic Ran by Server no data to process", 0);//$today = strtotime(date('Y-m-d'));

        $thirthydays    = mktime(0, 0, 0, date('m'), date('d')+30, date('Y'));
        $sixtydays    = mktime(0, 0, 0, date('m'), date('d')+60, date('Y'));
        $inductions = DB::table('client_inductions')
            ->select('client_inductions.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'client_inductions.create_by', '=', 'hr_people.user_id')
            ->where('client_inductions.status', '<', 2)
            ->orderBy('client_inductions.induction_title')
            ->get();
        if (!empty($inductions))
        {
            foreach ($inductions as $induction)
            {
                $employeeName = $induction->firstname.' '.$induction->surname;

                if(!empty($induction->date_created))
                {
                    # Send Email to employee
                    $employee = HRPerson::where('user_id', $induction->create_by)->first();
                    if($induction->date_created <= $thirthydays && $induction->date_created > $sixtydays)
                    {
                        Mail::to($employee->email)->send(new InductionHandHovinEmail($employee, $induction->induction_title));
                        AuditReportsController::store('Task Management', "Hand Hovin Email Sent", "induction name: $induction->induction_title Sent to Account manager", 0);
                    }
                    # Send Email to escalation person
                    if($induction->date_created <= $sixtydays)
                    {
                        Mail::to($employee->email)->send(new InductionSignofEmail($employee, $induction->induction_title));
                        AuditReportsController::store('Task Management', "Sign Off Email Sent", "induction name: $induction->induction_title Sent to Account manager", 0);
                    }
                }
            }
        }
        else
            AuditReportsController::store('Task Management', "Con Induction Ran", "Automatic Ran by Server no data to process", 0);
    }
}
//echo 144;