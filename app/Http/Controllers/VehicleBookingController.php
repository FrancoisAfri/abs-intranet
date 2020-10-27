<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\DivisionLevel;
use App\DivisionLevelFour;
use App\HRPerson;
use App\Http\Requests;
use App\images;
use App\incident_type;
use App\fleet_fillingstation;
use App\Mail\confirm_collection;
use App\Mail\vehicle_bookings;
use App\Mail\vehiclebooking_approval;
use App\Mail\vehiclebooking_cancellation;
use App\Mail\vehiclebooking_manager_notification;
use App\Mail\vehiclebooking_rejection;
use App\safe;
use App\service_station;
use App\tank;
use App\Users;
use App\vehicle;
use App\vehicle_booking;
use App\vehicle_collect_documents;
use App\vehicle_collect_image;
use App\vehicle_config;
use App\vehicle_detail;
use App\vehicle_fuel_log;
use App\Vehicle_managemnt;
use App\vehicle_milege;
use App\vehicle_return_documents;
use App\vehicle_return_images;
use App\vehiclemake;
use App\vehiclemodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake', 'vehicle_model.name as vehicleModel',
                'vehicle_managemnet.name as vehicleType', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'vehicle_details.image')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.UserID', $loggedInEmplID)
            ->where('vehicle_booking.status', '!=', 13)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'My Bookings';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.myvehiclebooking')->with($data);
    }

    public function vehiclerequest()
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('name', 'asc')->get();

        $data['page_title'] = "Fleet Search";
        $data['page_description'] = "Search Fleet For Booking";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Bookings', 'active' => 1, 'is_module' => 0]
        ];
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'My Bookings';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
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
        $hrDetails = HRPerson::where('status', 1)->get();
        $vehicletype = $request['vehicle_type'];
        $Company = $request['division_level_5'];
        $Department = $request['division_level_4'];
        $requiredFrom = $vehicleData['required_from'];
        $requiredTo = $vehicleData['required_to'];
        $startDate = strtotime($requiredFrom);
        $EndDate = strtotime($requiredTo);
        $status = array(1 => 'Active', 2 => 'Require Approval', 3 => 'Rejected', 4 => 'Inactive');

        $vehiclebookings = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date ', 'vehicle_booking.return_datetime as return_date ',
                'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicletype',
                'division_level_fives.name as company', 'division_level_fours.name as Department', 'vehicle_incidents.severity as Severity')
            ->leftJoin('vehicle_incidents', 'vehicle_details.id', '=', 'vehicle_incidents.vehicleID')
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
            ->where('vehicle_details.status', 1)
            ->orderBy('vehicle_details.id', 'asc')
            ->get();
        $vehiclebooking = $vehiclebookings->unique('id');
        $vehicleDates = $startDate . ' - ' . $EndDate;
        $data['$vehicleDates'] = $vehicleDates;
        $data['hrDetails'] = $hrDetails;
        $data['vehicleDates'] = $vehicleDates;
        $data['vehiclebooking'] = $vehiclebooking;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Internal Fleet Management Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'My Bookings';
        AuditReportsController::store('Fleet Management', 'View Vehicle Search Results', "view Audit Results", 0);
        return view('Vehicles.Create_request.search_results')->with($data);
    }

    public function viewBooking(Request $request, vehicle_detail $vehicle, $requiredFrom)
    {
        $startExplode = explode('-', $requiredFrom);
        $startdate = $startExplode[0];
        $enddate = $startExplode[1];

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $vehicle->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $vehicle->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicle->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;

        $data['startdate'] = $startdate;
        $data['enddate'] = $enddate;
        $data['employees'] = $employees;

        $data['vehicle'] = $vehicle;

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.vehiclebookings')->with($data);
    }
	
	public function viewBookingDetails(vehicle_booking $booking)
    {
        $employee = HRPerson::where('status', 1)->where('id', $booking->driver_id)->first();
        $vehicle = vehicle_detail::where('id', $booking->vehicle_id)->where('status', 1)->first();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $vehicle->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $vehicle->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicle->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############
		$usageTypes = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
		$bookingStatuses = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
        $data['page_title'] = " View Booking";
        $data['page_description'] = "Booking";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['employee'] = $employee;
        $data['vehicle'] = $vehicle;
		$data['booking'] = $booking;
        $data['usageTypes'] = $usageTypes;
        $data['bookingStatuses'] = $bookingStatuses;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'View Booking', "Accessed by User", 0);
        return view('Vehicles.Create_request.view_booking')->with($data);
    }

    public function BookingDetails($status = 0, $hrID = 0, $driverID = 0)
    {
		
		$approvals = DB::table('vehicle_configuration')->select('approval_manager_capturer', 'approval_manager_driver', 'approval_hod', 'approval_admin')->first();
        if (empty($approvals)) {
            $vehicleconfig = new vehicle_config();
            $vehicleconfig->approval_manager_capturer = 0;
            $vehicleconfig->approval_manager_driver = 0;
            $vehicleconfig->approval_hod = 0;
            $vehicleconfig->approval_admin = 0;
            $vehicleconfig->save();
        }

        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();
//return $hrDetails;
        if ($approvals->approval_manager_capturer == 1) {
			
            # code...
            // query the hrperon  model and bring back the values of the manager
			if (!empty($hrDetails->manager_id))
			{
				$managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)->select('first_name', 'surname', 'email')->first();
				if (!empty($managerDetails->email)) {
					$details = array('status' => 2, 'first_name' => $managerDetails->first_name, 'surname' => $managerDetails->surname, 'email' => $managerDetails->email, 'comment' => '');
					return $details;
				} 
				else {
                $details = array('status' => 2, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'Employee Manager Details are Incorrect.');
                return $details;
				}
			}
			else 
			{
                $details = array('status' => 2, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'No manager has been assigned to employee.');
                return $details;
			}
        } 
		elseif ($approvals->approval_manager_capturer == 2) {
			// Driver manager approval...
			
			$driverDetails = HRPerson::where('id', $driverID)->where('status', 1)->first();
			if (!empty($driverDetails->manager_id))
			{
				$manager = HRPerson::where('id', $driverDetails->manager_id)->where('status', 1)->select('first_name', 'surname', 'email')->first();
				if (!empty($manager->email) && !empty($manager->first_name)) 
				{
					$details = array('status' => 1, 'first_name' => $manager->first_name, 'surname' => $manager->surname, 'email' => $manager->email, 'comment' => '');
					return $details;
				} 
				else 
				{
					$details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'Driver Manager Details are Incorrect.');
					return $details;
				}
			}
			else
			{
				$details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'No manager has been assigned to driver.');
				return $details;
			}
        } 
		elseif ($approvals->approval_manager_capturer == 3) {
			
			if (!empty($hrDetails->division_level_4))
			{
				$Dept = DivisionLevelFour::where('manager_id', $hrDetails->division_level_4)->get()->first();
				if (!empty($Dept->manager_id))
				{
					$hodmamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)->select('first_name', 'surname', 'email')->first();

					if (!empty($hodmamgerDetails->email)) {
						$details = array('status' => 3, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email, 'comment' => '');
						return $details;
					} else {
						$details = array('status' => 3, 'first_name' => $hrDetails->firstname, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'Department manager details are incorrect.');
						return $details;
					}
				}
				else {
					$details = array('status' => 3, 'first_name' => $hrDetails->firstname, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'No manager has been assigned to this department.');
					return $details;
				}
			}
			else
			{
				$details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'No Department has been assigned to employee.');
				return $details;
			}   
        } 
		elseif ($approvals->approval_manager_capturer == 4) 
		{
			$details = array('status' => 4, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => '');
            return $details;
        }
		else {
            $details = array('status' => 10, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email, 'comment' => 'Auto Approval has been performed.');
            return $details;
        }
    }

    public function status($status = 0)
    {
        $aStatusses = array(2 => "Pending Manager Approval",
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

    public function vehiclebooking(Request $request, vehicle_detail $vehicle)
    {
        $this->validate($request, [
            //'driver ' => 'required'
            'driver' => 'bail|required',
            'usage_type' => 'bail|required',
            'purpose' => 'required',
            'destination' => 'required',
            'metre_reading_type' => 'required',
			'odometer_reading' => 'required_if:metre_reading_type,1',
			'hours_reading' => 'required_if:metre_reading_type,2',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        // call the status function
        $BookingDetails = array();
        $loggedInEmplID = Auth::user()->person->id;

        $driverID = $vehicleData['driver'];
        $hrID = $loggedInEmplID;
        $BookingDetail = VehicleBookingController::BookingDetails(0, $hrID, $driverID);
        $vehicleDetails = vehicle_detail::where('id', $vehicle->id)->orderBy('id', 'desc')->first();
        $users = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $users->first_name . ' ' . $users->surname;

        $Vehiclebookings = new vehicle_booking();
        $Vehiclebookings->vehicle_type = !empty($vehicle->vehicle_type) ? $vehicle->vehicle_type : 0;
        $Vehiclebookings->vehicle_model = !empty($vehicle->vehicle_model) ? $vehicle->vehicle_model : 0;
        $Vehiclebookings->vehicle_make = !empty($vehicle->vehicle_make) ? $vehicle->vehicle_make : 0;
        $Vehiclebookings->year = !empty($vehicle->year) ? $vehicle->year : 0;
        $Vehiclebookings->fleet_number = !empty($vehicle->fleet_number) ? $vehicle->fleet_number : 0;
        $Vehiclebookings->vehicle_reg = !empty($vehicle->vehicle_registration) ? $vehicle->vehicle_registration : 0;
        $Vehiclebookings->require_datetime = $request['required_from'];
        $Vehiclebookings->return_datetime = $request['required_to'];
        $Vehiclebookings->usage_type = $request['usage_type'];
        $Vehiclebookings->driver_id = $request['driver'];
        $Vehiclebookings->purpose = $vehicleData['purpose'];
        $Vehiclebookings->destination = $request['destination'];
        $Vehiclebookings->vehicle_id = $request['vehicle_id'];
        $Vehiclebookings->capturer_id = $name;
        $Vehiclebookings->UserID = $loggedInEmplID;
        if ($BookingDetail['status'] == 10) {
            $Vehiclebookings->status = $BookingDetail['status'];
            $Vehiclebookings->approver3_id = $loggedInEmplID;
        } else
            $Vehiclebookings->status = $BookingDetail['status'];
        $Vehiclebookings->cancel_status = 0;  // 0 is the for vehicle not booked
        if ($vehicleDetails->metre_reading_type === 1)
            $Vehiclebookings->start_mileage_id = !empty($vehicleData['odometer_reading']) ? $vehicleData['odometer_reading'] : 0;
        else
            $Vehiclebookings->start_mileage_id = !empty($vehicleData['hours_reading']) ? $vehicleData['hours_reading'] : 0;

        $Vehiclebookings->booking_date = time();
        $Vehiclebookings->save();

        $vehicle->booking_status = 1;
        $vehicle->update();
        #mail to User
        $usedetails = HRPerson::where('id', $loggedInEmplID)->select('first_name', 'surname', 'email')->first();

        #Driver Details
        $drivers = HRPerson::where('id', $request['driver'])
            ->select('first_name', 'surname')
            ->first();
        $driver = $drivers->first_name . ' ' . $drivers->surname;

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        $vehicle_model1 = vehiclemodel::where('id', $Vehiclebookings->vehicle_model)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $Vehiclebookings->vehicle_make)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $Vehiclebookings->vehicle_type)->get()->first();

        if (empty($vehicle_model1) || empty($vehiclemaker) || empty($vehicleTypes) || empty($Vehiclebookings->year))
            $vehicle_model = '';
        else
            $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $Vehiclebookings->year;

        //not tested
        #mail to manager
		if ($BookingDetail['status'] == 4) 
		{
			$admins = DB::table('security_modules_access')
			   ->select('security_modules_access.*','security_modules.*') 
			   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
			   ->where('code_name', 'vehicle')
			   ->where('access_level','>=', 4)
			   ->pluck('user_id');
			if (!empty($admins))
			{
				foreach ($admins as $admin) {
					$adminDetails = HRPerson::where('user_id', $admin)->where('status', 1)->select('first_name', 'surname', 'email')->first();
					if (!empty($adminDetails->email))
					{
						Mail::to($adminDetails->email)->send(new vehiclebooking_manager_notification($adminDetails->first_name, $adminDetails->surname, $adminDetails->email, $request['required_from'], $request['required_to'], $usageType[$request['usage_type']], $driver, $request['destination'], $request['purpose'], $vehicle_model,''));
					}
					else 
					{
						if (!empty($BookingDetail['email']))
							Mail::to($BookingDetail['email'])->send(new vehiclebooking_manager_notification($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'], $request['required_from'], $request['required_to'], $usageType[$request['usage_type']], $driver, $request['destination'], $request['purpose'], $vehicle_model,"Administration details are incorrect."));
					}
				}
			}
			else 
			{
				if (!empty($BookingDetail['email']))
					Mail::to($BookingDetail['email'])->send(new vehiclebooking_manager_notification($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'], $request['required_from'], $request['required_to'], $usageType[$request['usage_type']], $driver, $request['destination'], $request['purpose'], $vehicle_model,"No administration have been assigned to this module."));
			}
		}
		else
		{
			if (!empty($BookingDetail['email']))
				Mail::to($BookingDetail['email'])->send(new vehiclebooking_manager_notification($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'], $request['required_from'], $request['required_to'], $usageType[$request['usage_type']], $driver, $request['destination'], $request['purpose'], $vehicle_model,$BookingDetail['comment']));
		}
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed by User", 0);
        return redirect('/vehicle_management/vehiclebooking_results')->with('success_application', "Vehicle Booking application was successful.");
    }

    public function edit_bookings(Request $request, vehicle_booking $booking)
    {
        $this->validate($request, [
            // 'description' => 'numeric',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        $BookingDetails = array();

        $hrID = $vehicleData['driver'];
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        $vehicleID = $vehicleData['vehicle_id'];

        $booking->require_datetime = strtotime($vehicleData['required_from']);
        $booking->return_datetime = strtotime($vehicleData['required_to']);
        $booking->usage_type = $vehicleData['usage_type'];
        $booking->driver_id = $vehicleData['driver'];
        $booking->purpose = $vehicleData['purpose'];
        $booking->destination = $vehicleData['destination'];
        $booking->capturer_id = $name;
        $booking->UserID = $loggedInEmplID;
        $booking->cancel_status = 0;
        $booking->update();

        AuditReportsController::store('Fleet Management', 'Vehicle Booking edited ', "Edited by User", 0);
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
        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake', 'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.UserID', $loggedInEmplID)
            ->where('vehicle_booking.status', '!=', 13)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.vehiclebooking_results')->with($data);
    }

    public function vewApprovals()
    {
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $vehicleapprovals = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake', 'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType', 'hr_people.first_name as driver_firstname', 'hr_people.surname as driver_surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->whereNotIn('vehicle_booking.status', [10, 11, 12, 13, 14])//check if the booking is not approved
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicleapprovals'] = $vehicleapprovals;
        $data['usageType'] = $usageType;
        $data['bookingStatus'] = $bookingStatus;

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Booking Approval';
        AuditReportsController::store('Fleet Management', 'Vehicle Approvals Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.vehiclebooking_approvals')->with($data);
    }

    public function cancel_booking(Request $request, vehicle_booking $booking)
    {
        $booking->canceller_id = $loggedInEmplID = Auth::user()->person->id;
        $booking->canceller_timestamp = $currentDate = time();
        $booking->status = 13;
        $booking->update();

        $hrID = Auth::user()->person->id;

        $ID = $booking->vehicle_id;

        DB::table('vehicle_details')->where('id', $ID)->update(['booking_status' => 0]);

        $BookingDetail = HRPerson::where('id', $booking->UserID)->select('first_name', 'surname', 'email')->first();
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
		;

        $vehicle_model1 = vehiclemodel::where('id', $vehicmodel)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $vehmake)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicleypes)->get()->first();

        $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $year;

        #Driver Details
        $drivers = HRPerson::where('id', $Driver)->select('first_name', 'surname')->first();
        $driver = $drivers->first_name . ' ' . $drivers->surname;
		if (!empty($BookingDetail->email))
			Mail::to($BookingDetail->email)->send(new vehiclebooking_cancellation($BookingDetail->first_name, $BookingDetail->surname, $BookingDetail->email, $required_from, $required_to, $usageType[$Usage_type], $driver, $destination, $purpose, $vehicle_model));
        AuditReportsController::store('Fleet Management', 'Booking   Cancelled', "Booking has been Cancelled", 0);
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
		$BookingDetail = HRPerson::where('id', $approve->UserID)->select('first_name', 'surname', 'email')->first();
		if (!empty($BookingDetail->email))
			Mail::to($BookingDetail->email)->send(new vehiclebooking_approval($BookingDetail->first_name, $BookingDetail->surname, $BookingDetail->email));

        AuditReportsController::store('Fleet Management', 'Booking   Approved', "Booking has been Approved", 0);
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
        $BookingDetail = HRPerson::where('id', $booking->UserID)->select('first_name', 'surname', 'email')->first();
		if (!empty($BookingDetail->email))
        Mail::to($BookingDetail->email)->send(new vehiclebooking_rejection($BookingDetail->first_name, $BookingDetail->surname, $BookingDetail->email));

        AuditReportsController::store('Fleet Management', 'Booking   Declined', "Booking has been Declined", 0);
        return response()->json();
    }

    public function collect_vehicle(vehicle_booking $collect)
    {
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $collect->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $collect->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $collect->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $InforceVehiclerules = DB::table('vehicle_configuration')->select('inforce_vehicle_image', 'inforce_vehicle_documents', 'include_inspection_document')->first();

        if (empty($InforceVehiclerules)) {
            $vehicleconfig = new vehicle_config();
            $vehicleconfig->inforce_vehicle_image = 0;
            $vehicleconfig->inforce_vehicle_documents = 0;
            $vehicleconfig->include_inspection_document = 0;
            $vehicleconfig->save();
        }
        $bookingID = $collect->id;
        $doc = vehicle_collect_documents::count();
        $image = vehicle_collect_image::count();

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make', 'vehicle_details.metre_reading_type',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->where('vehicle_booking.id', $bookingID)
            ->orderBy('vehicle_booking.id')
            ->first();


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'My Bookings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['doc'] = $doc;
        $data['InforceVehiclerules'] = $InforceVehiclerules;
        $data['image'] = $image;
        $data['collect'] = $collect;
        $data['name'] = $name;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'My Bookings';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/collectiondocuments', $fileName);
                //Update file name in the table
                $collection->document = $fileName;
                $collection->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }

    public function AddcollectionImage(Request $request, vehicle_collect_image $collectionImage)
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
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/collectionimages', $fileName);
                //Update file name in the database
                $collectionImage->image = $fileName;
                $collectionImage->update();
            }
        }
        AuditReportsController::store('Fleet Management', 'Collection Document Uploaded ', "Collection Document Uploaded ", 0);
        return response()->json();
    }

    public function confrmCollection(Request $request, vehicle_booking $confirm)
    {
        $this->validate($request, [
            'start_mileage_id' => 'required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);
        $loggedInEmplID = Auth::user()->person->id;

        $vehicleID = $confirm->vehicle_id;
        $vehicleDetails = vehicle_detail::where('id', $vehicleID)->orderBy('id', 'desc')->first();

        $confirm->collector_id = $loggedInEmplID;
        $confirm->status = 11;
        $confirm->collect_timestamp = time();
        $confirm->update();
        $ID = $confirm->id;
        $BookingDetail = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $vehicmodel = $confirm->vehicle_model;
        $vehicleypes = $confirm->vehicle_type;
        $vehmake = $confirm->vehicle_make;
        $year = $confirm->year;

        $vehicle_model1 = vehiclemodel::where('id', $vehicmodel)->get()->first();
        $vehiclemaker = vehiclemake::where('id', $vehmake)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $vehicleypes)->get()->first();
        if (empty($vehicle_model1) || empty($vehiclemaker) || empty($vehicleTypes) || empty($year))
            $vehicle_model = '';
        else
            $vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $confirm->year;

        //$vehicle_model = $vehiclemaker->name . ' ' . $vehicle_model1->name . ' ' . $vehicleTypes->name . ' ' . $year;
        //return $vehicle_model;
        //types
        //1 =  vehicle creation
        // 2 =  vehicle collected
        //3= vehicle returned
        $Vehiclemilege = new vehicle_milege();
        $Vehiclemilege->date_created = time();
        $Vehiclemilege->date_taken = time();
        $Vehiclemilege->vehicle_id = $confirm->vehicle_id;
		if ($vehicleDetails->metre_reading_type === 1)
            $Vehiclemilege->odometer_reading = $vehicleData['start_mileage_id'];
        else
            $Vehiclemilege->hours_reading = $vehicleData['start_mileage_id'];
        $Vehiclemilege->type = 2;
        $Vehiclemilege->booking_id = $confirm->id;
        $Vehiclemilege->save();

        #check if the images have beeen uploaded
        $vehiclecollectimage = vehicle_collect_image::where('vehicleID', $confirm->vehicle_id)->first();
        $vehiclecollectdocuments = vehicle_collect_documents::where('vehicleID', $confirm->vehicle_id)->first();
        // return $vehiclecollectdocuments;
        #mail to manager
        // Mail::to($BookingDetail['email'])->send(new vehicle_confirm_collection($BookingDetail['first_name'], $BookingDetail['surname'], $BookingDetail['email'], $vehicle_model));

        AuditReportsController::store('Fleet Management', 'Vehicle Has Been Collected  ', "Booking has been Collected", 0);
        return redirect()->to('/vehicle_management/create_request')->with('success_application', "Vehicle Collection  was successful.");
    }

    public function returnVehicle(vehicle_booking $returnVeh)
    {
        #######rn $ $returnVeh->id;########### WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $returnVeh->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $returnVeh->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $returnVeh->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################

        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $vehicle_fuel_log = vehicle_fuel_log::orderBy('id', 'desc')->get();
        $fueltank = tank::orderBy('id', 'desc')->get();
        $servicestation = fleet_fillingstation::orderBy('name', 'asc')->get();
        $bookingID = $returnVeh->id;
        $doc = vehicle_return_documents::count();
        $image = vehicle_return_images::count();
        $incidentType = incident_type::orderBy('id', 'asc')->get();
        $InforceVehiclerules = DB::table('vehicle_configuration')->select('inforce_vehicle_image', 'inforce_vehicle_documents', 'include_inspection_document')->first();

        if (empty($InforceVehiclerules)) {
            $vehicleconfig = new vehicle_config();
            $vehicleconfig->inforce_vehicle_image = 0;
            $vehicleconfig->inforce_vehicle_documents = 0;
            $vehicleconfig->include_inspection_document = 0;
            $vehicleconfig->save();
        }

        $vehiclebookings = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_details.*', 'vehicle_details.name as vehicle_make', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->where('vehicle_booking.id', $bookingID)
            ->orderBy('vehicle_booking.id')
            ->first();

        $OdometerReading = vehicle_milege::where('booking_id', $bookingID)->latest()->first();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['incidentType'] = $incidentType;
        $data['OdometerReading'] = $OdometerReading;
        $data['InforceVehiclerules'] = $InforceVehiclerules;
        $data['image'] = $image;
        $data['doc'] = $doc;
        $data['servicestation'] = $servicestation;
        $data['fueltank'] = $fueltank;
        $data['returnVeh'] = $returnVeh;
        $data['name'] = $name;
        $data['employees'] = $employees;
        $data['bookingID'] = $bookingID;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/returndocuments', $fileName);
                //Update file name in the table
                $returnVehicle->document = $fileName;
                $returnVehicle->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'vehicle return Document Uploaded ', "vehicle return Document Uploaded ", 0);
        return response()->json();
    }

    public function AddreturnImage(Request $request, vehicle_return_images $returnImage)
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
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/returnImages', $fileName);
                //Update file name in the database
                $returnImage->image = $fileName;
                $returnImage->update();
            }
        }
        AuditReportsController::store('Fleet Management', 'Return vehicle Image Uploaded ', "Return vehicle Image Uploaded ", 0);
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
            ->update(['booking_status' => 0, 'odometer_reading' => $vehicleData['end_mileage_id']]);

        //types
        //1 =  vehicle creation
        // 2 =  vehicle collected
        //3 = vehicle returned

        $Vehiclemilege = new vehicle_milege();
        $Vehiclemilege->date_created = time();
        $Vehiclemilege->vehicle_id = $confirm->vehicle_id;
        $Vehiclemilege->odometer_reading = $vehicleData['end_mileage_id'];
        //$Vehiclemilege->hours_reading = !empty($SysData['hours_reading']) ? $SysData['hours_reading'] : '';
        $Vehiclemilege->type = 3;
        $Vehiclemilege->booking_id = $confirm->id;
        $Vehiclemilege->save();

        AuditReportsController::store('Fleet Management', 'Vehicle Has Been Collected  ', "Booking has been Collected", 0);
        return redirect()->to('/vehicle_management/create_request')->with('success_application', "Vehicle Collection  was successful.");;
    }

    public function viewVehicleIspectionDocs(vehicle_booking $ispection)
    {
        $vehicle = vehicle_detail::where('id', $ispection->vehicle_id)->get()->first();
		
        #vehicle collect documents
        $vehiclecollectdocuments = vehicle_collect_documents::where('bookingID', $ispection->id)->get();
        #vehicle collect images
        $vehiclecollectimage = vehicle_collect_image::where('bookingID', $ispection->id)->get();
        #vehicle return documents
        $vehiclereturndocuments = vehicle_return_documents::where('bookingID', $ispection->id)->get();
        #vehicle return documents
        $vehiclereturnimages = vehicle_return_images::where('bookingID', $ispection->id)->get(); //$ispection->id

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $ispection->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $ispection->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $ispection->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $data['page_title'] = "Fleet Management";
        $data['page_description'] = " View Inspection Docs/images";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['vehicle'] = $vehicle;
        $data['vehiclecollectdocuments'] = $vehiclecollectdocuments;
        $data['vehiclecollectimage'] = $vehiclecollectimage;
        $data['vehiclereturndocuments'] = $vehiclereturndocuments;
        $data['vehiclereturnimages'] = $vehiclereturnimages;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['ispection'] = $ispection;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'View Booking Inspections Docs/images', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.ViewispectionDocs')->with($data);
    }
	public function bookingSearch()
    {
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $data['page_title'] = "Search";
        $data['page_description'] = "Bookings";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/bookings_search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Booking', 'active' => 1, 'is_module' => 0]
        ];

        $data['employees'] = $employees;
        $data['bookingStatus'] = $bookingStatus;
        $data['usageType'] = $usageType;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['divisionLevels'] = $divisionLevels;
        $data['vehicledetail'] = $vehicledetail;
        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Search Bookings';
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.search_bookings')->with($data);
    }
	public function bookingSearchResults(Request $request)
    {
		$bookingData = $request->all();
        unset($bookingData['_token']);
        $bookingFrom = $bookingTo = 0;
		$bookingDate = $bookingData['booking_date'];
		$driverID = $bookingData['driver_id'];
		$bookingStatus = $bookingData['booking_status'];
		$usageType = $bookingData['usage_type'];
		$modelID = $bookingData['model_id'];
		$makeID = $bookingData['make_id'];
		$vehicleType = $bookingData['vehicle_type'];
		$fleetNumber = $bookingData['fleet_number'];
		if (!empty($bookingDate))
		{
			$startExplode = explode('-', $bookingDate);
			$bookingFrom = strtotime($startExplode[0]);
			$bookingTo = strtotime($startExplode[1]);
		}
				
		$usageTypes = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');
        $bookingStatuses = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
		$bookings = DB::table('vehicle_booking')
		->select('vehicle_booking.*','hr_people.first_name as firstname', 'hr_people.surname as surname'
				, 'vehicle_make.name as vehicleMake', 'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType')
		->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
		->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
		->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
		->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
		->where(function ($query) use ($bookingFrom, $bookingTo) {
			if ($bookingFrom > 0 && $bookingTo  > 0) {
				$query->whereBetween('vehicle_booking.booking_date', [$bookingFrom, $bookingTo]);
			}
		})->where(function ($query) use ($driverID) {
			if (!empty($driverID)) {
				$query->where('vehicle_booking.driver_id', $driverID);
			}
		})
		->where(function ($query) use ($makeID) {
			if (!empty($makeID)) {
				$query->where('vehicle_booking.vehicle_make', $makeID);
			}
		})
		->where(function ($query) use ($modelID) {
			if (!empty($modelID)) {
				$query->where('vehicle_booking.vehicle_model', $modelID);
			}
		})
		->where(function ($query) use ($bookingStatus) {
			if (!empty($bookingStatus)) {
				$query->where('vehicle_booking.status', $bookingStatus);
			}
		})
		->where(function ($query) use ($usageType) {
			if (!empty($usageType)) {
				$query->where('vehicle_booking.usage_type', $usageType);
			}
		})
		->where(function ($query) use ($vehicleType) {
			if (!empty($vehicleType)) {
				$query->where('vehicle_booking.vehicle_type', $vehicleType);
			}
		})
		->where(function ($query) use ($fleetNumber) {
			if (!empty($fleetNumber)) {
				$query->where('vehicle_booking.fleet_number', 'ILIKE', "%$fleetNumber%");
			}
		})
		->orderBy('vehicle_booking.booking_date','desc')
		->get();

		$data['page_title'] = "Search";
        $data['page_description'] = "Bookings";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/bookings_search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Booking', 'active' => 1, 'is_module' => 0]
        ];
 
        $data['bookings'] = $bookings;
        $data['usageTypes'] = $usageTypes;
        $data['bookingStatuses'] = $bookingStatuses;
		
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Search Bookings';

        AuditReportsController::store('Fleet Management', 'Booking Search Results Page Accessed ', "Accessed by User", 0);
        return view('Vehicles.Create_request.booking_search_results')->with($data);
    }
}
