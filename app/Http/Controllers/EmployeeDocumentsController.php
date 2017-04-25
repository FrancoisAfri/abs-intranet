<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;

use App\HRPerson;

use App\hr_people;

use App\DivisionLevel;

use App\doc_type;

use App\doc_type_category;
use App\DivisionLevelTwo;

//use App\User;

class EmployeeDocumentsController extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewDoc() {
        //$user->load('person');
        //$avatar = $user->person->profile_pic;
    	$hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $document = doc_type_category::where('active', 1)->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division=DivisionLevelTwo::where('active', 1)->get();
        // return $divisionLevels;
    	//$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
     
     
        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'employees documents';
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division'] = $division;
        //$data['user'] = $user;
        $data['division_levels'] = $divisionLevels;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.employee_documents')->with($data);
    }


}
//