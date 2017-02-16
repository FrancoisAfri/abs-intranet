<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\HRPerson;
use App\Mail\ActivityComplete;
use App\Mail\ActivityELMApproval;
use App\Mail\ApprovedActivity;
use App\Mail\RejectedActivity;
use App\programme;
use App\projects;
use App\User;
use App\activity_expenditures;
use App\activity_incomes;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ActivitiesController extends Controller
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
    public function create()
    {
        $serviceProviders = contacts_company::where('status', 1)->where('company_type', 1)->orderBy('name')->get();
        $facilitators = HRPerson::where('position', 6)->where('status', 1)->orderBy('first_name', 'asc')->get();
        $programmes = programme::where('status', 2)->orderBy('name', 'asc')->get();
        $projects = projects::where('status', 2)->orderBy('name', 'asc')->get();
		$activityID = DB::table('activities')->orderBy('id', 'desc')->limit(1)->get()->first();

        $data['page_title'] = "Activities";
        $data['page_description'] = "Register a New Activity";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Activity', 'path' => '/education/activity', 'icon' => 'fa fa-trophy', 'active' => 0, 'is_module' => 0],
            ['title' => 'Register activity', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'add new activity';
        $data['service_providers'] = $serviceProviders;
        $data['facilitators'] = $facilitators;
        $data['programmes'] = $programmes;
        $data['projects'] = $projects;
		$data['activityCode'] = $activityID;
		AuditReportsController::store('Programmes', 'View Create Activities Page', "Tried To Create Activity", 0);
        return view('education.add_activity')->with($data);
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
            'name' => 'bail|required|min:2',
            'start_date' => 'bail|required|date_format:"d/m/Y"',
            'end_date' => 'bail|required|date_format:"d/m/Y"',
            'budget' => 'bail|numeric|min:0.1',
            'actual_cost' => 'bail|numeric|min:0.1',
            'sponsorship_amount' => 'bail|numeric|min:0.1',
            'contract_amount' => 'bail|numeric|min:0.1',
            'project_id' => 'required',
        ]);
        $activityData = $request->all();

        //Exclude empty fields from query
        foreach ($activityData as $key => $value)
        {
            if (empty($activityData[$key])) {
                unset($activityData[$key]);
            }
        }

        //convert dates to unix time stamp
        if (isset($activityData['start_date'])) {
            $activityData['start_date'] = str_replace('/', '-', $activityData['start_date']);
            $activityData['start_date'] = strtotime($activityData['start_date']);
        }
        if (isset($activityData['end_date'])) {
            $activityData['end_date'] = str_replace('/', '-', $activityData['end_date']);
            $activityData['end_date'] = strtotime($activityData['end_date']);
        }

        //convert numeric values to numbers
        if (isset($activityData['budget'])) {
            $activityData['budget'] = (double) $activityData['budget'];
        }
        if (isset($activityData['actual_cost'])) {
            $activityData['actual_cost'] = (double) $activityData['actual_cost'];
        }
        if (isset($activityData['sponsorship_amount'])) {
            $activityData['sponsorship_amount'] = (double) $activityData['sponsorship_amount'];
        }
        if (isset($activityData['contract_amount'])) {
            $activityData['contract_amount'] = (double) $activityData['contract_amount'];
        }

        //Inset activity data
        $activity = new activity($activityData);
        $activity->status = 1;
        $activity->user_id = Auth::user()->id;
        $activity->save();

        //Upload contract document
        if ($request->hasFile('contract_doc')) {
            $fileExt = $request->file('contract_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('contract_doc')->isValid()) {
                $fileName = $activity->id . "_contract." . $fileExt;
                $request->file('contract_doc')->storeAs('activities', $fileName);
                //Update file name in the table
                $activity->contract_doc = $fileName;
                $activity->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $activity->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('activities', $fileName);
                //Update file name in the table
                $activity->supporting_doc = $fileName;
                $activity->update();
            }
        }

        //Send email to project manager for approval
        $notifConf = '';
        //$managerID = $activity->project->manager_id;
        $managerID = DB::table('projects')->select('manager_id')->where('id', $activity->project_id)->get();
        if (count($managerID) > 0) $managerID = $managerID->first()->manager_id;
        else $managerID = null;
        $elManagers = [];
        if ($managerID != null) $elManagers = HRPerson::where('id', $managerID)->get();
        if(count($elManagers) > 0) {
            $elManagers->load('user');
            foreach ($elManagers as $elManager) {
                $elmEmail = $elManager->email;
                Mail::to($elmEmail)->send(new ActivityELMApproval($elManager, $activity->id));
            }
            $notifConf = " \nA request for approval has been sent to the Project Manager(s).";
        }
		AuditReportsController::store('Programmes', 'Activity Created', "New Activity Successfully Added", 0);
        return redirect('/education/activity/' . $activity->id . '/view')->with('success_add', "The Activity has been added successfully.$notifConf");
    }

    /**
     * Display the specified resource.
     *
     * @param  activity $activity
     * @return \Illuminate\Http\Response
     */
    public function show(activity $activity)
    {
        $activity->load('expenditure', 'income');
        $activity->load('programme', 'project', 'facilitator', 'serviceProvider');
        //return $activity;
        $activityStatus = [-1 => "Rejected", 1 => "Pending Project Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
        $statusLabels = [-1 => "callout-danger", 1 => "callout-warning", 2 => 'callout-success', 3 => 'callout-info'];
        $contractDoc = $activity->contract_doc;
        $supportingDoc = $activity->supporting_doc;
        $user = Auth::user()->load('person');
        //$showEdit = (in_array($programme->status, [-1, 1]) && in_array($user->type, [1, 3]) && in_array($user->person->position, [1, 2, 3, 4])) ? true : false;
        $showEdit = false;
        $showApproveReject = ($activity->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 4) ? true : false;
        $showComplete = ($activity->status === 2 && in_array($user->type, [1, 3])) ? true : false;

        $data['page_title'] = "Activities";
        $data['page_description'] = "View Activity Details";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education/search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'View activity', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'programmes';
        $data['active_rib'] = 'search';
        $data['activity'] = $activity;
        $data['status_strings'] = $activityStatus;
        $data['status_labels'] = $statusLabels;
        $data['contract_doc'] = (!empty($contractDoc)) ? Storage::disk('local')->url("activities/$contractDoc") : '';
        $data['supporting_doc'] = (!empty($supportingDoc)) ? Storage::disk('local')->url("activities/$supportingDoc") : '';
        $data['show_edit'] = $showEdit;
        $data['show_approve'] = $showApproveReject;
        $data['show_complete'] = $showComplete;
		AuditReportsController::store('Programmes', 'Activity Viewed', "Activity Viewed", 0);
        return view('education.view_activity')->with($data);
    }

    /**
     * Reject a loaded activity.
     *
     * @param  Request $request
     * @param  activity $activity
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, activity $activity)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to reject the activity
        if ($activity->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 4) {
            //Validate reason
            $this->validate($request, [
                'rejection_reason' => 'required'
            ]);

            //Update status to rejected
            $activity->status = -1;
            $activity->rejection_reason = $request['rejection_reason'];
            $activity->update();
			$reason = $request['rejection_reason'];
            //Notify the applicant about the rejection
            $creator = User::find("$activity->user_id")->load('person');
            $creatorEmail = $creator->person->email;
            Mail::to($creatorEmail)->send(new RejectedActivity($creator, $request['rejection_reason'], $activity->id));
			AuditReportsController::store('Programmes', 'Activity Rejected', "$reason", 0);
            return response()->json(['programme_rejected' => $activity], 200);
        }
        else return response()->json(['error' => ['Unauthorized user or illegal activity status type']], 422);
    }

    /**
     * Approve a loaded activity.
     *
     * @param  activity $activity
     * @return \Illuminate\Http\Response
     */
    public function approve(activity $activity)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to approve the activity
        if ($activity->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 4) {
            //Update status to approved
            $activity->status = 2;
            $activity->approver_id = $user->id;
            $activity->update();

            //Notify the loader about the approval
            $creator = User::find("$activity->user_id")->load('person');
            $creatorEmail = $creator->person->email;
            Mail::to($creatorEmail)->send(new ApprovedActivity($creator, $activity->id));
			AuditReportsController::store('Programmes', 'Activity Approved', "Approved By User", 0);
            return redirect('/education/activity/' . $activity->id . '/view')->with('success_approve', "The Activity has been successfully approved. \nA confirmation has been sent to the person who loaded the Activity.");
        }
        else return redirect('/');
    }

    /**
     * Complete an activity.
     *
     * @param  Request $request
     * @param  activity $activity
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, activity $activity)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to complete the activity
        if ($activity->status === 2 && in_array($user->type, [1, 3])) {
            //Validate comment
            $this->validate($request, [
                'comment' => 'required'
            ]);

            //Update status to completed
            $activity->status = 3;
            $activity->comment = $request['comment'];
            $activity->update();

            //Notify the E&L Manager about the completion
            $elManagers = HRPerson::where('position', 4)->get();
            if(count($elManagers) > 0) {
                $elManagers->load('user');
                foreach ($elManagers as $elManager) {
                    $elmEmail = $elManager->email;
                    Mail::to($elmEmail)->send(new ActivityComplete($elManager, $activity->id));
                }
            }
			AuditReportsController::store('Programmes', 'Activity Completed', "Completion By User", 0);
            return response()->json(['activity_completed' => $activity], 200);
        }
        else return response()->json(['error' => ['Unauthorized user or illegal activity status type']], 422);
    }

    public function saveExpenditure(Request $request, activity $activity) {
        //validation
//Validate form input
        $this->validate($request, [
            'amount' => 'bail|required|numeric|min:0.1',
            'date_added' => 'bail|required|date_format:"d/m/Y"',
            'supplier_name' => 'bail|required|min:2"',
        ]);
        $projectData = $request->all();

         //Exclude empty fields from query
        foreach ($projectData as $key => $value)
        {
            if (empty($projectData[$key])) {
                unset($projectData[$key]);
            }
        }

        //convert dates to unix time stamp
        if (isset($projectData['date_added'])) {
            $projectData['date_added'] = str_replace('/', '-', $projectData['date_added']);
            $projectData['date_added'] = strtotime($projectData['date_added']);
        }

        //convert numeric values to numbers
        if (isset($projectData['amount'])) {
            $projectData['amount'] = (double) $projectData['amount'];
        }

         //Inset project data // not sure.......
        $expenditure = new activity_expenditures($projectData);
        $expenditure->payee = $projectData['supplier_name'];
        $activity->addExpenditure($expenditure);

        //upload doc
        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $expenditure->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('activity/expenditures', $fileName);
                //Update file name in the table
                $expenditure->supporting_doc = $fileName;
                $expenditure->update();
            }
        }
    }


//
    // add activity expenditure
	public function addExpenditure(Request $request, activity $activity){
        //validation
       //Validate form input
        $this->validate($request, [
            'amount' => 'bail|required|numeric|min:0.1',
            'date_added' => 'bail|required|date_format:"d/m/Y"',
            'supplier_name' => 'bail|required|min:2"',
        ]);
		$projectData = $request->all();

         //Exclude empty fields from query
        foreach ($projectData as $key => $value)
        {
            if (empty($projectData[$key])) {
                unset($projectData[$key]);
            }
        }

        //convert dates to unix time stamp
               if (isset($projectData['date_added'])) {
            $projectData['date_added'] = str_replace('/', '-', $projectData['date_added']);
            $projectData['date_added'] = strtotime($projectData['date_added']);
        }

        //convert numeric values to numbers
        if (isset($projectData['amount'])) {
            $projectData['amount'] = (double) $projectData['amount'];
        }

           //Inset project data // not sure.......
        $expenditure = new activity_expenditures($projectData);
        $expenditure->payee = $projectData['supplier_name'];
        $activity->addExpenditure($expenditure);

        //upload doc
        //Upload supporting document
             if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $expenditure->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('projects/expenditures', $fileName);
                //Update file name in the table
                $expenditure->supporting_doc = $fileName;
                $expenditure->update();
            }
        }
   }


//
   //
	public function saveIncome(Request $request, activity $activity){
		//validate

		$this->validate($request, [
            'inc_amount' => 'bail|required|numeric|min:0.1',
            'inc_date_added' => 'bail|required|date_format:"d/m/Y"',
            'inc_supplier_name' => 'bail|required|min:2"',
        ]);
        $projectData = $request->all();

         //Exclude empty fields from query
        foreach ($projectData as $key => $value)
        {
            if (empty($projectData[$key])) {
                unset($projectData[$key]);
            }
        }

        //convert dates to unix time stamp
        if (isset($projectData['inc_date_added'])) {
            $projectData['inc_date_added'] = str_replace('/', '-', $projectData['inc_date_added']);
            $projectData['inc_date_added'] = strtotime($projectData['inc_date_added']);
        }

        //convert numeric values to numbers
        if (isset($projectData['inc_amount'])) {
            $projectData['inc_amount'] = (double) $projectData['inc_amount'];
        }

         //Inset project data // not sure.......
        $income = new activity_incomes();
        $income->payer = $projectData['inc_supplier_name'];
        $income->amount = $projectData['inc_amount'];
        $income->date_added = $projectData['inc_date_added'];

        $activity->addIncome($income);

        //upload doc
        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $income->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('programmes/incomes', $fileName);
                //Update file name in the table
                $income->supporting_doc = $fileName;
                $income->update();
            }
        }
    }

    public function activityDD(Request $request)
    {
        $projectID = $request->input('option');
        $loadAll = $request->input('load_all');
        $activities = [];
        if ($projectID > 0 && $loadAll == -1) $activities = activity::activitiesFromProject($projectID);
        elseif ($loadAll == 1) {
            $activities = activity::where('status', 2)->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        return $activities;
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
