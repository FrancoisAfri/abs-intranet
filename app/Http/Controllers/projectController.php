<?php

namespace App\Http\Controllers;

use App\projects_expenditures;
use App\projects_incomes;
use Illuminate\Http\Request;
use App\contacts_company;
use App\HRPerson;
use App\projects;
use App\User;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Mail\EducatorManagerMail;
use App\Mail\ProjectReApprovalMail;
use App\Mail\ProjectRejectionMail;
use App\Mail\ProjectComplete;

class projectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $data['page_title'] = "Projects";
        $data['page_description'] = "Register a New Project";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Project', 'path' => '/education/project', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Register project', 'active' => 1, 'is_module' => 0]
        ];
        $programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
        $projectID = DB::table('projects')->orderBy('id', 'desc')->limit(1)->get()->first();
        $facilitators = DB::table('hr_people')->where('status', 1)->where('position', 6)->orderBy('first_name', 'asc')->get();
        $managers = DB::table('hr_people')->where('status', 1)->where('position', 5)->orderBy('first_name', 'asc')->get();
        $serviceProviders = contacts_company::where('status', 3)->where('company_type', 1)->orderBy('name', 'asc')->get();
        $data['programmes'] = $programmes;
        $data['facilitators'] = $facilitators;
        $data['managers'] = $managers;
        $data['service_providers'] = $serviceProviders;
		$data['projectCode'] = $projectID;
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Add New Project';
        AuditReportsController::store('Programmes', 'project Creation Page Accessed', "Accessed By User", 0);
        return view('education.add_project')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
            'sponsorship_amount' => 'bail|numeric|min:0.1',
            'contract_amount' => 'bail|numeric|min:0.1',
            'manager_id' => 'required',
            'programme_id' => 'required',
            'facilitator_id' => 'required',
        ]);

        $projectData = $request->all();

        foreach ($projectData as $key => $value) {
            if (empty($projectData[$key])) {
                unset($projectData[$key]);
            }
        }
        //convert dates to unix time stamp
        if (isset($projectData['start_date'])) {
            $projectData['start_date'] = str_replace('/', '-', $projectData['start_date']);
            $projectData['start_date'] = strtotime($projectData['start_date']);
        }
        if (isset($projectData['end_date'])) {
            $projectData['end_date'] = str_replace('/', '-', $projectData['end_date']);
            $projectData['end_date'] = strtotime($projectData['end_date']);
        }
        //convert numeric values to numbers
        if (isset($projectData['budget_expenditure'])) {
            $projectData['budget_expenditure'] = (double)$projectData['budget_expenditure'];
        }
        if (isset($projectData['budget_income'])) {
            $projectData['budget_income'] = (double)$projectData['budget_income'];
        }
        if (isset($projectData['sponsorship_amount'])) {
            $projectData['sponsorship_amount'] = (double)$projectData['sponsorship_amount'];
        }
        if (isset($projectData['contract_amount'])) {
            $projectData['contract_amount'] = (double)$projectData['contract_amount'];
        }

        //Inset project data
        $project = new projects($projectData);
        $project->status = 1;
        $project->user_id = Auth::user()->id;
        $project->save();
        //Upload contract document
        if ($request->hasFile('contract_doc')) {
            $fileExt = $request->file('contract_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('contract_doc')->isValid()) {
                $fileName = $project->id . "_contract." . $fileExt;
                $request->file('contract_doc')->storeAs('projects', $fileName);
                //Update file name in the table
                $project->contract_doc = $fileName;
                $project->update();
            }
        }

        //Upload supporting Documents
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $project->id . "_supporting." . $fileExt;
                $request->file('supporting_doc')->storeAs('projects', $fileName);
                //Update file name in hr table
                $project->supporting_doc = $fileName;
                $project->update(['supporting_doc' => $fileName]);
            }
        }
        # Email EL Manager
        $EducationManager = DB::table('hr_people')->where('position', 4)->orderBy('id', 'asc')->get();
        //; ->toSql()
        $notifConf = '';
        if (count($EducationManager) > 0) {
            foreach ($EducationManager as $user) {
                AuditReportsController::store('Programmes', 'project Approval Sent', "Sent TO $user->first_name $user->surname for $project->id", 0);
                Mail::to($user->email)->send(new EducatorManagerMail($user->first_name, $project->id));
            }
            $notifConf = " \nA request for approval has been sent to the Education & Learning Manager(s).";
        }
        AuditReportsController::store('Programmes', 'project Created', "Created By User", 0);
        return redirect("/project/view/$project->id")->with('success_add', "The Project has been added successfully.$notifConf");
    }

    /**
     * Display the specified resource.
     *
     * @param  projects $project
     * @return \Illuminate\Http\Response
     */
    public function projectView(projects $project)
    {
        //return $project;
        $project->load('serviceProvider', 'manager', 'facilitator', 'programme', 'income', 'expenditure');
        $supporting_doc = $project->supporting_doc;
        $contract_doc = $project->contract_doc;
        $user = Auth::user()->load('person');
        if (($user->person->position != 4) && $user->type != 3)
            die("Sorry you do not have access to view data on this page please return to dashboard");
        elseif (($user->person->position == 4) || $user->type == 3 && $project->status == 1) $showButton = 1;
        else $showButton = 0;
        $projectStatus = [-1 => "Rejected", 1 => "Pending Education & Learning Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
        $statusLabels = [-1 => "callout-danger", 1 => "callout-warning", 2 => 'callout-success', 3 => 'callout-info'];
        $showApproveReject = ($project->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 4) ? true : false;
        $showComplete = ($project->status === 2 && in_array($user->type, [1, 3])) ? true : false;

        $data['page_title'] = "Project View";
        $data['page_description'] = "View Project Details";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education/programmes', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'View project', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'programmes';
        $data['showButton'] = $showButton;
        $data['projects'] = $project;
        $data['supporting_doc'] = (!empty($supporting_doc)) ? Storage::disk('local')->url("projects/$supporting_doc") : '';
        $data['contract_doc'] = (!empty($contract_doc)) ? Storage::disk('local')->url("projects/$contract_doc") : '';
        $data['active_rib'] = 'search';
        $data['status_strings'] = $projectStatus;
        $data['status_labels'] = $statusLabels;
        $data['show_approve'] = $showApproveReject;
        $data['show_complete'] = $showComplete;
        AuditReportsController::store('Programmes', 'project Informations Accessed', "Accessed By User", 0);
        return view('education.project_view')->with($data);
    }

    public function approveProject(Request $request, projects $project)
    {
        $user = Auth::user()->load('person');

        if (($user->type === 1 && $user->person->position === 4) || $user->type === 3) {
            $project->status = 2;
            $project->approver_id = $user->id;
            $project->update();

            //Notify the applicant about the approval
            $applicant = User::find($project->user_id)->load('person');
            $applicantEmail = $applicant->person->email;
            Mail::to($applicantEmail)->send(new ProjectReApprovalMail($applicant));

            AuditReportsController::store('Programmes', 'project Approved', "Approved By User", 0);
            return redirect("/project/view/$project->id")->with('success_approve', "The Project has been successfully approved. \nA confirmation has been sent to the person who loaded the Project.");
        }
    }

    public function rejectProject(Request $request, projects $project)
    {
        $this->validate($request, [
            'rejection_reason' => 'required'
        ]);
        $user = Auth::user()->load('person');
        if (($user->type === 1 && $user->person->position === 4) || $user->type === 3) {
            $project->status = -1;
            $project->rejection_reason = $request['rejection_reason'];
            $project->approver_id = $user->id;
            $project->update();
            //Notify the applicant about the rejection
            $applicant = User::find("$project->user_id")->load('person');
            $applicantEmail = $applicant->person->email;
            Mail::to("$applicantEmail")->send(new ProjectRejectionMail($applicant, $request['rejection_reason'], $project->id));
            AuditReportsController::store('Programmes', 'project Rejected', "Rejected By User", 0);
            return response()->json();
        }
    }

    /**
     * Complete a programme.
     *
     * @param  Request $request
     * @param  projects $project
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, projects $project)
    {
        $user = Auth::user()->load('person');
        //check if logged in user is allowed to complete the programme
        if ($project->status === 2 && in_array($user->type, [1, 3])) {
            //Validate comment
            $this->validate($request, [
                'comment' => 'required'
            ]);

            //Update status to completed
            $project->status = 3;
            $project->comment = $request['comment'];
            $project->update();

            //Notify the General Manager about the completion
            $educationManagers = HRPerson::where('position', 4)->get();
            if (count($educationManagers) > 0) {
                $educationManagers->load('user');
                foreach ($educationManagers as $educationManager) {
                    $emEmail = $educationManager->email;
                    Mail::to($emEmail)->send(new ProjectComplete($educationManager, $project->id));
                }
            }
            AuditReportsController::store('Programmes', 'Project Completed', "Project Completed By User", 0);
            return response()->json(['project _completed' => $project], 200);
        } else return response()->json(['error' => ['Unauthorized user or illegal Project status type']], 422);
    }

    public function saveExpenditure(Request $request, projects $project)
    {
        //validation
        $this->validate($request, [
            'amount' => 'bail|required|numeric|min:0.1',
            'date_added' => 'bail|required|date_format:"d/m/Y"',
            'supplier_name' => 'bail|required|min:2"',
        ]);
        $projectData = $request->all();

        //Exclude empty fields from query
        foreach ($projectData as $key => $value) {
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
            $projectData['amount'] = (double)$projectData['amount'];
        }

        //Inset project data
        $expenditure = new projects_expenditures($projectData);
        $expenditure->payee = $projectData['supplier_name'];
        $project->addExpenditure($expenditure);

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

    public function saveIncome(Request $request, projects $project)
    {
        //validate
        $this->validate($request, [
            'inc_amount' => 'bail|required|numeric|min:0.1',
            'inc_date_added' => 'bail|required|date_format:"d/m/Y"',
            'inc_supplier_name' => 'bail|required|min:2"',
        ]);
        $projectData = $request->all();

        //Exclude empty fields from query
        foreach ($projectData as $key => $value) {
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
            $projectData['inc_amount'] = (double)$projectData['inc_amount'];
        }

        //Inset project data
        $income = new projects_incomes();
        $income->payer = $projectData['inc_supplier_name'];
        $income->amount = $projectData['inc_amount'];
        $income->date_added = $projectData['inc_date_added'];

        $project->addIncome($income);

    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
