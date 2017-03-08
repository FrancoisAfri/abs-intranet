<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DivisionLevel;

class HrController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function showSetup() {
    	$division_types = DB::table('division_setup')->orderBy('level', 'desc')->get();
        $data['page_title'] = "HR";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'setup';
        $data['division_types'] = $division_types;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.setup')->with($data);
    }

    public function updateGroupLevel(Request $request, DivisionLevel $groupLevel) {
        //validate name required if active
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'plural_name' => 'bail|required|min:2',
        ]);
        //save the changes
        $groupLevelData=$request->all();
        $groupLevel->update($groupLevelData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
     }
     public function activateGroupLevel(DivisionLevel $groupLevel) 
    {
        if ($groupLevel->active == 1) $stastus = 0;
        else $stastus = 1;
        
        $groupLevel->active = $stastus;    
        $groupLevel->update();
        return back();
    }
 }