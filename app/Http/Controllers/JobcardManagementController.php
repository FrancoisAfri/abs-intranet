<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\jobcardMaintance;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\incident_type;
use App\vehicle_config;
use App\modules;
use App\fleet_documentType;
use App\fleet_fillingstation;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class JobcardManagementController extends Controller
{
     public function __construct() {
        $this->middleware('auth');
    }

    public function JobcardManagent(Request $request) {
        //$incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Job Card Management";
        $data['page_description'] = " Job Card Management";
        $data['breadcrumb'] = [
                ['title' => 'Job Card  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Job Cards ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Job Card Management';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.JobcardManagement.jobcardIndex')->with($data);
    }

    public function addJobcard(Request $request) {
        $jobcardMaintance = jobcardMaintance::orderBy('id', 'asc')->get();
        $Vehicle_managemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();


        $data['page_title'] = " Job Card Management";
        $data['page_description'] = " Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Job Cards ', 'active' => 1, 'is_module' => 0]
        ];

        $data['jobcardMaintance'] = $jobcardMaintance;
        $data['Vehicle_managemnt'] = $Vehicle_managemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Job Card Management';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.JobcardManagement.jobCard')->with($data);
    }
}
