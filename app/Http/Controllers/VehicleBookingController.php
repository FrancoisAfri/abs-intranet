<?php

namespace App\Http\Controllers;

use App\DivisionLevelFour;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\Vehicle_managemnt;
use App\vehicle;
Use App\vehicle_booking;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\vehicle_maintenance;
use App\vehicle_collect_documents;
use App\vehiclemake;
use App\safe;
use App\vehicle_collect_image;
use App\images;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Http\Request;
use App\Mail\vehicle_bookings;
use  App\Mail\vehiclebooking_approval;
use App\Mail\vehiclebooking_cancellation;
use App\Mail\vehiclebooking_rejection;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

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
            'required_from' => 'bail|required',
            'required_to' => 'bail|required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        //return $vehicleData;

        $hrDetails = HRPerson::where('status', 1)->get();

        $vehicletype = $request['vehicle_type'];
        $Company = $request['division_level_5'];
        $Department = $request['division_level_4'];
        $requiredFrom = $vehicleData['required_from'];
        $requiredTo = $vehicleData['required_to'];

        $startDate = strtotime($requiredFrom );
        $EndDate = strtotime($requiredTo );

        $vehiclebooking = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date ',
                'vehicle_booking.return_datetime as return_date ', 'vehicle_booking.required_time as requiredTime',
                'vehicle_booking.return_time as returnTime', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
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

        $vehicleDates = $startDate . ' - ' . $EndDate ;
       // return $vehicleDates;


        $data['$vehicleDates'] = $vehicleDates;

        $data['hrDetails'] = $hrDetails;
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
        $enddate = $startExplode[1];
       // return $startdate;

        //return $enddate;
       // return  date("F j, Y, g:i a", trim($startdate));


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
            $data['startdate'] = $startdate;
            $data['enddate'] = $enddate;
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

    public function BookingDetails($status = 0, $hrID = 0, $driverID = 0)
    {

        // query the vehicle_configuration  table and bring back the values
        $approvals = DB::table('vehicle_configuration')->select('approval_manager_capturer', 'approval_manager_driver', 'approval_hod', 'approval_admin')->first();

        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverDetails = HRPerson::where('id', $driverID)->where('status', 1)->first();

        $managerID = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverHead = $managerID->manager_id;

        if ($approvals->approval_manager_capturer == 1) {
            # code...
            // query the hrperon  model and bring back the values of the manager
            $loggedInEmplID = Auth::user()->person->id;

            $managerID = HRPerson::where('id', $loggedInEmplID)->where('status', 1)->first();
            $manageID = $managerID->manager_id;

            $managerDetails = HRPerson::where('id', $manageID)->where('status', 1)->select('first_name', 'surname', 'email')->first();
            if ($managerDetails == null) {
                $details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
            } else {

                $details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_manager_driver == 1) {

            $driverHeadDetails = HRPerson::where('id', $driverHead)->where('status', 1)->select('first_name', 'surname', 'email')->first();

            if ($driverHeadDetails == null) {
                $details = array('status' => 1, 'first_name' => $driverHeadDetails->first_name, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                return $details;
            } else {

                $details = array('status' => 1, 'first_name' => $driverHeadDetails->first_name, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_hod == 1) {

            $Dept = DivisionLevelFour::where('manager_id', $hrDetails->division_level_4)->get()->first();

            $hodmamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)->select('first_name', 'surname', 'email')->first();

            if ($hodmamgerDetails == null) {
                $details = array('status' => 3, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            } else {

                $details = array('status' => 3, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            }
        } elseif ($approvals->approval_admin == 1) {
            $userID = DB::table('security_modules_access')->select('security_modules_access.*')->where('module_id', 9)->pluck('user_id');
            foreach ($userID as $empID) {
                $driverHeadDetails = HRPerson::where('id', $empID)->where('status', 1)
                    ->select('first_name', 'surname', 'email')->first();
                if ($approvals->approval_admin == null) {
                    $details = array('status' => 4, 'first_name' => $driverHeadDetails->firstname, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                    return $details;
                } else {
                    // array to store manager details
                    $details = array('status' => 4, 'first_name' => $driverHeadDetails->firstname, 'surname' => $driverHeadDetails->surname, 'email' => $driverHeadDetails->email);
                    return $details;
                }
            }
        } else {

            $details = array('status' => 10, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
            return $details;
        }
    }

    public function status($status = 0)
    {

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
            'driver' => 'bail|required',
            'Usage_type' => 'bail|required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        // call the status function
        $BookingDetails = array();

        $driverID = $vehicleData['driver'];
        $hrID = $vehicleData['driver'];
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);

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
        $Vehiclebookings->return_datetime = $request['required_to'];
        $Vehiclebookings->usage_type = $request['Usage_type'];
        $Vehiclebookings->driver_id = $request['driver'];
        $Vehiclebookings->purpose = $vehicleData['purpose'];
        $Vehiclebookings->destination = $request['destination'];
        $Vehiclebookings->vehicle_id = $request['vehicle_id'];
        $Vehiclebookings->capturer_id = $name;
        $Vehiclebookings->UserID = $loggedInEmplID;
        $Vehiclebookings->status = $BookingDetail['status'];
        $Vehiclebookings->cancel_status = 0;
        $Vehiclebookings->save();

        #mail to manager(s)
        Mail::to($BookingDetail['email'])->send(new vehicle_bookings($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email']));

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed by User", 0);
        return redirect('/vehicle_management/vehiclebooking_results')->with('success_application', "Vehicle Booking application was successful.");
    }

    public function edit_bookings(Request $request, vehicle_booking $Vehicebookings)
    {
        $this->validate($request, [
            // 'description' => 'numeric',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        $BookingDetails = array();

        $hrID = $vehicleData['driver'];
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        $vehicleID = $vehicleData['vehicle_id'];
        $Employee = vehicle_maintenance::where('id', $vehicleID)->orderBy('id', 'desc')->get()->first();

        $Vehicebookings->vehicle_type = $Employee->vehicle_type;
        $Vehicebookings->vehicle_model = $Employee->vehicle_model;
        $Vehicebookings->vehicle_make = $Employee->vehicle_make;
        $Vehicebookings->vehicle_reg = $Employee->vehicle_registration;
        $Vehicebookings->require_datetime = $request['required_from'];
        $Vehicebookings->return_datetime = $request['required_to'];
        $Vehicebookings->usage_type = $request['Usage_type'];
        $Vehicebookings->driver_id = $request['driver'];
        $Vehicebookings->purpose = $vehicleData['purpose'];
        $Vehicebookings->destination = $request['destination'];
        $Vehicebookings->vehicle_id = $request['vehicle_id'];
        $Vehicebookings->capturer_id = $name;
        $Vehicebookings->UserID = $loggedInEmplID;
        $Vehicebookings->status = $BookingDetail['status'];
        $Vehicebookings->cancel_status = 0;
        $Vehicebookings->update();

        AuditReportsController::store('Vehicle Management', 'Vehicle Booking edited ', "Edited by User", 0);
        return response()->json();
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
            ->where('vehicle_booking.status','!=', 13)
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

    public function vewApprovals(){

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Aproved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $vehicleapprovals = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id')
            ->where('vehicle_booking.status','!=', 10) //check if the booking is not approved
            ->where('vehicle_booking.status','!=',13) // check if the booking is not cancelled
            ->where('vehicle_booking.status','!=' ,14) // check if the booking is not declined
            ->get();


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

       // $data['employees'] = $employees;
        $data['vehicleapprovals'] = $vehicleapprovals;
        $data['usageType'] = $usageType;
        $data['bookingStatus'] = $bookingStatus;
        
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Approval';
        AuditReportsController::store('Vehicle Management', 'Vehicle Approvals Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.vehiclebooking_approvals')->with($data);
    }
    public  function collect_vehicle(vehicle_booking $collect){

        //return $collect->id;
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $collect->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $collect->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $collect->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        if ($collect->status == 10) {
            $bookingID = $collect->id;
            //return $ID;

            $vehiclebookings = DB::table('vehicle_booking')
                ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname')
                ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
                ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
                ->where('vehicle_booking.id',$bookingID )
                ->orderBy('vehicle_booking.id')
                ->first();
           // return $vehiclebookings;

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['collect'] = $collect;
            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehiclebookings'] = $vehiclebookings;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed ', "Accessed by User", 0);
            return view('Vehicles.Create_request.vehiclecollection')->with($data);
        } else
            return back();

    }
    public function cancel_booking(Request $request, vehicle_booking $booking)
    {
        $booking->canceller_id = $loggedInEmplID = Auth::user()->person->id;
        $booking->canceller_timestamp = $currentDate = time();
        $booking->status = 13;
        $booking->update();

        $hrID = Auth::user()->person->id;

        $BookingDetails = array();
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);

         Mail::to($BookingDetail['email'])->send(new vehiclebooking_cancellation($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email']));

        AuditReportsController::store('Vehicle Management', 'Booking   Cancelled', "Booking has been Cancelled", 0);
        return back();

    }

     public function Approve_booking( vehicle_booking $approve)
    {
        $approve->approver3_id = $loggedInEmplID = Auth::user()->person->id;
        $approve->approver3_timestamp = $currentDate = time();
        $approve->status =  10;
        $approve->update();

        $hrID = Auth::user()->person->id;
        $BookingDetails = array();
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);

         Mail::to($BookingDetail['email'])->send(new vehiclebooking_approval($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email']));

        AuditReportsController::store('Vehicle Management', 'Booking   Approved', "Booking has been Approved", 0);
      return back()->with('success_application', "vehiclebooking Booking Approval was successful.");
      
    }
    public function Decline_booking(Request $request, vehicle_booking $booking)
    {
        $this->validate($request, [
            //'description' => 'numeric',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $booking->rejector_id = $loggedInEmplID = Auth::user()->person->id;
        $booking->reject_reason = $vehicleData['description'];
        $booking->rejector_timestamp = $currentDate = time();
        $booking->status =  14;
        $booking->update();

        $hrID = Auth::user()->person->id;
        $BookingDetails = array();
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);
         Mail::to($BookingDetail['email'])->send(new vehiclebooking_rejection($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email']));

        AuditReportsController::store('Vehicle Management', 'Booking   Declined', "Booking has been Declined", 0);
        return response()->json();
    }
    public function AddcollectionDoc(Request $request){
        $this->validate($request, [
//            'type' => 'required',
//            'description' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $collection =  new vehicle_collect_documents();
        $collection->type = $docData['doctype'];
        $collection->description = $docData['description'];
        $collection->upload_date = $currentDate = time();
        $collection->user_name = $loggedInEmplID = Auth::user()->person->id;
        $collection->vehicleID = $docData['vehicleID'];
        $collection->bookingID = $docData['bookingID'];
        $collection->save();
        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $collection->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $collection->document = $fileName;
                $collection->update();
            }
        }

        AuditReportsController::store('Vehicle Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }

    public function AddcollectionImage(Request $request){
        $this->validate($request, [
//            'type' => 'required',
//            'description' => 'required',
                'image' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $collectionImage =  new vehicle_collect_image();
        $collectionImage->name = $docData['name'];
        $collectionImage->description = $docData['description'];
        $collectionImage->upload_date = $currentDate = time();
        $collectionImage->user_name = $loggedInEmplID = Auth::user()->person->id;
        $collectionImage->vehicleID = $docData['vehicleID'];
        $collectionImage->bookingID = $docData['bookingID'];
        $collectionImage->save();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName =  $collectionImage->id . "image." . $fileExt;
                $request->file('image')->storeAs('image', $fileName);
                //Update file name in the database
                $collectionImage->image = $fileName;
                $collectionImage->update();
            }
        }


        AuditReportsController::store('Vehicle Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }
}