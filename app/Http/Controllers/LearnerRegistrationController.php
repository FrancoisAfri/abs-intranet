<?php

namespace App\Http\Controllers;

use App\activity;
use App\ContactPerson;
use App\Country;
use App\projects;
use App\programme;
use App\public_reg;
use App\Learner;
use App\Mail\ConfirmRegistration;
use Illuminate\Http\Request;
use App\Mail\adminEmail;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\Province;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LearnerRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($projectID = -1, $activityID = -1)
    {
         $data['page_title'] = "Learner registration";
        $data['page_description'] = "Add a New Learner registration";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learner registration', 'active' => 1, 'is_module' => 0]
        ];

		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
        $projects = projects::where('status', 2)->orderBy('name', 'asc')->get();
		$schools = DB::table('contacts_companies')->where('company_type', 2)->orderBy('name', 'asc')->get();
		$educators = DB::table('educators')->orderBy('first_name', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['projects'] = $projects;
		$data['activities'] = $activities;
		$data['schools'] = $schools;
		$data['educators'] = $educators;
        $data['project_id'] = $projectID;
        $data['activity_id'] = $activityID;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Learner registration';
		AuditReportsController::store('Clients', 'Learner Registration Form Viewed ', "Actioned By User", 0);
        return view('contacts.learner_registration')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate form input
        $this->validate($request, [
            'first_name' => 'bail|required|min:2',
            'surname' => 'bail|required|min:2',
            'cell_number' => 'required_if:type,2',
            'project_id' => 'required',
            'id_number' => 'required_if:type,2',
            'physical_address' => 'required_if:type,2',
            'school_id' => 'required_if:type,2',
            'school_id' => 'required_if:type,1',
            'gender' => 'required_if:type,1',
        ]);
        $LearnerData = $request->all();
		
        //Exclude empty fields from query
        foreach ($LearnerData as $key => $value)
        {
            if (empty($LearnerData[$key])) {
                unset($LearnerData[$key]);
            }
        }
		//Cell number formatting
		$LearnerData['cell_number'] = str_replace(' ', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace('-', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace('(', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace(')', '', $LearnerData['cell_number']);
        //convert dates to unix time stamp
        if (isset($LearnerData['date_started_project'])) {
            $LearnerData['date_started_project'] = str_replace('/', '-', $LearnerData['date_started_project']);
            $LearnerData['date_started_project'] = strtotime($LearnerData['date_started_project']);
        }
		if (isset($LearnerData['date_of_birth'])) {
            $LearnerData['date_of_birth'] = str_replace('/', '-', $LearnerData['date_of_birth']);
            $LearnerData['date_of_birth'] = strtotime($LearnerData['date_of_birth']);
        }
        //Inset LearnerData data
        $LearnerData = new Learner($LearnerData);
        $LearnerData->active = 1;
        $LearnerData->save();

        //Upload contract document
        if ($request->hasFile('attendance_reg_doc')) {
            $fileExt = $request->file('attendance_reg_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('attendance_reg_doc')->isValid()) {
                $fileName = $LearnerData->id . "_contract." . $fileExt;
                $request->file('attendance_reg_doc')->storeAs('Learner', $fileName);
                //Update file name in the table
                $LearnerData->attendance_reg_doc = $fileName;
                $LearnerData->update();
            }
        }
        //Upload supporting document
        if ($request->hasFile('result_doc')) {
            $fileExt = $request->file('result_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('result_doc')->isValid()) {
                $fileName = $LearnerData->id . "result_doc." . $fileExt;
                $request->file('result_doc')->storeAs('Learner', $fileName);
                //Update file name in the table
                $LearnerData->result_doc = $fileName;
                $LearnerData->update();
            }
        }
		AuditReportsController::store('Clients', 'Learner Registered', "Actioned By User", 0);
		return redirect('/contacts/learner/' . $LearnerData->id . '/edit')->with('success_add', "Learner has been added successfully.");
    }
/*
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Learner $learner)
    {
        $result_doc = $learner->result_doc;
		$attendance_reg_doc = $learner->attendance_reg_doc;
        $data['page_title'] = "View Learner";
        $data['page_description'] = "View/Update Learner details";
        $data['back'] = "/contacts";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts/learner', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learner details', 'active' => 1, 'is_module' => 0]
        ];
		//return $learner;
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$schools = DB::table('contacts_companies')->where('company_type', 2)->orderBy('name', 'asc')->get();
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$educators = DB::table('educators')->orderBy('first_name', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['schools'] = $schools;
		$data['activities'] = $activities;
        $data['educators'] = $educators;
        $data['learner'] = $learner;
		$data['result_doc'] = (!empty($result_doc)) ? Storage::disk('local')->url("Learner/$result_doc") : '';
		$data['attendance_reg_doc'] = (!empty($attendance_reg_doc)) ? Storage::disk('local')->url("Learner/$attendance_reg_doc") : '';
        $data['active_mod'] = 'Clients';
        $data['active_rib'] = 'search';
		AuditReportsController::store('Clients', 'Learner Information Edited', "Actioned By User", 0);
        return view('contacts.learner_view')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Request $request
     * @param  Learner $learner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Learner $learner)
    {
		//die('do you hget here');
       //Validate form input
        $this->validate($request, [
            'first_name' => 'bail|required|min:2',
            'surname' => 'bail|required|min:2',
            'cell_number' => 'required',
        ]);
        $LearnerData = $request->all();
		
        //Exclude empty fields from query
        foreach ($LearnerData as $key => $value)
        {
            if (empty($LearnerData[$key])) {
                unset($LearnerData[$key]);
            }
        }
		//Cell number formatting
		$LearnerData['cell_number'] = str_replace(' ', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace('-', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace('(', '', $LearnerData['cell_number']);
        $LearnerData['cell_number'] = str_replace(')', '', $LearnerData['cell_number']);
		//convert dates to unix time stamp
        if (isset($LearnerData['date_started_project'])) {
            $LearnerData['date_started_project'] = str_replace('/', '-', $LearnerData['date_started_project']);
            $LearnerData['date_started_project'] = strtotime($LearnerData['date_started_project']);
        }
		if (isset($LearnerData['date_of_birth'])) {
            $LearnerData['date_of_birth'] = str_replace('/', '-', $LearnerData['date_of_birth']);
            $LearnerData['date_of_birth'] = strtotime($LearnerData['date_of_birth']);
        }
        //Update Date
		$learner->update($LearnerData);
		
       //Upload contract document
        if ($request->hasFile('attendance_reg_doc')) {
            $fileExt = $request->file('attendance_reg_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('attendance_reg_doc')->isValid()) {
                $fileName = $learner->id . "_contract." . $fileExt;
                $request->file('attendance_reg_doc')->storeAs('Learner', $fileName);
                //Update file name in the table
                $learner->attendance_reg_doc = $fileName;
                $learner->update();
            }
        }
        //Upload supporting document
        if ($request->hasFile('result_doc')) {
            $fileExt = $request->file('result_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('result_doc')->isValid()) {
                $fileName = $learner->id . "result_doc." . $fileExt;
                $request->file('result_doc')->storeAs('Learner', $fileName);
                //Update file name in the table
                $learner->result_doc = $fileName;
                $learner->update();
            }
        }
        //Redirect to all usr view
		AuditReportsController::store('Clients', 'Learner Informations Updated', "Actioned By User", 0);
        return redirect('/contacts/learner/' . $learner->id . '/edit')->with('success_edit', "Learner has been updated successfully.");
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

    public function projectsDropdown(Request $request){
        $projectID = $request->input('option');
        $loadAll = $request->input('load_all');
        $projects = [];
        if ($projectID > 0 && $loadAll == -1) $projects = activity::projectsFromProgramme($projectID);
        elseif ($loadAll == 1) {
            $projects = $projects::where('status', 2)->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        return $projects;
    }
}

