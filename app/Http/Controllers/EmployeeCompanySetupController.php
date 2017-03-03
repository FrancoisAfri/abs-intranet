<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\TopLevel;
use App\DivisionLevel;





class EmployeeCompanySetupController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewLevel() {
        //get the highest active level
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first();
       
        if ($highestLvl->level == 5){
             $types = DB::table('division_level_fives')->get();
        }
        elseif ($highestLvl->level == 4) {
            # code...
             $types = DB::table('division_level_fours')->get();
        }
        elseif ($highestLvl->level == 3) {
            # code...
             $types = DB::table('division_level_threes')->get();
        }
        elseif ($highestLvl->level == 2) {
            # code...
             $types = DB::table('division_level_twos')->get();
        }
        elseif ($highestLvl->level == 1) {
            # code...
             $types = DB::table('division_level_ones')->get();

        }
        $data['highestLvl'] = $highestLvl;
        $types = DB::table('division_level_fives')->get();
        $data['page_title'] = "Company Setup";
        $data['page_description'] = "Company records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'setup';
        $data['types'] = $types;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.company_setup')->with($data);
    }

        public function UpdateLevel(Request $request, TopLevel $firstLevel) {

         $this->validate($request, [
            'name' => 'bail|required|min:2',
        ]);

        $firstLevelData=$request->all();
        $firstLevel->update($firstLevelData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
}
        public function activateFirstLevel(TopLevel $firstLevel) 
           {
            if ($firstLevel->active == 1) $stastus = 0;
            else $stastus = 1;
                
            $firstLevel->active = $stastus;    
            $firstLevel->update();
            return back();
            }
 }

    
 
    