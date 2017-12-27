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
use App\vehicle_fuel_log;
use App\service_station;
use App\module_ribbons;
use App\ribbons_access;
use App\ContactCompany;
use App\vehicle_return_images;
use App\vehicle_return_documents;
use Illuminate\Http\Request;
use App\Mail\vehicle_bookings;
use App\Mail\confirm_collection;
use App\Mail\vehiclebooking_approval;
use App\Mail\vehiclebooking_cancellation;
use App\Mail\vehiclebooking_rejection;
use App\Mail\vehiclebooking_manager_notification;
use App\Mail\vehicle_confirm_collection;
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

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
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
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.UserID', $loggedInEmplID)
            ->where('vehicle_booking.status', '!=', 13)
            // ->where('vehicle_booking.status', '!=', 12)
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
        return view('Vehicles.Create_request.myvehiclebooking')->with($data);

    }

    public function vehiclerequest()
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

        // return $vehicleData;

        $hrDetails = HRPerson::where('status', 1)->get();

        $vehicletype = $request['vehicle_type'];
        $Company = $request['division_level_5'];
        $Department = $request['division_level_4'];
        $requiredFrom = $vehicleData['required_from'];
        $requiredTo = $vehicleData['required_to'];
        $startDate = strtotime($requiredFrom);
        $EndDate = strtotime($requiredTo);

        $vehiclebookings = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date ',
                'vehicle_booking.return_datetime as return_date ', 'vehicle_make.name as vehicle_make',
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
            /*->where(function ($query) use ($startDate) {
                if (!empty($startDate)) {
                    $query->where('vehicle_booking.require_datetime', '!=', $startDate);
                }
            })
            ->where(function ($query) use ($EndDate) {
                if (!empty($EndDate)) {
                    $query->where('vehicle_booking.return_datetime', '!=', $EndDate);
                }
            })*/
            ->where('vehicle_details.booking_status', '!=', 1)
            // ->where('vehicle_booking.status' , '=', 12 )
            ->orderBy('vehicle_details.id')
            ->get();

        $vehiclebooking = $vehiclebookings->unique('id');

        $vehicleDates = $startDate . ' - ' . $EndDate;
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

    public function viewBooking(Request $request, vehicle_maintenance $bookings, $requiredFrom)
    {

        $ID = $bookings->id;

        $startExplode = explode('-', $requiredFrom);
        $startdate = $startExplode[0];
        $enddate = $startExplode[1];
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

        // if ($bookings->status == 1) {
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
            10 => "Approved",
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
        $users = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $users->first_name . ' ' . $users->surname;
        $vehicleID = $vehicleData['vehicle_id'];
        $Employee = vehicle_maintenance::where('id', $vehicleID)->orderBy('id', 'desc')->get()->first();
        //return $Employee;

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
        $Vehiclebookings->cancel_status = 0;  // 0 is the for vehicle not booked
        $Vehiclebookings->save();

        DB::table('vehicle_details')->where('id', $request['vehicle_id'])->update(['booking_status' => 1]);

        #mail to User
        $usedetails = HRPerson::where('id', $loggedInEmplID)->select('first_name', 'surname', 'email')->first();

        #Driver Details
        $drivers = HRPerson::where('id', $request['driver'])
            ->select('first_name', 'surname')
            ->first();
        $driver = $drivers->first_name . ' ' . $drivers->surname;

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $firstname = $usedetails['first_name'];
        $surname = $usedetails['surname'];
        $email = $usedetails['email'];
        $required_from = $request['required_from'];
        $required_to = $request['required_to'];
        $Usage_type = $request['Usage_type'];
        $destination = $request['destination'];
        $purpose = $request['purpose'];
        $vehiclemodel = $Employee->vehicle_model;
        $vehicleMake = $Employee->vehicle_make;
        $vehicleType = $Employee->vehicle_type;
        $year = $Employee->year;
        $vehicle_model1 = vehiclemodel::where('id', $vehiclemodel)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $vehicleMake)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicleType)->get()->first();

        $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $year;

        #mail to user
        Mail::to($email)->send(new vehiclebooking_manager_notification($firstname, $surname, $email, $required_from, $required_to,
            $usageType[$Usage_type], $driver, $destination, $purpose, $vehicle_model));

        #mail to manager
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
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
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
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.UserID', $loggedInEmplID)
            ->where('vehicle_booking.status', '!=', 13)
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

    public function vewApprovals()
    {

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $vehicleapprovals = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as driver_firstname', 'hr_people.surname as driver_surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.status', '!=', 10)//check if the booking is not approved
            ->where('vehicle_booking.status', '!=', 11)//check if the vehicle is not collected
            ->where('vehicle_booking.status', '!=', 13)// check if the booking is not cancelled
            ->where('vehicle_booking.status', '!=', 12)// check if the booking is not cancelled
            ->where('vehicle_booking.status', '!=', 14)// check if the booking is not declined
            ->get();

        //return $vehicleapprovals;


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

    public function cancel_booking(Request $request, vehicle_booking $booking)
    {

        //return $booking;
        $booking->canceller_id = $loggedInEmplID = Auth::user()->person->id;
        $booking->canceller_timestamp = $currentDate = time();
        $booking->status = 13;
        $booking->update();

        $hrID = Auth::user()->person->id;

        $ID = $booking->vehicle_id;

        DB::table('vehicle_details')->where('id', $ID)->update(['booking_status' => 0]);

        $BookingDetails = array();
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        #
        $required_from = $booking->require_datetime;
        $required_to = $booking->return_datetime;
        $Usage_type = $booking->usage_type;
        $Driver = $booking->driver_id;
        $destination = $booking->destination;
        $purpose = $booking->purpose;
        $vehicmodel = $booking->vehicle_model;
        $vehicleypes = $booking->vehicle_type;
        $vehmake = $booking->vehicle_make;
        $year = $booking->year;

        $vehicle_model1 = vehiclemodel::where('id', $vehicmodel)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $vehmake)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicleypes)->get()->first();

        $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $year;

        #Driver Details
        $drivers = HRPerson::where('id', $Driver)->select('first_name', 'surname')->first();
        $driver = $drivers->first_name . ' ' . $drivers->surname;

        Mail::to($BookingDetail['email'])->send(new vehiclebooking_cancellation($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'], $required_from, $required_to, $usageType[$Usage_type], $driver, $destination, $purpose, $vehicle_model));
        AuditReportsController::store('Vehicle Management', 'Booking   Cancelled', "Booking has been Cancelled", 0);
        return back();

    }

    public function Approve_booking(vehicle_booking $approve)
    {
        $approve->approver3_id = $loggedInEmplID = Auth::user()->person->id;
        $approve->approver3_timestamp = $currentDate = time();
        $approve->status = 10;
        $approve->update();

        $ID = $approve->vehicle_id;
        DB::table('vehicle_details')
            ->where('id', $ID)
            ->update(['booking_status' => 1]);

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
        $booking->status = 14;
        $booking->update();

        $ID = $booking->vehicle_id;
        DB::table('vehicle_details')
            ->where('id', $ID)
            ->update(['booking_status' => 0]);

        $hrID = Auth::user()->person->id;
        $BookingDetails = array();
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $hrID);
        Mail::to($BookingDetail['email'])->send(new vehiclebooking_rejection($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email']));

        AuditReportsController::store('Vehicle Management', 'Booking   Declined', "Booking has been Declined", 0);
        return response()->json();
    }

    public function collect_vehicle(vehicle_booking $collect)
    {

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

        //  if ($collect->status == 10) {
        $bookingID = $collect->id;
        //return $ID;

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->where('vehicle_booking.id', $bookingID)
            ->orderBy('vehicle_booking.id')
            ->first();


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
    }

    public function AddcollectionDoc(Request $request)
    {
        $this->validate($request, [
//            'type' => 'required',
            //  'document' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $collection = new vehicle_collect_documents();
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
                $request->file('documents')->storeAs('projects/collectiondocuments', $fileName);
                //Update file name in the table
                $collection->document = $fileName;
                $collection->update();
            }
        }

        AuditReportsController::store('Vehicle Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }

    public function AddcollectionImage( Request $request , vehicle_collect_image $collectionImage)
    {
        $this->validate($request, [
           // 'type' => 'required',
           'description' => 'required',
            //  'image' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

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
                $fileName = $collectionImage->id . "image." . $fileExt;
                $request->file('image')->storeAs('collectionimage', $fileName);
                //Update file name in the database
                $collectionImage->image = $fileName;
                $collectionImage->update();
            }
        }


        AuditReportsController::store('Vehicle Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }

    public function confrmCollection(Request $request, vehicle_booking $confirm)
    {

        $this->validate($request, [
            'start_mileage_id' => 'required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $confirm->collector_id = $loggedInEmplID = Auth::user()->person->id;
        $confirm->status = 11;
        $confirm->start_mileage_id = $vehicleData['start_mileage_id'];
        $confirm->collect_timestamp = $currentDate = time();
        $confirm->update();
        $ID = $confirm->id;

        $loggedInEmplID = Auth::user()->person->id;
        $BookingDetail = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();

        $vehicmodel = $confirm->vehicle_model;
        $vehicleypes = $confirm->vehicle_type;
        $vehmake = $confirm->vehicle_make;
        $year = $confirm->year;

        $vehicle_model1 = vehiclemodel::where('id', $vehicmodel)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $vehmake)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicleypes)->get()->first();

        $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $year;

        #check if the images have beeen uploaded
        $vehiclecollectimage = vehicle_collect_image::where()->first();
        $vehiclecollectdocuments = vehicle_collect_documents::where()->first();
        #mail to manager
         Mail::to($BookingDetail['email'])->send(new vehicle_confirm_collection($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'],$vehicle_model ));

        AuditReportsController::store('Vehicle Management', 'Vehicle Has Been Collected  ', "Booking has been Collected", 0);
        return redirect()->to('/vehicle_management/collect/' . $ID);

    }

    public function returnVehicle(vehicle_booking $returnVeh)
    {

        //return $ $returnVeh->id;
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $returnVeh->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $returnVeh->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $returnVeh->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $fueltank = vehicle_fuel_log::orderBy('id', 'desc')->get();
        $servicestation = service_station::orderBy('id', 'desc')->get();

        //  if ($collect->status == 10) {
        $bookingID = $returnVeh->id;
        //return $ID;

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->where('vehicle_booking.id', $bookingID)
            ->orderBy('vehicle_booking.id')
            ->first();


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['servicestation'] = $servicestation;
        $data['fueltank'] = $fueltank;
        $data['returnVeh'] = $returnVeh;
        $data['name'] = $name;
        $data['employees'] = $employees;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.returnvehicle')->with($data);
    }

    public function AddreturnDoc(Request $request)
    {
        $this->validate($request, [
//            'type' => 'required',
            //  'document' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $returnVehicle = new vehicle_return_documents();
        $returnVehicle->type = $docData['doctype'];
        $returnVehicle->description = $docData['description'];
        $returnVehicle->upload_date = $currentDate = time();
        $returnVehicle->user_name = $loggedInEmplID = Auth::user()->person->id;
        $returnVehicle->vehicleID = $docData['vehicleID'];
        $returnVehicle->bookingID = $docData['bookingID'];
        $returnVehicle->save();
        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $returnVehicle->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/returndocuments', $fileName);
                //Update file name in the table
                $returnVehicle->document = $fileName;
                $returnVehicle->update();
            }
        }

        AuditReportsController::store('Vehicle Management', 'vehicle return Document Uploaded ', "vehicle return Document Uploaded ", 0);
        return response()->json();
    }

    public function AddreturnImage(Request $request , vehicle_return_images  $returnImage )
    {
        $this->validate($request, [
//            'type' => 'required',
//            'description' => 'required',
            //  'image' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $returnImage->name = $docData['name'];
        $returnImage->description = $docData['description'];
        $returnImage->upload_date = $currentDate = time();
        $returnImage->user_name = $loggedInEmplID = Auth::user()->person->id;
        $returnImage->vehicleID = $docData['vehicleID'];
        $returnImage->bookingID = $docData['bookingID'];
        $returnImage->save();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = $returnImage->id . "image." . $fileExt;
                $request->file('image')->storeAs('vehiclereturnImage', $fileName);
                //Update file name in the database
                $returnImage->image = $fileName;
                $returnImage->update();
            }
        }


        AuditReportsController::store('Vehicle Management', 'Return vehicle Image Uploaded ', "Return vehicle Image Uploaded ", 0);
        return response()->json();
    }

    public function confirmReturn(Request $request, vehicle_booking $confirm)
    {

        $this->validate($request, [
            'end_mileage_id' => 'required',
            //'Returned_At' => 'required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $confirm->collector_id = $loggedInEmplID = Auth::user()->person->id;
        $confirm->status = 12;
        $confirm->end_mileage_id = $vehicleData['end_mileage_id'];
        $confirm->return_timestamp = strtotime($vehicleData['return_timestamp']);
        $confirm->collect_timestamp = $currentDate = time();
        $confirm->update();
        $ID = $confirm->id;

        $ID = $confirm->vehicle_id;
        DB::table('vehicle_details')
            ->where('id', $ID)
            ->update(['booking_status' => 0]);

        AuditReportsController::store('Vehicle Management', 'Vehicle Has Been Collected  ', "Booking has been Collected", 0);
        return redirect()->to('/vehicle_management/return_vehicle/' . $ID);

    }

    public function viewVehicleIspectionDocs(vehicle_booking $ispection)
    {

        $ID = $ispection->id;
     

        $vehicleID = $ispection->vehicle_id;


      

        $vehicle = vehicle_maintenance::where('id', $ID )->get()->first();


        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $servicestation = service_station::orderBy('id', 'desc')->get();
        // $fueltank = tank::orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        #vehicle collect documents
        $vehiclecollectdocuments = vehicle_collect_documents::where('vehicleID', $ID )->get()->first();
         #vehicle collect images
        $vehiclecollectimage = vehicle_collect_image::where('vehicleID', $ID)->get();
        //return $vehiclecollectimage;
        #vehicle return documents
        $vehiclereturndocuments = vehicle_return_documents::where('vehicleID', $ID)->get()->first();
        #vehicle return documents
        $vehiclereturnimages = vehicle_return_images::where('vehicleID', $ID)->get();
       //return  $vehiclereturnimages;

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $ispection->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $ispection->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $ispection->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        //return $name;
        ###################>>>>>#################
        $fineType = array(1 => 'Accident', 2 => 'Mechanical Fault', 3 => 'Electronic Fault', 4 => 'Damaged', 5 => 'Attempted Hi-jacking', 6 => 'Hi-jacking', 7 => 'Other');

        $status = array(1 => 'Tank', 2 => 'Other');
        $transType = array(1 => 'Full Tank', 2 => 'Top Up');


        //  $vehicleID = $maintenance->id;

        // $bookingStatus = array(2 => "Pending Capturer Manager Approval",
        //  1 => "Pending Driver Manager Approval",
        //  3 => "Pending HOD Approval",
        //  4 => "Pending Admin Approval",
        //  10 => "Approved",
        //  11 => "Collected",
        //  12 => "Returned",
        //  13 => "Cancelled",
        //  14 => "Rejected");

        //  $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        //   $vehiclebookinglog = DB::table('vehicle_booking')
        //  ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
        //      'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
        //      'hr_people.first_name as firstname', 'hr_people.surname as surname',
        //      'vehicle_collect_documents.document as collectDoc' ,'vehicle_return_documents.document as returnDoc'
        //  )
        //  ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
        //  ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
        //  ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
        //  ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
        //  ->leftJoin('vehicle_collect_documents' ,'vehicle_booking.id' , '=' , 'vehicle_collect_documents.bookingID' )
        //  ->leftJoin('vehicle_return_documents' ,'vehicle_booking.id' , '=' , 'vehicle_return_documents.bookingID' )
        //  ->orderBy('vehicle_booking.id', 'desc')
        //  ->where('vehicle_booking.vehicle_id', $ID)
        //  ->get();

        //return $vehiclebookinglog;


        //return $vehiclefine
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['vehicle'] = $vehicle;
        $data['ContactCompany'] = $ContactCompany;
        $data['loggedInEmplID'] = $loggedInEmplID;
        $data['name'] = $name;
        $data['servicestation'] = $servicestation;
        $data['vehiclecollectdocuments'] = $vehiclecollectdocuments;
        $data['vehiclecollectimage'] = $vehiclecollectimage;
        $data['vehiclereturndocuments'] = $vehiclereturndocuments;
        $data['vehiclereturnimages'] = $vehiclereturnimages;
        $data['status'] = $status;
        $data['vehicleID'] = $vehicleID;
        $data['fineType'] = $fineType;
        $data['IssuedTo'] = $IssuedTo;
        $data['keyStatus'] = $keyStatus;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        // $data['vehiclebookinglog'] = $vehiclebookinglog;
        $data['ispection'] = $ispection;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.ViewispectionDocs')->with($data);

    }

}
