<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\jobcardMaintance;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\job_maintanace;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehiclemake;
use App\fleet_documentType;
use App\fleet_fillingstation;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FleetManagementController extends Controller
{
      public function __construct() {
        $this->middleware('auth');
    }

      public function fleetManagent() {
        //$incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.fleetIndex')->with($data);
    }

    public function addvehicle() {

    	$vehicle = vehicle::orderBy('id', 'asc')->get();
    	$Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
    	//return $Vehicle_types;
  

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
                 ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.add_vehicle')->with($data);

    }
    // public function addvehicleDetails(Request $request , job_maintanace $jobmaintanace) {
    //    $this->validate($request, [
    //         // 'name' => 'required',
    //         'description' => 'required',
    //     ]);
    //     $jobData = $request->all();
    //     unset($jobData['_token']);

    //     $jobmaintanace->status = 1;
    //     $jobmaintanace->responsible_for_maintenance = $jobData['responsible_for_maintenance'];
    //     $jobmaintanace->vehicle_make = $jobData['vehicle_make'];
    //     $jobmaintanace->vehicle_registration = $jobData['vehicle_registration'];
    //     $jobmaintanace->chassis_number = $jobData['chassis_number'];
    //     // $jobmaintanace->name = $jobData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     $jobmaintanace->save();
    //     AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
    //     ;
    //     return response()->json();

       

    // }

       public function addvehicleDetails(Request $request) {
        $this->validate($request, [
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $jobmaintanace = new job_maintanace($SysData);
        $jobmaintanace->status = 1;
        $jobmaintanace->responsible_for_maintenance = $jobData['responsible_for_maintenance'];
        $jobmaintanace->vehicle_make = $jobData['vehicle_make'];
        $jobmaintanace->vehicle_registration = $jobData['vehicle_registration'];
        $jobmaintanace->chassis_number = $jobData['chassis_number'];
        $jobmaintanace->save();
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        ;
        return response()->json();
    }

}
