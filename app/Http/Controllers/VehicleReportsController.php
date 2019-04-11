<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\DivisionLevel;
use App\fleet_licence_permit;
use App\FleetType;
use App\HRPerson;
use App\vehicle_fire_extinguishers;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\permits_licence;
use App\Users;
use App\vehicle;
use App\vehicle_booking;
use App\vehicle_maintenance;
use App\Vehicle_managemnt;
use App\vehiclemake;
use App\vehiclemodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();
        $licence = permits_licence::orderby('status', 1)->get();

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.index')->with($data);
    }

    public function general()
    {
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('name', 'asc')->get();
        $vehiclemakes = vehiclemake::orderBy('name', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $licence = $permitlicence = fleet_licence_permit::orderBy('id', 'asc')->get();
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = FleetType::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->get();

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Reports ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['ContactCompany'] = $ContactCompany;
        $data['division_levels'] = $divisionLevels;
        $data['licence'] = $licence;
        $data['vehicledetail'] = $vehicledetail;
        $data['vehiclemakes'] = $vehiclemakes;
        $data['hrDetails'] = $hrDetails;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['licence'] = $licence;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.generalreport_search')->with($data);
    }

    public function bookingReports(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        // $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        //$licenceType = $reportData['licence_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $vehiclebookings = vehicle_booking::select('vehicle_booking.*', 'vehicle_make.name as vehicle_make',
            'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
            'vehicle_details.vehicle_registration as v_registration',
            'hr_people.first_name as driver_name',
            'hr_people.surname as driver_surname',
            'hr.first_name as apr_firstname',
            'hr.surname as apr_surname')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_booking.approver3_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'vehicle_booking.driver_id', '=', 'hr.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_booking.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver_id', $driverID);
                }
            })
            ->where(function ($query) use ($Destination) {
                if (!empty($Destination)) {
                    $query->where('destination', 'ILIKE', "%$Destination%");
                }
            })
            ->where(function ($query) use ($Purpose) {
                if (!empty($Purpose)) {
                    $query->where('purpose', 'ILIKE', "%$Purpose%");
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('booking_date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();


        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.bookinglog_results')->with($data);
    }

    public function bookingReportsPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;

        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];


        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $vehiclebookings = vehicle_booking::select('vehicle_booking.*', 'vehicle_make.name as vehicle_make',
            'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
            'vehicle_details.vehicle_registration as v_registration',
            'hr_people.first_name as driver_name',
            'hr_people.surname as driver_surname',
            'hr.first_name as apr_firstname',
            'hr.surname as apr_surname')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('vehicle_details', 'vehicle_booking.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_booking.approver3_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'vehicle_booking.driver_id', '=', 'hr.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_booking.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver_id', $driverID);
                }
            })
            ->where(function ($query) use ($Destination) {
                if (!empty($Destination)) {
                    $query->where('destination', 'ILIKE', "%$Destination%");
                }
            })
            ->where(function ($query) use ($Purpose) {
                if (!empty($Purpose)) {
                    $query->where('purpose', 'ILIKE', "%$Purpose%");
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('booking_date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $data['vehiclebookings'] = $vehiclebookings;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.booking_report_print')->with($data);
    }

    public function fuelReports(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $fuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Status', 'vehicle_fuel_log.id as fuelLogID', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_managemnet.name as vehicletypes', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }

        $data['fuelLog'] = $fuelLog;
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuellog_results')->with($data);
    }

    public function fuelReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;

        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));

        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $fuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Status', 'vehicle_fuel_log.id as fuelLogID', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_managemnet.name as vehicletypes', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('id', 'desc')
            ->get();

        $data['fuelLog'] = $fuelLog;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuel_report_print')->with($data);

    }


    public function vehicleFineDetails(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }


        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype',
                'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_types', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fines.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $total = $vehiclefines->sum('amount');
        $totalamount_paid = $vehiclefines->sum('amount_paid');
		
        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }
		
        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $data['total'] = $total;
        $data['totalamount_paid'] = $totalamount_paid;
        $data['fineType'] = $fineType;
        $data['status'] = $status;
        $data['vehiclefines'] = $vehiclefines;
        $data['vehicle_id'] = rtrim($vehicle, ",");
        //$data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Fines Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet ManagementFine Report', "Accessed By User", 0);
        return view('Vehicles.Reports.finelog_results')->with($data);
    }

    public function fineReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;

        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype',
                'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_types', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fines.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('id', 'desc')
            ->get();


        $total = $vehiclefines->sum('amount');
        $totalamount_paid = $vehiclefines->sum('amount_paid');

        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $data['total'] = $total;
        $data['totalamount_paid'] = $totalamount_paid;
        $data['fineType'] = $fineType;
        $data['status'] = $status;
        $data['vehiclefines'] = $vehiclefines;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Fines Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fine_report_print')->with($data);

    }

    public function vehicleServiceDetails(Request $request)
    {

        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        //$licenceType = $reportData['licence_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $serviceDetail = DB::table('vehicle_serviceDetails')
            ->select('vehicle_serviceDetails.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel',
                'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel',
                'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_serviceDetails.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $serviceDetails = $serviceDetail->unique('id');;
		for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }

        $totalamount_paid = $serviceDetails->sum('total_cost');

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['serviceDetails'] = $serviceDetails;
        $data['totalamount_paid'] = $totalamount_paid;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.Servicedetailslog_results')->with($data);
    }

    public function ServiceReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }


        $serviceDetail = DB::table('vehicle_serviceDetails')
            ->select('vehicle_serviceDetails.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel',
                'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel',
                'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_serviceDetails.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $serviceDetails = $serviceDetail->unique('id');;
		for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }
        $totalamount_paid = $serviceDetails->sum('total_cost');


        $data['serviceDetails'] = $serviceDetails;
        $data['totalamount_paid'] = $totalamount_paid;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.service_report_print')->with($data);
    }

    public function vehicleIncidentsDetails(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $vehicleincident = DB::table('vehicle_incidents')
            ->select('vehicle_incidents.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel',
                'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel',
                'vehicle_details.vehicle_registration as vehicle_registration', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'incident_type.name as IncidentType')
            ->leftJoin('vehicle_details', 'vehicle_incidents.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_incidents.reported_by', '=', 'hr_people.id')
            ->leftJoin('incident_type', 'vehicle_incidents.incident_type', '=', 'incident_type.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $vehicleincidents = $vehicleincident->unique('id');

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['status'] = $status;
        $data['severity'] = $severity;
        $data['vehicleincidents'] = $vehicleincidents;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.Incidentlog_results')->with($data);
    }

    public function IncidentReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        //$reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        // $licenceType = $reportData['licence_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        // return $serviceDetails;

        $vehicleincident = DB::table('vehicle_incidents')
            ->select('vehicle_incidents.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel',
                'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel',
                'vehicle_details.vehicle_registration as vehicle_registration', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'incident_type.name as IncidentType')
            ->leftJoin('vehicle_details', 'vehicle_incidents.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_incidents.reported_by', '=', 'hr_people.id')
            ->leftJoin('incident_type', 'vehicle_incidents.incident_type', '=', 'incident_type.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $vehicleincidents = $vehicleincident->unique('id');

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');

        $data['status'] = $status;
        $data['severity'] = $severity;
        $data['vehicleincidents'] = $vehicleincidents;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.incident_report_print')->with($data);
    }

    public function vehiclesDetails(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $vehicleMake = $reportData['vehicle_make'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $vehicledetails = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($vehicleMake) {
                if (!empty($vehicleMake)) {
                    $query->where('vehicle_details.vehicle_make', $vehicleMake);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentDate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['vehicledetails'] = $vehicledetails;
        $data['status'] = $status;

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicledetailslog_results')->with($data);
    }

    public function DetailsReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $vehicledetails = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentDate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->get();

        $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');

        $data['status'] = $status;
        $data['vehicledetails'] = $vehicledetails;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicledetails_report_print')->with($data);
    }

    public function vehiclesExpiry_documents(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleType = $reportData['vehicle_type'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $currentTime = time();
        $vehicleDocumets = DB::table('vehicle_documets')
            ->select('vehicle_documets.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake'
				, 'vehicle_model.name as VehicleModel', 'vehicle_details.vehicle_registration as vehicle_registration',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_details', 'vehicle_documets.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_documets.currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('vehicle_documets.exp_date', '<', $currentTime)
            ->orderby('vehicle_documets.id', 'desc')
            ->get();

        $VehicleLicences = DB::table('permits_licence')
            ->select('permits_licence.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_details.vehicle_registration as vehicle_registration',
                'division_level_fives.name as company', 'division_level_fours.name as Department', 'contact_companies.name as supplier')
            ->leftJoin('contact_companies', 'permits_licence.Supplier', '=', 'contact_companies.id')
            ->leftJoin('vehicle_details', 'permits_licence.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('permits_licence.date_captured', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
                if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('permits_licence.exp_date', '<', $currentTime)
            ->orderby('permits_licence.id', 'desc')
            ->get();
			
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['report_type'] = $reportType;
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['vehicleDocumets'] = $vehicleDocumets;
        $data['VehicleLicences'] = $VehicleLicences;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicle_expdocs_log')->with($data);
    }

    public function ExpdocsReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $currentTime = time();
        $vehicleDocumets = DB::table('vehicle_documets')
            ->select('vehicle_documets.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_details.vehicle_registration as vehicle_registration',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_details', 'vehicle_documets.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('vehicle_documets.exp_date', '<', $currentTime)
            ->orderby('vehicle_documets.id', 'desc')
            ->get();


        $data['vehicleDocumets'] = $vehicleDocumets;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicleexpdocs_report_print')->with($data);
    }

    public function ExpLicencesReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $currentTime = time();
        $VehicleLicences = DB::table('permits_licence')
            ->select('permits_licence.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_details.vehicle_registration as vehicle_registration',
                'division_level_fives.name as company', 'division_level_fours.name as Department', 'contact_companies.name as supplier')
            ->leftJoin('contact_companies', 'permits_licence.Supplier', '=', 'contact_companies.id')
            ->leftJoin('vehicle_details', 'permits_licence.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('permits_licence.exp_date', '<', $currentTime)
            ->orderby('permits_licence.id', 'desc')
            ->get();
        $data['VehicleLicences'] = $VehicleLicences;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicleexplicences_report_print')->with($data);
    }

    public function vehiclesExternaldiesel(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $externalFuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype'
                , 'fleet_fillingstation.name as Supplier', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel'
                , 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('vehicle_fuel_log.driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_fuel_log.date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('vehicle_fuel_log.tank_and_other', '=', 2)
            ->orderby('vehicle_fuel_log.service_station', 'desc')
            ->get();
		
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $totalKms = $externalFuelLog->sum('Odometer_reading');
        $totalHours = $externalFuelLog->sum('Hoursreading');
        $totalLitres = $externalFuelLog->sum('litres_new');
        $totalCost = $externalFuelLog->sum('total_cost');
        if (!empty($totalKms) && !empty($totalLitres)) $totalAvgKms = $totalKms / $totalLitres;
        else $totalAvgKms = 0;
        if (!empty($totalHours) && !empty($totalLitres)) $totAlavgHrs = $totalHours / $totalLitres;
        else $totAlavgHrs = 0;
        if (!empty($totalCost) && !empty($totalLitres)) $totalAvgCost = $totalCost / $totalLitres;
        else $totalAvgCost = 0;

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['externalFuelLog'] = $externalFuelLog;
        $data['totalKms'] = $totalKms;
        $data['totalHours'] = $totalHours;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['totalAvgKms'] = $totalAvgKms;
        $data['totAlavgHrs'] = $totAlavgHrs;
        $data['totalAvgCost'] = $totalAvgCost;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuelexternallog_results')->with($data);
    }

    public function ExternalOilReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $externalFuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype'
                , 'fleet_fillingstation.name as Supplier', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel'
                , 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('vehicle_fuel_log.driver', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_fuel_log.date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('vehicle_fuel_log.tank_and_other', '=', 2)
            ->orderby('vehicle_fuel_log.service_station', 'desc')
            ->get();
        $totalKms = $externalFuelLog->sum('Odometer_reading');
        $totalHours = $externalFuelLog->sum('Hoursreading');
        $totalLitres = $externalFuelLog->sum('litres_new');
        $totalCost = $externalFuelLog->sum('total_cost');
        if (!empty($totalKms) && !empty($totalLitres)) $totalAvgKms = $totalKms / $totalLitres;
        else $totalAvgKms = 0;
        if (!empty($totalHours) && !empty($totalLitres)) $totAlavgHrs = $totalHours / $totalLitres;
        else $totAlavgHrs = 0;
        if (!empty($totalCost) && !empty($totalLitres)) $totalAvgCost = $totalCost / $totalLitres;
        else $totalAvgCost = 0;

        $data['externalFuelLog'] = $externalFuelLog;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
        $data['totalKms'] = $totalKms;
        $data['totalHours'] = $totalHours;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['totalAvgKms'] = $totalAvgKms;
        $data['totAlavgHrs'] = $totAlavgHrs;
        $data['totalAvgCost'] = $totalAvgCost;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicleextelOil_report_print')->with($data);
    }

    public function vehiclesInternaldiesel(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $fuelTankTopUp = DB::table('fuel_tank_topUp')
            ->select('fuel_tanks.*', 'fuel_tank_topUp.*', 'vehicle_details.fleet_number as fleet_number'
                , 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype'
                , 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel'
                , 'vehicle_details.vehicle_registration as vehicle_registration', 'contact_companies.name as supplier')
            ->leftJoin('fuel_tanks', 'fuel_tank_topUp.tank_id', '=', 'fuel_tanks.id')
            ->leftJoin('contact_companies', 'fuel_tank_topUp.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_details', 'fuel_tank_topUp.tank_id', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID)) {
                    $query->where('fuel_tank_topUp.received_by', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where('fuel_tank_topUp.status', '=', 1)
            ->orderby('fuel_tanks.id', 'desc')
            ->get();
		
        $data['fuelTankTopUp'] = $fuelTankTopUp;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Internal Fuel Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuelIntenallog_results')->with($data);
    }

    public function fleetCardReport(Request $request)
    {
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $actionFrom = $actionTo = 0;
        $cardtype = $request['card_type_id'];
        $company = $request['company_id'];
        $holder = $vehicleData['driver_id'];
        $actionDate = $request['action_date'];
        $vehicle = '';
        $vehicleArray = isset($vehicleData['vehicle_id']) ? $vehicleData['vehicle_id'] : array();
        $vehicleType = $vehicleData['vehicle_type'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $status = array(1 => ' Active', 2 => ' InActive');

        $fleetcards = DB::table('vehicle_fleet_cards')
            ->select('vehicle_fleet_cards.*', 'contact_companies.name as Vehicle_Owner'
                , 'hr_people.first_name as first_name', 'hr_people.surname as surname'
                , 'fleet_type.name as type_name', 'vehicle_details.fleet_number as fleetnumber')
            ->leftJoin('contact_companies', 'vehicle_fleet_cards.company_id', '=', 'contact_companies.id')
            ->leftJoin('hr_people', 'vehicle_fleet_cards.holder_id', '=', 'hr_people.id')
            ->leftJoin('fleet_type', 'vehicle_fleet_cards.card_type_id', '=', 'fleet_type.id')
            ->leftJoin('vehicle_details', 'vehicle_fleet_cards.fleet_number', '=', 'vehicle_details.id')
            ->where(function ($query) use ($cardtype) {
                if (!empty($cardtype)) {
                    $query->where('vehicle_fleet_cards.card_type_id', $cardtype);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where(function ($query) use ($company) {
                if (!empty($company)) {
                    $query->where('vehicle_fleet_cards.company_id', $company);
                }
            })
            ->where(function ($query) use ($holder) {
                if (!empty($holder)) {
                    $query->where('vehicle_fleet_cards.holder_id', $holder);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('issued_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('vehicle_fleet_cards.fleet_number', 'asc')
            ->get();

		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $data['status'] = $status;
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $holder;
        $data['action_date'] = $actionDate;
        $data['company_id'] = $company;
        $data['card_type_id'] = $cardtype;
        $data['fleetcards'] = $fleetcards;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Card Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Card Report', "Accessed By User", 0);
        return view('Vehicles.Reports.fleet_card_report')->with($data);
    }

    public function fleetCardReportPrint(Request $request)
    {

        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $actionFrom = $actionTo = 0;
        $cardtype = $request['card_type_id'];
        $company = $request['company_id'];
        $holder = $vehicleData['driver_id'];
        $actionDate = $request['action_date'];
        $vehicle = isset($vehicleData['vehicle_id']) ? $vehicleData['vehicle_id'] : array();
        $vehicleArray = (explode(",", $vehicle));
        $vehicleType = $vehicleData['vehicle_type'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $status = array(1 => ' Active', 2 => ' InActive');

        $fleetcards = DB::table('vehicle_fleet_cards')
            ->select('vehicle_fleet_cards.*', 'contact_companies.name as Vehicle_Owner'
                , 'hr_people.first_name as first_name', 'hr_people.surname as surname'
                , 'fleet_type.name as type_name', 'vehicle_details.fleet_number as fleetnumber')
            ->leftJoin('contact_companies', 'vehicle_fleet_cards.company_id', '=', 'contact_companies.id')
            ->leftJoin('hr_people', 'vehicle_fleet_cards.holder_id', '=', 'hr_people.id')
            ->leftJoin('fleet_type', 'vehicle_fleet_cards.card_type_id', '=', 'fleet_type.id')
            ->leftJoin('vehicle_details', 'vehicle_fleet_cards.fleet_number', '=', 'vehicle_details.id')
            ->where(function ($query) use ($cardtype) {
                if (!empty($cardtype)) {
                    $query->where('vehicle_fleet_cards.card_type_id', $cardtype);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleType);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->where(function ($query) use ($company) {
                if (!empty($company)) {
                    $query->where('vehicle_fleet_cards.company_id', $company);
                }
            })
            ->where(function ($query) use ($holder) {
                if (!empty($holder)) {
                    $query->where('vehicle_fleet_cards.holder_id', $holder);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('issued_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('vehicle_fleet_cards.fleet_number', 'asc')
            ->get();

        $status = array(1 => ' Active', 2 => ' InActive');
        $data['fleetcards'] = $fleetcards;
        $data['status'] = $status;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Cards Report Printed', "Accessed By User", 0);
        return view('Vehicles.Reports.fleet_cards_report_print')->with($data);
    }
	
	public function fireExtinguishersReport(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $actionDate = $request['action_date'];
		$statusArray= array(1 => 'Active', 2 => ' Allocate', 3 => 'In Use', 4 => 'Empty', 5=> 'Evacate', 6=> 'In Storage', 7=> 'Discarded', 8=> 'Rental' , 9=> 'Sold');
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $fireExtinguishers = vehicle_fire_extinguishers::select('vehicle_fire_extinguisher.*'
            ,'vehicle_details.fleet_number as fleet_number',
            'hr_people.first_name as capt_name','hr_people.surname as capt_surname'
			,'contact_companies.name as com_name')
            ->leftJoin('vehicle_details', 'vehicle_fire_extinguisher.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_fire_extinguisher.capturer_id', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'vehicle_fire_extinguisher.supplier_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date_purchased', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();

//return $fireExtinguishers;
        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['action_date'] = $actionDate;
        $data['fireExtinguishers'] = $fireExtinguishers;
        $data['statusArray'] = $statusArray;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fire Extinguisher Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Fleet Management', 'Report Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fire_results')->with($data);
    }

    public function fireExtinguishersReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;

        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : '';
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
		else $vehicleArray = '';
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $fireExtinguishers = vehicle_fire_extinguishers::select('vehicle_fire_extinguisher.*'
            ,'vehicle_details.fleet_number as fleet_number',
            'hr_people.first_name as capt_name','hr_people.surname as capt_surname'
			,'contact_companies.name as com_name')
            ->leftJoin('vehicle_details', 'vehicle_fire_extinguisher.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_fire_extinguisher.capturer_id', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'vehicle_fire_extinguisher.supplier_id', '=', 'contact_companies.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date_purchased', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicle_id', $vehicleArray);
				}
            })
            ->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();
		$statusArray= array(1 => 'Active', 2 => ' Allocate', 3 => 'In Use', 4 => 'Empty', 5=> 'Evacate', 6=> 'In Storage', 7=> 'Discarded', 8=> 'Rental' , 9=> 'Sold');
        
        $data['fireExtinguishers'] = $fireExtinguishers;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fire Extinguisher Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['statusArray'] = $statusArray;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fire_report_print')->with($data);
    }
}