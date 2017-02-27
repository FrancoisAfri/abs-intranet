<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class HrController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function showSetup() {
    	$division_types = DB::table('division_setup')->orderBy('name', 'asc')->get();
        $data['page_title'] = "Setup";
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
}
