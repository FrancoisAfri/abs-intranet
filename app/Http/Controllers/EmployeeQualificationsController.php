<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;

use App\HRPerson;

use App\hr_person;


class EmployeeQualificationsController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewDoc() {
    	$employees = HRPerson::where('status', 1)->get();
        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'employee qualifications';
        $data['employees'] = $employees;
        //$data['hr_people'] = $hr_people;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.emp_qualifications')->with($data);
    }
}
