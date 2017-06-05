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
		$today = strtotime(date('Y-m-d'));
		$escalationTasks = DB::table('employee_tasks')
		->select('employee_tasks.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
		->where('employee_tasks.due_date', '<=', $today)
		->orderBy('employee_tasks.order_no')
		->get();
		//return $escalationTasks;
		foreach ($escalationTasks as $task)
		{
			$employeeName = $task->firstname.' '.$task->surname;
						
			if(!empty($task->employee_id))
			{
				# Send Email to employee
				$employee = HRPerson::where('id', $task->employee_id)->first();
				Mail::to($employee->email)->send(new InductionCronEmail($employee, $task));
				
			}
			if(!empty($task->escalation_id))
			{
				# Send Email to escalation person
				$employee = HRPerson::where('id', $task->escalation_id)->first();
				Mail::to($employee->email)->send(new InductionCronEscalationEmail($employee, $task, $employeeName));
				
			}
		}
		
		AuditReportsController::store('Task Management', "Task Started", "Edited by User", 0);
		return back();
    }
}
