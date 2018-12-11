<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock_Approvals_level;
use App\DivisionLevel;
use App\HRPerson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class StockApprovalsController extends Controller
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
        $flow = Stock_Approvals_level::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;
        $newstep = $flowprocee + 1;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
		$LevelFive = DivisionLevel::where('active', 1)->where('level', 5)->first();
		$LevelFour = DivisionLevel::where('active', 1)->where('level', 4)->first();
		$LevelTHree = DivisionLevel::where('active', 1)->where('level', 3)->first();
		$LevelTwo = DivisionLevel::where('active', 1)->where('level', 2)->first();
		$LevelOne = DivisionLevel::where('active', 1)->where('level', 1)->first();
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		
		$processflow = Stock_Approvals_level::get();
		if (!empty($processflow)) $processflow= $processflow->load('divisionLevelFive','divisionLevelFour',
		'divisionLevelThree','divisionLevelTwo','divisionLevelOne','employeeDetails');
       //return $processflow;
        $data['page_title'] = "Stock Approval";
        $data['page_description'] = "Processes";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/approval_level', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Approval Steps', 'active' => 1, 'is_module' => 0]
        ];

        $data['LevelFive'] = $LevelFive;
        $data['LevelFour'] = $LevelFour;
        $data['LevelTHree'] = $LevelTHree;
        $data['LevelTwo'] = $LevelTwo;
        $data['LevelOne'] = $LevelOne;
        $data['employees'] = $employees;
        $data['newstep'] = $newstep;
        $data['division_levels'] = $divisionLevels;
        $data['processflows'] = $processflow;
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Approval Steps';
		
		AuditReportsController::store('Stock Management', 'Stock Approvals Page Accessed', 'Accessed By User', 0);
        return view('stock.step_approvals')->with($data);
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
        $this->validate($request, [
            'step_name' => 'required',
            'step_number' => 'required',
            'division_level_5' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $flow = Stock_Approvals_level::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

        $approvalsLevel = new Stock_Approvals_level();
        $approvalsLevel->step_number = $flowprocee + 1;
        $approvalsLevel->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $approvalsLevel->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $approvalsLevel->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $approvalsLevel->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $approvalsLevel->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $approvalsLevel->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $approvalsLevel->employee_id = !empty($SysData['hr_person_id']) ? $SysData['hr_person_id'] : 0;
        $approvalsLevel->max_amount = !empty($SysData['max_amount']) ? $SysData['max_amount'] : 0;
        $approvalsLevel->status = 1;
        $approvalsLevel->date_added = time();
        $approvalsLevel->save();

        AuditReportsController::store('Stock Management', 'New Step has been added', "Added by user");
        return response()->json();
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock_Approvals_level $step)
    {
        $this->validate($request, [
            'step_name' => 'required',
            'step_number' => 'required',
            'division_level_5' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $step->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $step->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $step->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $step->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $step->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $step->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $step->employee_id = !empty($SysData['hr_person_id']) ? $SysData['hr_person_id'] : 0;
        $step->max_amount = !empty($SysData['max_amount']) ? $SysData['max_amount'] : 0;
        
        $step->update();

        AuditReportsController::store('Stock Management', 'Step edited', "Proces flow Edited");
        return response()->json();
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
	
	// Activate/ de-activate
	public function steps_act(processflow $steps)
    {
        if ($steps->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $steps->status = $stastus;
        $steps->update();

        AuditReportsController::store('Stock Management', ' process flow status Changed', "Proces flow Status changed", $steps->id);
        return back();
    }
}
