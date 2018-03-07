<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\AppraisalSetup;


class AppraisalSetupController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $appraisalSetup = AppraisalSetup::first();
        $data['page_title'] = "Performance Appraisal ";
        $data['page_description'] = "Manage module settings";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Setup';
        $data['appraisalSetup'] = $appraisalSetup;
        AuditReportsController::store('Performance Appraisal', 'Setup Page Accessed', "Actioned By User", 0);

        return view('appraisals.setup')->with($data);
    }

    /**
     * Save or update company identity data.
     *
     * @param   \App\Http\Requests
     * @return  \Illuminate\Http\Response
     */
    public function saveOrUpdate(Request $request)
    {
        $this->validate($request, [
            'three_sixty_status' => 'bail|required|integer|min:0',
            'manager_appraisal_weight' => 'bail|required|numeric|min:0',
            'employee_appraisal_weight' => 'bail|required|numeric|min:0',
            'colleagues_appraisal_weight' => 'bail|required_if:three_sixty_status,1|numeric|min:0',
            'total_appraisal_weight' => 'bail|numeric|min:100|max:100',
        ]);

        $appraisalSetup = AppraisalSetup::first();
        if ($appraisalSetup) { //update
            $appraisalSetup->update($request->all());

        } else { //insert
            $appraisalSetup = new AppraisalSetup($request->all());
            $appraisalSetup->save();

        }

        return back()->with('changes_saved', 'Your changes have been saved successfully.');
    }
/*
    public function addAppraisal(Request $request)
    {
        $this->validate($request, [
            'number_of_times' => 'required|integer|min:0',
            'percentage' => 'required|numeric',

        ]);

        $lateData = $request->all();
        unset($lateData['_token']);
        $appraisal_setup = new appraisalSetup($lateData);
        $appraisal_setup->active = 1;
        $appraisal_setup->save();
        AuditReportsController::store('Leave custom', 'leave custom Added', "Actioned By User", 0);
        return response()->json();

    }

//
    public function updateAppraisal(Request $request, appraisalSetup $appraisal_setup)
    {
        //validate name required if active
        $this->validate($request, [
            'number_of_times' => 'required|integer|min:0',
            'percentage' => 'required|numeric',
        ]);
        //save the changes
        $appraisalData = $request->all();
        $appraisal_setup->update($appraisalData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
    }

    //check hr contoller company_setup blade for this
    public function activateAppraisal(appraisalSetup $appraisal_setup)
    {
        if ($appraisal_setup->active == 1) $stastus = 0;
        else $stastus = 1;
        //return $appraisal_setup;
        $appraisal_setup->active = $stastus;
        $appraisal_setup->update();
        return back();
    }
    */
}
 