<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\HRPerson;
use App\LeaveType;
use App\Http\Requests;
use App\DivisionLevelOne;
use App\DivisionLevelTwo;
use App\DivisionLevelThree;
use App\DivisionLevelFour;
use App\DivisionLevelFive;
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
        $division_types = DB::table('division_setup')->orderBy('level', 'desc')->get();
        $employees = HRPerson::where('status', 1)->get();
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->load('divisionLevelGroup.manager');
        //return $highestLvl;
        $data['division_types'] = $division_types;
        $data['employees'] = $employees;
        $data['highestLvl'] = $highestLvl;
        $data['page_title'] = "Company Setup";
        $data['page_description'] = "Company records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'setup';
            //return $highestLvl;

        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.company_setup')->with($data);
    }
public function editlevel(Request $request, DivisionLevel $divLevel) 
{
            $this->validate($request, [
            'name' => 'required',   
            'manager_id' => 'required',
        ]);

             //$lev->hr_id = $request->input('hr_id');
        $divLevel->name = $request->input('name');
        $divLevel->manager_id = $request->input('manager_id');
        $divLevel->update();
        //return $lev;
        AuditReportsController::store('');
        return response()->json();

}




        public function addLevel(Request $request, DivisionLevel $divLevel) {

         $this->validate($request, [
            'manager_id' => 'required',
        ]);
        $firstLevelData=$request->all();
        //$addDivisionLevelGroup = new TopLevel($firstLevelData);
        //$firstLevel->new('DivisionLevelOne');
        //$addDivisionLevelGroup->status = 1;
        //$addDivisionLevelGroup->save();
        //return $childDiv;
        //return $divLevel;

        if ($divLevel->level == 5){
             $childDiv = new DivisionLevelFive($firstLevelData);
        }
        elseif ($divLevel->level == 4){
            $childDiv = new DivisionLevelFour($firstLevelData);
        }
        elseif ($divLevel->level == 3) {
            $childDiv = new DivisionLevelThree($firstLevelData);
        }
        elseif ($divLevel->level == 2) {
            $childDiv = new DivisionLevelTwo($firstLevelData);
        }
        elseif ($divLevel->level == 1) {
            $childDiv = new DivisionLevelOne($firstLevelData);
        }
        $divLevel->addDivisionLevelGroup($childDiv);

       // return $divLevel;

        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
        }

        public function activateFirstLevel(DivisionLevel $active, $divLevel) 
           {
                   if ($divLevel == 5){
             $childDiv =  DivisionLevelFive::find($high);
             if ($childDiv->active == 1) $stastus = 0;
            else $stastus = 1;  
            $childDiv->active = $stastus;    
            $childDiv->update();   
        }
                if ($divLevel == 4){
            $childDiv =  DivisionLevelFour::find($high);
            if ($childDiv->active == 1) $stastus = 0;
             else $stastus = 1;  
            $childDiv->active = $stastus;    
            $childDiv->update();   
        }
        elseif ($divLevel == 3) {
            $childDiv =  DivisionLevelThree::find($high);
             if ($childDiv->active == 1) $stastus = 0;
            else $stastus = 1;  
            $childDiv->active = $stastus;    
            $childDiv->update();   
        }
        elseif ($divLevel == 2) {
            $childDiv =  DivisionLevelTwo::find($high);
             if ($childDiv->active == 1) $stastus = 0;
          else $stastus = 1;  
            $childDiv->active = $stastus;    
            $childDiv->update();   
        }
        elseif ($divLevel == 1) {
            $childDiv =  DivisionLevelOne::find($high);
             if ($childDiv->active == 1) $stastus = 0;
            else $stastus = 1;  
            $childDiv->active = $stastus;    
            $childDiv->update();   
        }
               return back();
            }

        public function editCompany(Request $request, DivisionLevel $divLevel)
        {
        //$user = Auth::user()->load('person');
        $this->validate($request, [
            'name' => 'required',
            'manager_id'=>  'numeric|required',

        ]);
         //return DivisionLevel;
        if ($divLevel == 5){
             $childDiv =  DivisionLevelFive::find($high);
             $childDiv->update($request->all());
        }
        elseif ($divLevel == 4){
            $childDiv =  DivisionLevelFour::find($high);
            $childDiv->update($request->all());
        }
        elseif ($divLeveldivLeveldivLeveldivLevel == 3) {
            $childDiv =  DivisionLevelThree::find($high);
            $childDiv->update($request->all());
        }
        elseif ($divLeveldivLeveldivLevel == 2) {
            $childDiv =  DivisionLevelTwo::find($high);
            $childDiv->update($request->all());
        }
        elseif ($divLeveldivLevel == 1) {
            $childDiv =  DivisionLevelOne::find($high);
            $childDiv->update($request->all());
        }

        
        AuditReportsController::store('Company', 'edit company  Informations Edited', "Edited by User", 0);
        return response()->json();
        }
    //

      }



      


  