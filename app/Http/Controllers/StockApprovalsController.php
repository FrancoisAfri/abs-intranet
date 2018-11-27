<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock_Approvals_level;
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

        $processflow = Stock_Approvals_level::all();

        $processflow = DB::table('jobcard_process_flow')
            ->select('jobcard_process_flow.*', 'hr_roles.description as jobtitle_name')
            ->leftJoin('hr_roles', 'jobcard_process_flow.job_title', '=', 'hr_roles.id')
            ->orderBy('jobcard_process_flow.id')
            ->get();

        $data['page_title'] = "Job Card Processes";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['newstep'] = $newstep;
        $data['roles'] = $roles;
        $data['processflows'] = $processflow;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Process Flow';
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
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $flow = processflow::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

        $processflow = new processflow();
        $processflow->step_number = $flowprocee + 1;
        $processflow->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $processflow->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
        $processflow->status = 1;
        $processflow->save();

        AuditReportsController::store('Job Card Management', 'New processflow has been added', "New proces flow Added", $processflow->id);
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'step_name' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $steps->step_number = !empty($SysData['step_number']) ? $SysData['step_number'] : '';
        $steps->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $steps->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
        $steps->update();

        AuditReportsController::store('Job Card Management', ' process flow edited', "Proces flow Edited", $steps->id);
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

        AuditReportsController::store('Job Card Management', ' process flow status Changed', "Proces flow Status changed", $steps->id);
        return back();
    }
}
