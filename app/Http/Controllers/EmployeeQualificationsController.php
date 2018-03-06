<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use App\HRPerson;
use App\hr_people;
use App\DivisionLevel;
use App\employee_documents;
use App\doc_type;
use App\doc_type_category;
use App\DivisionLevelTwo;
use App\Qualification_type;


use App\qualification;

class EmployeeQualificationsController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewDoc() {

        $data['page_title'] = "Employee Records";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'Employee records', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Records', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Search';

        //$user->load('person');
        //$avatar = $user->person->profile_pic;
        $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $qualifications = DB::table('qualification')->orderBy('id')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division=DivisionLevelTwo::where('active', 1)->get();
        $QulificationType = Qualification_type::where('status', 1)->get();
        $DocType = doc_type::where('active', 1)->get();
        $doc_type = doc_type::where('active', 1)->get();
        // return $DocType;
        //$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
     
     
      
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Search';
        $data['doc_type'] ='doc_type';  
        $data['qualifications'] = $qualifications;
        $data['employees'] = $employees;
        $data['DocType'] = $DocType;
        $data['QulificationType'] = $QulificationType;
        $data['category'] = $category;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
         return view('hr.emp_qualifications')->with($data);

    }

     public function Searchqul(Request $request){
            $this->validate($request, [                     
               //  'division_level_2'=> 'required',
               // 'division_level_1'=> 'required',
               // 'User_name'=> 'required',
               // 'doc_description'=>'bail|required|min:6',

               //         "division_level_2"
 //        division_level_1"
 //       employe_name"
     
 //     id_number"
 //     passport_number
 //   employee_number"
 // qualification_type
 // document-type"
                 ]);
            $qul = $request->all();
            unset($qul['_token']);

             $division = trim($request->division_level_2);
             $department = trim($request->division_level_1);
             $userID = trim($request->employe_name);
             $Search = trim($request->qualification_type);   
             

          #
        $qualification  = DB::table('qualification')
        ->select('qualification.*','hr_people.status as statas','hr_people.first_name as Name','hr_people.surname as Surname','division_level_ones.name as Department', 'division_level_twos.name as Division', 'Qualification_type.name as Qualification_type')

          ->leftJoin('Qualification_type', 'qualification.Qualification_Type', '=' , 'Qualification_type.id')
          ->leftJoin('hr_people', 'qualification.hr_person_id', '=', 'hr_people.id')
          ->leftJoin('division_level_ones','qualification.division_level_1', '=', 'division_level_ones.id')
          ->leftJoin('division_level_twos', 'qualification.division_level_2', '=', 'division_level_twos.id')

          ->where('hr_people.status' ,1) 
          ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('qualification.hr_person_id', $userID);
                    }
                })
          ->where(function ($query) use ($division) {
                     if (!empty($division)) {
                        $query->where('qualification.division_level_2', $division );
                      }
                 })   
          ->where(function ($query) use ($department) {
                      if (!empty($department)) {
                        $query->where('qualification.division_level_1', $department );
                      }
                 }) 
          ->where(function ($query) use ($Search) {
                if (!empty($Search)) {
                     $query->where('qualification.Qualification_Type', $Search );
                }
                 }) 
                  ->orderBy('Name')
                  ->limit(50)
                  ->get();
                     
                      // return  $qualification ;

              #
        $data['qualification'] = $qualification;   
        $data['userID'] = $userID;
        $data['division'] = $division;
        $data['department'] = $department;
        $data['Search'] = $Search;
        $data['page_title'] = "Employee Qualifications";

        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee Documents', 'Employee Documents Search Page Accessed', "Actioned By User", 0);
        //return back();
        return view('hr.qul_searchresults')->with($data);       

        }

          


}
