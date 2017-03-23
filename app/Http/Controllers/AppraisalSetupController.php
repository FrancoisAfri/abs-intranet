<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;




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
        $data['page_title'] = "Performance appraisal ";
        $data['page_description'] = "Manage Latecomers";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Templates', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Setup';
        $data['appraisal_setup'] = $appraisal_setup;
      
		//return $data;
		AuditReportsController::store('Performance Appraisal', 'Templates Page Accessed', "Actioned By User", 0);
        return view('appraisals.setup')->with($data);
    }
  }