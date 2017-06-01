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
use App\Mail\EmployeesTasksMail;
use App\Mail\NextTaskNotifications;
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
		$oldTask = DB::table('employee_tasks')
		->select('employee_tasks.status','employee_tasks.order_no','employee_tasks.id')
		->where('employee_tasks.due_date', $today)
		->where('employee_tasks.order_no', $order)
		->orderBy('employee_tasks.order_no')
		->first();
		
		$OnProgress = DB::table('employee_tasks')
		->select('employee_tasks.id')
		->where('employee_tasks.employee_id', $user->person->id)
		->where('employee_tasks.status', 2)
		->first();
		if (!empty($OnProgress->id))
			return redirect('/')->with('error_starting', "You can not start this task, You have another task in progess.");
		$stastus = 2;
		$task->status = $stastus;	
		$task->date_started = strtotime(date('Y-m-d'));	
		$task->update();
		AuditReportsController::store('Task Management', "Task Started", "Edited by User", 0);
		return back();
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
}
