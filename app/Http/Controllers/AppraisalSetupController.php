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
      
		//return $data;
		AuditReportsController::store('Performance Appraisal', 'Templates Page Accessed', "Actioned By User", 0);
        return view('appraisals.setup')->with($data);
    }

 public function addAppraisal(Request $request, appraisalSetup $appraisal_setup) {
        $this->validate($request, [
            'number_of_times' => 'required|numeric',
            'percentage'=> 'required|numeric|min:2',

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
            'number_of_times' => 'bail|required|numeric',
            'percentage' => 'bail|required|numeric|min:2',
        ]);
        //save the changes
        $appraisalData=$request->all();
        $appraisal_setup->update($appraisalData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
     }
     //check hr contoller company_setup blade for this
 public function activateGroupLevel( $groupLevel) 
    {
        if ($groupLevel->active == 1) $stastus = 0;
        else $stastus = 1;
        
        $groupLevel->active = $stastus;   
        $groupLevel->update();
        return back();
    }
 }
  /*  $lev->name = $request->input('name');
        $lev->description = $request->input('description');
        //$lev->font_awesome = $request->input('font_awesome');
        $lev->update();
        return $lev;
        AuditReportsController::store('Leave', 'leavetype Informations Edited', "Edited by User: $lev->name", 0);
        return response()->json(['new_name' => $lev->name, 'description' => $lev->description], 200);
    }