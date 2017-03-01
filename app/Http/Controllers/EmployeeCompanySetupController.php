<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;



class EmployeeCompanySetupController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewLevel() {
        $types = DB::table('division_level_fives')->get();
        $data['page_title'] = "HR";
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

  }
