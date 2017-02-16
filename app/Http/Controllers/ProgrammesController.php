<?php

namespace App\Http\Controllers;

use App\contacts_company;
use App\HRPerson;
use App\Mail\ApprovedProgramme;
use App\Mail\ProgrammeComplete;
use App\Mail\ProgrammeGMApproval;
use App\Mail\RejectedProgramme;
use App\programme;
use App\programme_incomes;
use App\User;
use App\programme_expenditures;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProgrammesController extends Controller
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
        $programmeManagers = HRPerson::where('status', 1)->get();
        $serviceProviders = contacts_company::where('status', 3)->where('company_type', 1)->orderBy('name')->get();
        //$serviceProviders = (object) array();
		$programmeID = DB::table('programmes')->orderBy('id', 'desc')->limit(1)->get()->first();
		
        $data['page_title'] = "Programmes";
        $data['page_description'] = "Register a New Programme";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Register programme', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'add new programme';
        $data['programme_managers'] = $programmeManagers;
        $data['service_providers'] = $serviceProviders;
        $data['programmeCode'] = $programmeID;
		//return $data;
		AuditReportsController::store('Programmes', 'View Programme Creation Page', "Actioned By User", 0);
        return view('education.add_programme')->with($data);
    }
    public function createProject()
    {
        $data['page_title'] = "Projects";
        $data['page_description'] = "Register a New Project";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Project', 'path' => '/education/project', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Register project', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'add new project';
		AuditReportsController::store('Programmes', 'View Project Creation Page', "Actioned By User", 0);
        return view('education.add_project')->with($data);
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
            'budget_expenditure' => 'bail|numeric|min:0.1',
            'budget_income' => 'bail|numeric|min:0.1',
            'sponsorship_amount' => 'bail|numeric|min:0.1',
            'contract_amount' => 'bail|numeric|min:0.1',
            'manager_id' => 'required',
            


        ]);
        $programmeData = $request->all();

        //Exclude empty fields from query
        foreach ($programmeData as $key => $value)
        {
            if (empty($programmeData[$key])) {
                unset($programmeData[$key]);
            }
        }

        //convert dates to unix time stamp
        if (isset($programmeData['start_date'])) {
            $programmeData['start_date'] = str_replace('/', '-', $programmeData['start_date']);
            $programmeData['start_date'] = strtotime($programmeData['start_date']);
        }
        if (isset($programmeData['end_date'])) {
            $programmeData['end_date'] = str_replace('/', '-', $programmeData['end_date']);
            $programmeData['end_date'] = strtotime($programmeData['end_date']);
        }

        //convert numeric values to numbers
        if (isset($programmeData['budget_expenditure'])) {
            $programmeData['budget_expenditure'] = (double) $programmeData['budget_expenditure'];
        }
        if (isset($programmeData['budget_income'])) {
            $programmeData['budget_income'] = (double) $programmeData['budget_income'];
        }
        if (isset($programmeData['sponsorship_amount'])) {
            $programmeData['sponsorship_amount'] = (double) $programmeData['sponsorship_amount'];
        }
        if (isset($programmeData['contract_amount'])) {
            $programmeData['contract_amount'] = (double) $programmeData['contract_amount'];
        }

        //Inset programme data
        $programme = new programme($programmeData);
        $programme->status = 1;
        $programme->user_id = Auth::user()->id;
        $programme->save();

        //Upload contract document
        if ($request->hasFile('contract_doc')) {
            $fileExt = $request->file('contract_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('contract_doc')->isValid()) {
                $fileName = $programme->id . "_contract." . $fileExt;
                $request->file('contract_doc')->storeAs('programmes', $fileName);
                //Update file name in the table
                $programme->contract_doc = $fileName;
                $programme->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $programme->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('programmes', $fileName);
                //Update file name in the table
                $programme->supporting_doc = $fileName;
                $programme->update();
            }
        }

        //Send email to General manager for approval
        $notifConf = '';
        $generalManagers = HRPerson::where('position', 1)->get();
        if(count($generalManagers) > 0) {
            $generalManagers->load('user');
            foreach ($generalManagers as $generalManager) {
                $gmEmail = $generalManager->email;
				AuditReportsController::store('Programmes', 'Programme Approval Sent', "Sent TO $user->person->first_name $user->person->surname for $programme->id", 0);
                Mail::to($gmEmail)->send(new ProgrammeGMApproval($generalManager, $programme->id));
            }
            $notifConf = " \nA request for approval has been sent to the General Manager(s).";
        }
		AuditReportsController::store('Programmes', 'Programme Created', "Programme successfully Added", 0);
        return redirect('/education/programme/' . $programme->id . '/view')->with('success_add', "The Programme has been added successfully.$notifConf");
    }

    /**
     * Display the specified resource.
     *
     * @param  programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function show(programme $programme)
    {
        $programme->load('expenditure', 'income');
        $programmeManagers = HRPerson::where('id', $programme->manager_id)->get();
        $serviceProviders = contacts_company::where('id', $programme->service_provider_id)->orderBy('name')->get();
        $programmeStatus = [-1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
        $statusLabels = [-1 => "callout-danger", 1 => "callout-warning", 2 => 'callout-success', 3 => 'callout-info'];
        $contractDoc = $programme->contract_doc;
        $supportingDoc = $programme->supporting_doc;
        $user = Auth::user()->load('person');
        //$showEdit = (in_array($programme->status, [-1, 1]) && in_array($user->type, [1, 3]) && in_array($user->person->position, [1, 2, 3])) ? true : false;
        $showEdit = false;
        $showApproveReject = ($programme->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 1) ? true : false;
        $showComplete = ($programme->status === 2 && in_array($user->type, [1, 3])) ? true : false;

        $data['page_title'] = "Programmes";
        $data['page_description'] = "View Program Details";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'View programme', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'education';
        $data['active_rib'] = 'search';
        $data['programme'] = $programme;
        $data['programme_managers'] = $programmeManagers;
        $data['service_providers'] = $serviceProviders;
        $data['status_strings'] = $programmeStatus;
        $data['status_labels'] = $statusLabels;
        $data['contract_doc'] = (!empty($contractDoc)) ? Storage::disk('local')->url("programmes/$contractDoc") : '';
        $data['supporting_doc'] = (!empty($supportingDoc)) ? Storage::disk('local')->url("programmes/$supportingDoc") : '';
        $data['show_edit'] = $showEdit;
        $data['show_approve'] = $showApproveReject;
        $data['show_complete'] = $showComplete;
		AuditReportsController::store('Programmes', 'View Programme', "Programme Informations Accessed By User", 0);
        return view('education.view_programme')->with($data);
    }

    /**
     * Reject a loaded Programme.
     *
     * @param  Request $request
     * @param  programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, programme $programme)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to reject the programme
        if ($programme->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 1) {
            //Validate reason
            $this->validate($request, [
                'rejection_reason' => 'required'
            ]);

            //Update status to rejected
            $programme->status = -1;
            $programme->rejection_reason = $request['rejection_reason'];
            $programme->update();

            //Notify the applicant about the rejection
            $creator = User::find("$programme->user_id")->load('person');
            $creatorEmail = $creator->person->email;
            Mail::to($creatorEmail)->send(new RejectedProgramme($creator, $request['rejection_reason'], $programme->id));
			AuditReportsController::store('Programmes', 'Programme Rejected', "$request[rejection_reason]", 0);
            return response()->json(['programme_rejected' => $programme], 200);
        }
        else return response()->json(['error' => ['Unauthorized user or illegal programme status type']], 422);
    }

    /**
     * Approve a loaded Programme.
     *
     * @param  programme  $programme
     * @return \Illuminate\Http\Response
     */
    public function approve(programme $programme)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to approve the programme
        if ($programme->status === 1 && in_array($user->type, [1, 3]) && $user->person->position === 1) {
            //Update status to rejected
            $programme->status = 2;
            $programme->approver_id = $user->id;
            $programme->update();

            //Notify the applicant about the approval
            $creator = User::find("$programme->user_id")->load('person');
            $creatorEmail = $creator->person->email;
            Mail::to($creatorEmail)->send(new ApprovedProgramme($creator, $programme->id));
			AuditReportsController::store('Programmes', 'Programme Approved', "Programme Approved By User", 0);
            return redirect('/education/programme/' . $programme->id . '/view')->with('success_approve', "The Programme has been successfully approved. \nA confirmation has been sent to the person who loaded the Programme.");
        }
        else return redirect('/');
    }

    /**
     * Complete a programme.
     *
     * @param  Request $request
     * @param  programme $programme
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request, programme $programme)
    {
        $user = Auth::user()->load('person');

        //check if logged in user is allowed to complete the programme
        if ($programme->status === 2 && in_array($user->type, [1, 3])) {
            //Validate comment
            $this->validate($request, [
                'comment' => 'required'
            ]);

            //Update programme to completed
            $programme->status = 3;
            $programme->comment = $request['comment'];
            $programme->update();

            //Notify the General Manager about the completion
            $generalManagers = HRPerson::where('position', 1)->get();
            if(count($generalManagers) > 0) {
                $generalManagers->load('user');
                foreach ($generalManagers as $generalManager) {
                    $gmEmail = $generalManager->email;
                    Mail::to($gmEmail)->send(new ProgrammeComplete($generalManager, $programme->id));
                }
            }
			AuditReportsController::store('Programmes', 'Programme Completed', "Programme Completed By User", 0);
            return response()->json(['programme_completed' => $programme], 200);
        }
        else return response()->json(['error' => ['Unauthorized user or illegal programme status type']], 422);
    }

    //

    //

	public function saveExpenditure(Request $request, programme $programme) {
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
        $expenditure = new programme_expenditures($projectData);
        $expenditure->payee = $projectData['supplier_name'];
        $programme->addExpenditure($expenditure);

        //upload doc
        //Upload supporting document
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $expenditure->id . "_supporting_doc." . $fileExt;
                $request->file('supporting_doc')->storeAs('programmes/expenditures', $fileName);
                //Update file name in the table
                $expenditure->supporting_doc = $fileName;
                $expenditure->update();
            }
        }
	}
//
	public function saveIncome(Request $request, programme $programme){

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
        if (isset($projectData['amount'])) {
            $projectData['amount'] = (double) $projectData['amount'];
        }

         //Inset project data // not sure.......
        $income = new programme_incomes();
        $income->payer = $projectData['inc_supplier_name'];
        $income->amount = $projectData['inc_amount'];
        $income->date_added = $projectData['inc_date_added'];

        $programme->addIncome($income);
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

    //

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
