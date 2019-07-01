<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProcurementApproval_steps;
use App\DivisionLevel;
use App\HRPerson;
use App\HRRoles;
use App\DivisionLevelFive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class procurementApprovalsController extends Controller
{
    //
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
        $flow = ProcurementApproval_steps::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;
        $newstep = $flowprocee + 1;
		$divisionFive = DivisionLevelFive::where('active', 1)->orderBy('name', 'desc')->get();
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
		$LevelFive = DivisionLevel::where('active', 1)->where('level', 5)->first();
		$LevelFour = DivisionLevel::where('active', 1)->where('level', 4)->first();
		$LevelTHree = DivisionLevel::where('active', 1)->where('level', 3)->first();
		$LevelTwo = DivisionLevel::where('active', 1)->where('level', 2)->first();
		$LevelOne = DivisionLevel::where('active', 1)->where('level', 1)->first();
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id', 'hr_roles.description as role_name')
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->get();
		
		$processflow = ProcurementApproval_steps::get();
		if (!empty($processflow)) $processflow= $processflow->load('divisionLevelFive','divisionLevelFour',
		'divisionLevelThree','divisionLevelTwo','divisionLevelOne','employeeDetails','roleDetails');
       //return $processflow;
        $data['page_title'] = "Procurement";
        $data['page_description'] = "Steps Approval";
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => 'stock/approval_level', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Approval Steps', 'active' => 1, 'is_module' => 0]
        ];
		$uploadArray = array(1=>"Single Docunent", 2=>"Multiple Docunents");
        $data['uploadArray'] = $uploadArray;
        $data['divisionFives'] = $divisionFive;
        $data['LevelFive'] = $LevelFive;
        $data['LevelFour'] = $LevelFour;
        $data['LevelTHree'] = $LevelTHree;
        $data['LevelTwo'] = $LevelTwo;
        $data['LevelOne'] = $LevelOne;
        $data['employees'] = $employees;
        $data['newstep'] = $newstep;
        $data['roles'] = $roles;
        $data['division_levels'] = $divisionLevels;
        $data['processflows'] = $processflow;
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Approval Steps';
		
		AuditReportsController::store('Procurement', 'Stock Approvals Page Accessed', 'Accessed By User', 0);
        return view('procurement.step_approvals')->with($data);
    }
	
	public function store(Request $request)
    {
        $this->validate($request, [
            'step_name' => 'required',
            'step_number' => 'required',
			'division_level_5' => 'integer|required_if:approval_type,2|min:1',
			'role_id' => 'integer|required_if:approval_type,1|min:1',
			'division_id' => 'integer|required_if:approval_type,1|min:1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $flow = ProcurementApproval_steps::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

        $approvalsLevel = new ProcurementApproval_steps();
        $approvalsLevel->step_number = $flowprocee + 1;
        $approvalsLevel->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $approvalsLevel->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $approvalsLevel->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $approvalsLevel->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $approvalsLevel->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $approvalsLevel->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $approvalsLevel->division_id = !empty($SysData['division_id']) ? $SysData['division_id'] : 0;
        $approvalsLevel->employee_id = !empty($SysData['hr_person_id']) ? $SysData['hr_person_id'] : 0;
        $approvalsLevel->max_amount = !empty($SysData['max_amount']) ? $SysData['max_amount'] : 0;
        $approvalsLevel->role_id = !empty($SysData['role_id']) ? $SysData['role_id'] : 0;
        $approvalsLevel->enforce_upload = !empty($SysData['enforce_upload']) ? $SysData['enforce_upload'] : 0;
        $approvalsLevel->status = 1;
        $approvalsLevel->date_added = time();
        $approvalsLevel->save();

        AuditReportsController::store('Procurement', 'New Step has been added', "Added by user");
        return response()->json();
    }
	
	public function update(Request $request, ProcurementApproval_steps $step)
    {
        $this->validate($request, [
            'step_name' => 'required',
            'step_number' => 'required',
            'division_level_5' => 'integer|required_if:approval_types,2|min:1',
			'role_id' => 'integer|required_if:approval_types,1|min:1',
			'division_id' => 'integer|required_if:approval_type,1|min:1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$type = !empty($SysData['approval_types']) ? $SysData['approval_types'] : 0; 
		if ($type == 2) $SysData['role_id'] = 0;
		else
			$SysData['division_level_4'] = $SysData['division_level_5'] = $SysData['hr_person_id'] = 0;
        $step->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $step->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $step->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $step->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $step->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $step->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $step->division_id = !empty($SysData['division_id']) ? $SysData['division_id'] : 0;
		$step->employee_id = !empty($SysData['hr_person_id']) ? $SysData['hr_person_id'] : 0;
        $step->max_amount = !empty($SysData['max_amount']) ? $SysData['max_amount'] : 0;
        $step->role_id = !empty($SysData['role_id']) ? $SysData['role_id'] : 0;
        $step->enforce_upload = !empty($SysData['enforce_upload']) ? $SysData['enforce_upload'] : 0;
        $step->update();

        AuditReportsController::store('Procurement', 'Step edited', "Proces flow Edited");
        return response()->json();
    }
	public function steps_act(ProcurementApproval_steps $step)
    {
        if ($step->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $step->status = $stastus;
        $step->update();

        AuditReportsController::store('Procurement', ' process flow status Changed', "Proces flow Status changed", $step->id);
        return back();
    }
}