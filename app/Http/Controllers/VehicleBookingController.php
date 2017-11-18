<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\ContactCompany;
use App\DivisionLevel;
use App\jobcardMaintance;
Use App\permits_licence;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\job_maintanace;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\fleet_documentType;
use App\keytracking;
use App\safe;
use App\vehicle_documets;
use App\images;
use App\fleet_fillingstation;
use App\module_access;
use App\notes;
use App\DivisionLevelFive;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VehicleBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function index(){
   	$Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
   	 $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
   	   $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];
 
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Create_request.search_vehicle')->with($data);

   }

    public function VehicleSearch(Request $request)
    {
        $this->validate($request, [
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        //return $vehicleData;

        $hrDetails = HRPerson::where('status', 1)->get();

        $companyID = $request['company_id'];
        $departmentID = $request['department_id'];
        $propertyID = $request['property_type'];
        $vehicleID = $request['vehicle_type'];
        $fleetID = $request['fleet_number'];
        $registration_number = $request['registration_number'];
        $promotionID = $request['promotion_type'];

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $vehiclebooking = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($propertyID) {
                if (!empty($propertyID)) {
                    $query->where('vehicle_details.property_type', $propertyID);
                }
            })
            ->where(function ($query) use ($vehicleID) {
                if (!empty($vehicleID)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleID);
                }
            })
            ->where(function ($query) use ($fleetID) {
                if (!empty($fleetID)) {
                    $query->where('vehicle_details.fleet_number', $fleetID);
                }
            })
            ->where(function ($query) use ($registration_number) {
                if (!empty($registration_number)) {
                    $query->where('vehicle_details.vehicle_registration', $registration_number);
                }
            })
            ->orderBy('vehicle_details.id')
            ->get();

        //return $vehiclebooking;


        $data['hrDetails'] = $hrDetails;
        $data['division_levels'] = $divisionLevels;
        $data['companyID'] = $companyID;
        $data['departmentID'] = $departmentID;
        $data['propertyID'] = $propertyID;
        $data['vehicleID'] = $vehicleID;
        $data['fleetID'] = $fleetID;
        $data['registration_number'] = $registration_number;
        $data['promotionID'] = $promotionID;
        $data['vehiclebooking'] = $vehiclebooking;
        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Internal Vehicle Management Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
       return view('Vehicles.Create_request.search_results')->with($data);
    }


}
