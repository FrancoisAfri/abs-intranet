<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\appraisalSetup;




class AppraisalSetupController extends Controller
{
    //
   public function __construct()
    {
        $this->middleware('auth');
    }


 public function show() 
    {
	    $appraisal_setup = DB::table('appraisal_setup')->orderBy('number_of_times', 'desc')->get();
        //$appraisalSet = appraisalSetup::where('active', 1)->orderBy('number_of_times', 'desc')->get();
        $data['page_title'] = "Performance appraisal ";
        $data['page_description'] = "Manage Latecomers";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Templates', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Setup';
        $data['appraisal_setup'] = $appraisal_setup;
        //$data['appraisalSet'] = $appraisalSet;
      
		//return $appraisal_setup;
		AuditReportsController::store('Performance Appraisal', 'Templates Page Accessed', "Actioned By User", 0);
        return view('appraisals.setup')->with($data);
    }

 public function addAppraisal(Request $request) {
        $this->validate($request, [
            'number_of_times' => 'required|integer|min:0',
            'percentage'=> 'required|numeric',

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
 public function updateAppraisal(Request $request, appraisalSetup $appraisal_setup) {
        //validate name required if active
        $this->validate($request, [
            'number_of_times' => 'required|integer|min:0',
            'percentage'=> 'required|numeric',
        ]);
        //save the changes
        $appraisalData=$request->all();
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
 }
 