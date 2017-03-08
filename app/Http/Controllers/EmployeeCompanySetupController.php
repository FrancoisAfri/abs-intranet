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
use App\manager;

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
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.company_setup')->with($data);
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

        public function editCompany(Request $request, manager $companyLevel)
        {
        //$user = Auth::user()->load('person');
        $this->validate($request, [
            'name' => 'required',
            'manager_id'=>  'numeric|required',

        ]);
        $CompanyLevel->name = $request->input('name');
        $CompanyLevel->manager_id = $request->input('manager_id');
        $CompanyLevel->update();
        //return $lev;
        AuditReportsController::store('Company', 'edit company  Informations Edited', "Edited by User", 0);
        return response()->json();
        }
    //
        public function CompanyAct(manager $companyLevel)
        {
        if ($CompanyLevel->status == 1) $stastus = 0;
        else $stastus = 1;

        $CompanyLevel->status = $stastus;
        $CompanyLevel->update();
        return back();
    }
}

      


  