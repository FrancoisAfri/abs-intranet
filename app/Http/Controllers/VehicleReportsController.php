<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\CompanyIdentity;
use App\permits_licence;
use App\vehicle_maintenance;
use App\ContactCompany;
use App\Vehicle_managemnt;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle;
use App\vehicle_booking;
use App\vehiclemake;
use App\vehiclemodel;
use App\DivisionLevel;
use App\vehicle_fuel_log;
use App\fleet_licence_permit;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

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
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $licence = $permitlicence = fleet_licence_permit::orderBy('id', 'asc')->get();
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();


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

        $data['ContactCompany'] = $ContactCompany;
        $data['division_levels'] = $divisionLevels;
        $data['licence'] = $licence;
        $data['vehicledetail'] = $vehicledetail;
        $data['hrDetails'] = $hrDetails;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['licence'] = $licence;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.generalreport_search')->with($data);
    }

    public function generaldetails(Request $request)
    {

        $this->validate($request, [

        ]);
        // "report_type":"1","report_id":"1","vehicle_type":"","licence_type":"","driver_id":"","action_date":""}

        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicleID = $vehicleID = 0;
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $licenceType = $reportData['licence_type'];
        $vehicleID = !empty($reportData['vehicle_id']) ? $reportData['vehicle_id'] : 0;
        $driverID = $reportData['driver_id'];

        //  return $vehicleID;

//        if (!empty($vehicleID))
        if ($reportID == 1) { /// Booking log

            $actionDate = $request['action_date'];
            if (!empty($actionDate)) {
                $startExplode = explode('-', $actionDate);
                $actionFrom = strtotime($startExplode[0]);
                $actionTo = strtotime($startExplode[1]);
            }

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            // $vehiclebookings = DB::table('vehicle_details')
            // ->select('vehicle_details.*', 'vehicle_make.name as vehicleMake',
            //     'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
            //     'hr_people.first_name as firstname', 'hr_people.surname as surname')
            // ->leftJoin('hr_people', 'vehicle_details.driver_id', '=', 'hr_people.id')
            // ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            // ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            // ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            // ->orderBy('vehicle_details.id', 'desc')
            // //->where('vehicle_booking.UserID', $loggedInEmplID)
            // //->where('vehicle_booking.status', '!=', 13)
            // // ->where('vehicle_booking.status', '!=', 12)
            // ->get();

            //returnvehiclebookings;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            return view('Vehicles.Reports.vehiclebooking_results')->with($data);


        } elseif ($reportID == 2) { // fuel log


            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return array($value);

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            return view('Vehicles.Reports.vehiclefuel_results')->with($data);

        } elseif ($reportID == 3) { // Fines

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return array($value);

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

            $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');


            $data['vehicledetail'] = $vehicledetail;
            $data['fineType'] = $fineType;
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
            return view('Vehicles.Reports.vehiclefinelog_results')->with($data);

        } elseif ($reportID == 4) { // Services

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            return view('Vehicles.Reports.servicelog_results')->with($data);
        } elseif ($reportID == 5) { // Vehicle Incidents Details

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 6) { // Vehicle Details

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 7) { // Vehicle Contract

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 8) { // Expired Documents

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 9) { // External Diesel Log

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 10) { // Internal Diesel Log

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 11) { // Diesel Log

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        } elseif ($reportID == 13) { // Alerts Report

            foreach ($vehicleID as $key => $value) {
                //echo $value.",";
                //return $key;
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->whereIn('vehicle_details.id', array($value))
                    ->get();
            }

            //return $vehicledetail;

            if (!empty($vehicledetail))
                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->get();

            //$data['vehiclefuellog'] = $vehiclefuellog;
            $data['vehicledetail'] = $vehicledetail;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
            //return view('Vehicles.Reports.incident_results')->with($data);
        }


    }

    public function bookingReports(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $licenceType = $reportData['licence_type'];
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                    // $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // return $vehiclebookings;

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

        $vehicleArray = isset($reportData['vehicle_id']) ? intval($reportData['vehicle_id']) : 0;


        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        //$Destination = $request['destination'];
        //$Purpose = $request['purpose'];


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
                if (!empty($vehicleType))
                    $query->where('vehicle_type', $vehicleType);
            })
            ->where(function ($query) use ($driverID) {
                if (!empty($driverID))
                    $query->where('driver_id', $driverID);
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('collect_timestamp', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
                if (!empty($vehicleArray))
                    $query->whereIn('vehicle_id', [$vehicleArray]);
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

        $user = Auth::user()->load('person');
        $companyDetails = CompanyIdentity::systemSettings();
        //return $companyDetails;
        $companyname = $companyDetails['full_company_name'];
        $companylogo = $companyDetails['company_logo_url'];

        $data['company_name'] = $companyname;
        $logo = $companylogo;
        $data['company_logo'] = url('/') . Storage::disk('local')->url("logos/$logo");
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
        $licenceType = $reportData['licence_type'];
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();

        //return $fuelLog;

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

        $vehicleArray = isset($reportData['vehicle_id']) ? intval($reportData['vehicle_id']) : 0;


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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            //->orderBy('vehicle_id', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        //return $fuelLog;

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

    public function jobcard()
    {
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.index')->with($data);
    }


    public function vehicleFuelDetails(vehicle_detail $vehicleID)
    {
        $vehiclefuellog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'fleet_fillingstation.name as Staion', 'fuel_tanks.tank_name as tankName')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->orderBy('vehicle_fuel_log.id')
            ->where('vehicle_fuel_log.vehicleID', $vehicleID->id)
            ->get();

        //  return  $vehiclefuellog;


        $vehicledetail = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->where('vehicle_details.id', $vehicleID->id)
            ->first();

        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');
        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $data['vehiclefuellog'] = $vehiclefuellog;
        $data['vehicledetail'] = $vehicledetail;
        $data['fineType'] = $fineType;
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
        return view('Vehicles.Reports.fuellog_results')->with($data);
    }

    public function vehicleFineDetails(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $licenceType = $reportData['licence_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $Destination = $request['destination'];
        $Purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }


        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype',
                'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_managemnet.name as vehicletypes', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fines.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();


        // $total =  DB::table('vehicle_details')->where('id', $vehicleID->id)->get('amount');
        $total = $vehiclefines->sum('amount');
        $totalamount_paid = $vehiclefines->sum('amount_paid');

        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $data['total'] = $total;
        $data['totalamount_paid'] = $totalamount_paid;
        //  $data['vehicledetail'] = $vehicledetail;
        $data['fineType'] = $fineType;
        $data['status'] = $status;
        $data['vehiclefines'] = $vehiclefines;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.finelog_results')->with($data);
    }

     public function fineReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
        $actionFrom = $actionTo = 0;

        $vehicleArray = isset($reportData['vehicle_id']) ? intval($reportData['vehicle_id']) : 0;

        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        //$Destination = $request['destination'];
        //$Purpose = $request['purpose'];


        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel', 'vehicle_details.vehicle_type as vehicletype',
                'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel', 'vehicle_managemnet.name as vehicletypes', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_fines.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();

        $serviceDetails = $serviceDetail->unique('id');;


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
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.Servicedetailslog_results')->with($data);
    }

    public function ServiceReportPrint(Request $request){
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $licenceType = $reportData['licence_type'];
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();

        $serviceDetails = $serviceDetail->unique('id');;


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
        $licenceType = $reportData['licence_type'];
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();

        $vehicleincidents = $vehicleincident->unique('id');

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');

        // return $vehicleincidents;

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

    public function IncidentReportPrint(Request $request){
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
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
                    $query->where('vehicle_type', $vehicleType);
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
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
          //  ->where('vehicle_incidents.id','desc')
            ->get();

        $vehicleincidents = $vehicleincident->unique('id');

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');

        // return $vehicleincidents;

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
        $licenceType = $reportData['licence_type'];
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
                    $query->where('vehicle_type', $vehicleType);
                }
            })
//            ->where(function ($query) use ($driverID) {
//                if (!empty($driverID)) {
//                    $query->where('driver', $driverID);
//                }
//            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentDate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('id', '=', $vehicleArray[$i]);
                }
            })
            ->get();
			
			//return $vehicledetails;

        $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');

        // return $vehicledetails;

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
     public function DetailsReportPrint(Request $request){
         $reportData = $request->all();
         unset($reportData['_token']);

         $actionFrom = $actionTo = 0;
         $vehicle = '';
         $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
         $reportID = $reportData['report_id'];
         $reportType = $reportData['report_type'];
         $vehicleType = $reportData['vehicle_type'];
         $licenceType = $reportData['licence_type'];
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
                     $query->where('vehicle_type', $vehicleType);
                 }
             })
//            ->where(function ($query) use ($driverID) {
//                if (!empty($driverID)) {
//                    $query->where('driver', $driverID);
//                }
//            })
             ->where(function ($query) use ($actionFrom, $actionTo) {
                 if ($actionFrom > 0 && $actionTo > 0) {
                     $query->whereBetween('currentDate', [$actionFrom, $actionTo]);
                 }
             })
             ->Where(function ($query) use ($vehicleArray) {
                 for ($i = 0; $i < count($vehicleArray); $i++) {
                     $vehicle = $vehicleArray[$i] . ',';
                     $query->whereOr('id', '=', $vehicleArray[$i]);
                 }
             })
             ->get();

         //return $vehicledetails;

         $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');

         // return $vehicledetails;

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

     public  function vehiclesExpiry_documents(Request $request){
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


        $vehicleDocumets = DB::table('vehicle_documets')
            ->select('vehicle_documets.*','vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel','vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel','vehicle_details.vehicle_registration as vehicle_registration')
            ->leftJoin('vehicle_details', 'vehicle_documets.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
            // ->where(function ($query) use ($driverID) {
            //     if (!empty($driverID)) {
            //         $query->where('driver', $driverID);
            //     }
            // })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
          //  ->where('vehicle_incidents.id','desc')
            ->get();
           // return $vehicleDocumets;


         // permit licences

            $VehicleLicences = DB::table('permits_licence')
            ->select('permits_licence.*','vehicle_details.vehicle_make as vehiclemake', 'vehicle_details.vehicle_model as vehiclemodel','vehicle_details.vehicle_type as vehicletype', 'vehicle_make.name as VehicleMake', 'vehicle_model.name as VehicleModel','vehicle_details.vehicle_registration as vehicle_registration')
           // ->leftJoin('permits_licence', 'vehicle_documets.vehicleID', '=', 'permits_licence.vehicleID')
            ->leftJoin('vehicle_details', 'permits_licence.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('vehicle_make', 'vehicle_details.id', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.id', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.id', '=', 'vehicle_managemnet.id')
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
            // ->where(function ($query) use ($driverID) {
            //     if (!empty($driverID)) {
            //         $query->where('driver', $driverID);
            //     }
            // })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('currentdate', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
                for ($i = 0; $i < count($vehicleArray); $i++) {
                    $vehicle = $vehicleArray[$i] . ',';
                    $query->whereOr('vehicle_id', '=', $vehicleArray[$i]);
                }
            })
            ->get();

       // return $VehicleLicences;


         

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


}
