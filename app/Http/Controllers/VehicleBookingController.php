<?php

namespace App\Http\Controllers;

use App\DivisionLevelFour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\ContactCompany;
use App\DivisionLevel;
use App\vehicle_bookings;
Use App\permits_licence;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\vehicle_booking;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\fleet_documentType;
use App\DivisionLevelTwo;
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

    public function index()
    {
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
        $date = $startdatee = strtotime($startdate);
        //return $startdatee;


        $returnAt = $vehicleData['return_at'] = str_replace('/', '-', $vehicleData['return_at']);
        $returnAt = $vehicleData['return_at'] = strtotime($vehicleData['return_at']);



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
        $vehicleDates = $date . ' - ' . $requiredTime . ' - ' . $returnAt . ' - ' . $returnTime;


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
        AuditReportsController::store('Vehicle Management', 'View Vehicle Search Results', "view Audit Results", 0);
        return view('Vehicles.Create_request.search_results')->with($data);
    }

    public function viewBooking(vehicle_maintenance $bookings, $requiredFrom)
    {

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

            $vehiclebookings = DB::table('vehicle_booking')
                ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make')
                ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
                ->orderBy('vehicle_booking.id')
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
            $data['vehiclebookings'] = $vehiclebookings;
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
            AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed ', "Accessed by User", 0);
            return view('Vehicles.Create_request.vehiclebookings')->with($data);
        } else
            return back();

    }

    public function BookingDetails($status = 0, $hrID = 0 , $driverID = 0) {

        // query the vehicle_configuration  table and bring back the values
        $approvals = DB::table('vehicle_configuration')
            ->select('approval_manager_capturer', 'approval_manager_driver', 'approval_hod', 'approval_admin')
            ->first();
        // query the hrperon  model and bring back the values of the managerg
        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverDetails = HRPerson::where('id', $driverID)->where('status', 1)->first();

        $managerID = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverHead = $managerID->manager_id;

//        $driverHead = HRPerson::where('id', $manager)->where('status', 1)
//            ->select('first_name', 'surname', 'email')
//            ->first();

        if ($approvals->approval_manager_capturer == 1) {
            # code...
            // query the hrperon  model and bring back the values of the manager
            $loggedInEmplID = Auth::user()->person->id;

            $managerID = HRPerson::where('id', $loggedInEmplID)->where('status', 1)->first();
            $manageID = $managerID->manager_id;

            $managerDetails = HRPerson::where('id', $manageID)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
            if ($managerDetails == null) {
                $details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_manager_driver == 1) {
            # code...  division_level_twos
            // query the hrperon  model and bring back the values of the manager


            $driverHeadDetails = HRPerson::where('id', $driverHead)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();

            if ($driverHeadDetails == null) {
                $details = array('status' => 1, 'first_name' => $driverHeadDetails->first_name, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 1, 'first_name' => $driverHeadDetails->first_name, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_hod == 1) {
            # code...  division_level_twos
            // query the hrperon  model and bring back the values of the manager


            $Dept = DivisionLevelFour::where('manager_id', $hrDetails->division_level_4)->get()->first();

            $hodmamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();

            if ($hodmamgerDetails == null) {
                $details = array('status' => 3, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 3, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_admin == 1) {
            $userID = DB::table('security_modules_access')
                ->select('security_modules_access.*')
                ->where('module_id', 9)
                ->pluck('user_id');

            foreach ($userID as $empID) {
                $driverHeadDetails = HRPerson::where('id', $empID)->where('status', 1)
                    ->select('first_name', 'surname', 'email')
                    ->first();
                if ($approvals->approval_admin == null) {
                    $details = array('status' => 4, 'first_name' => $driverHeadDetails->firstname, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                    return $details;
                } else {
                    // array to store manager details
                    $details = array('status' => 4, 'first_name' => $driverHeadDetails->firstname, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                    return $details;
                }
            }
        }
        #code here .. Require Hr
        else {

            $details = array('status' => 10, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
            return $details;
        }
    }

    public function status($status = 0) {

        $aStatusses = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Aproved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
        return $aStatusses;
    }

    public function vehiclebooking(Request $request)
    {
        $this->validate($request, [
            //'driver ' => 'required'
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        // call the status function
        $BookingDetails = array();

        $driverID =  $vehicleData['driver'];
        $hrID = $vehicleData['driver'];
        //$BookingDetail = VehicleBookingController::$BookingDetails(0, $hrID , $driverID );
       $BookingDetail = VehicleBookingController::BookingDetails(0,$hrID,$hrID);

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        $vehicleID = $vehicleData['vehicle_id'];
        $Employee = vehicle_maintenance::where('id', $vehicleID)->orderBy('id', 'desc')->get()->first();

        $Vehiclebookings = new vehicle_booking();
        $Vehiclebookings->vehicle_type = $Employee->vehicle_type;
        $Vehiclebookings->vehicle_model = $Employee->vehicle_model;
        $Vehiclebookings->vehicle_make = $Employee->vehicle_make;
        $Vehiclebookings->year = $Employee->year;
        $Vehiclebookings->fleet_number = $Employee->fleet_number;
        $Vehiclebookings->vehicle_reg = $Employee->vehicle_registration;
        $Vehiclebookings->require_datetime = $request['required_from'];
        $Vehiclebookings->required_time = $request['required_time'];
        $Vehiclebookings->return_datetime = $request['return_at'];
        $Vehiclebookings->return_time = $request['return_time'];
        $Vehiclebookings->usage_type = $request['Usage_type'];
        $Vehiclebookings->driver_id = $request['driver'];
        $Vehiclebookings->purpose = $vehicleData['purpose'];
        $Vehiclebookings->destination = $request['destination'];
        $Vehiclebookings->vehicle_id = $request['vehicle_id'];
        $Vehiclebookings->capturer_id = $name;
        $Vehiclebookings->UserID = $loggedInEmplID;
        $Vehiclebookings->status = $BookingDetail['status'];
        $Vehiclebookings->save();

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed by User", 0);
        return redirect('/vehicle_management/vehiclebooking_results')->with('success_application', "Vehicle Booking application was successful.");
    }

    public function booking_results()
    {


        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair',);
        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Aproved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        //$currentDate = time();

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################


        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id')
            ->where('vehicle_booking.UserID', $loggedInEmplID)
            ->get();

        //return $vehiclebookings;


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['employees'] = $employees;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['vehicle_image'] = $vehicle_image;
        $data['vehicle'] = $vehicle;
        $data['bookingStatus'] = $bookingStatus;
        $data['usageType'] = $usageType;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['divisionLevels'] = $divisionLevels;
        $data['vehicledetail'] = $vehicledetail;
        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.vehiclebooking_results')->with($data);
    }


}