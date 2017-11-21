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

        $vehicletype = $request['vehicle_type'];
        $Company = $request['division_level_5'];
        $Department = $request['division_level_4'];
        $requiredFrom = $request['required_from'];
        $requiredTime = $request['required_time'];
        $returnAt = $request['return_at'];
        $returnTime = $request['return_time'];;


        $startdate = $vehicleData['required_from'] = str_replace('/', '-', $vehicleData['required_from']);
        $date = $startdatee =  strtotime($startdate);
        //return $startdatee;


        $returnAt = $vehicleData['return_at'] = str_replace('/', '-', $vehicleData['return_at']);
        $returnAt = $vehicleData['return_at'] = strtotime($vehicleData['return_at']);





       // return $startdate;
        //$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
//        if (!empty($actionDate)) {
//            $startExplode = explode('-', $actionDate);
//            $actionFrom = strtotime($startExplode[0]);
//            $actionTo = strtotime($startExplode[1]);
//        }
        $vehiclebooking = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicletype) {
                if (!empty($vehicletype)) {
                    $query->where('vehicle_details.vehicle_type', $vehicletype);
                }
            })
            ->where(function ($query) use ($Company) {
                if (!empty($Company)) {
                    $query->where('vehicle_details.division_level_5', $Company);
                }
            })
            ->where(function ($query) use ($Department) {
                if (!empty($Department)) {
                    $query->where('vehicle_details.division_level_4', $Department);
                }
            })

            ->orderBy('vehicle_details.id')
            ->get();

        //return $vehiclebooking;
        //$valID = '$requiredFrom' . '$requiredTime' + '$returnAt' + '$return_time';
        $vehicleDates =  $date . ' - ' . $requiredTime . ' - ' . $returnAt . ' - ' . $returnTime;



        $data['$vehicleDates'] = $vehicleDates;

        $data['hrDetails'] = $hrDetails;
//        $data['companyID'] = $companyID;
//        $data['departmentID'] = $departmentID;
//        $data['propertyID'] = $propertyID;
//        $data['vehicleID'] = $vehicleID;
//        $data['fleetID'] = $fleetID;
//        $data['registration_number'] = $registration_number;
        $data['vehicleDates'] = $vehicleDates;
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

    public function viewBooking(vehicle_maintenance $bookings,$requiredFrom) {

        $ID = $bookings->id;
        //return $ID;

        //return $requiredFrom;
        $startExplode = explode('-', $requiredFrom);
        $startdate = $startExplode[0];
        $requiredTime = $startExplode[1];
        $returnAt = $startExplode[2];
        $returnTime = $startExplode[3];




        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');
        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $bookings->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $bookings->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $bookings->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        if ($bookings->status == 1) {
            $ID = $bookings->id;

            $vehiclenotes  = DB::table('vehicle_documets')
                ->select('vehicle_documets.*')
                ->orderBy('vehicle_documets.id')
                ->get();

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];



            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['IssuedTo'] = $IssuedTo;
            $data['requiredTime'] = $requiredTime;
            $data['startdate'] = $startdate;
            $data['returnAt'] = $returnAt;
            $data['returnTime'] = $returnTime;
            $data['employees'] = $employees;
            $data['vehiclenotes '] = $vehiclenotes ;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $bookings;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            return view('Vehicles.Create_request.vehiclebookings')->with($data);
        } else
            return back();

    }


}