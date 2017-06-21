<?php

namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\ContactCompany;
use App\HRPerson;
use App\User;
use App\ClientInduction;
use App\EmployeeTasks;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InductionGroupTaskEmail;
use App\Http\Requests;

class meetingMinutesAdminController extends Controller
{
    
	public function __construct()
    {
        $this->middleware('auth');
    }
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $libraries = DB::table('task_libraries')
		->orderBy('dept_id', 'asc')
		->orderBy('order_no', 'asc')
		->get();
		$companies = ContactCompany::where('status', 2)->orderBy('name', 'asc')->get();
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
        $data['page_title'] = "Meeting Minutes";
        $data['page_description'] = "Create Meeting Minutes";
        $data['breadcrumb'] = [
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Meeting Minutes', 'path' => '/meeting_minutes/create', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Create Minutes', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Meeting Minutes';
        $data['active_rib'] = 'Create Minutes';
		
		
		$data['users'] = $employees;
		$data['companies'] = $companies;
		$data['libraries'] = $libraries;
		AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('meeting_minutes.add_new_meeting')->with($data);
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
