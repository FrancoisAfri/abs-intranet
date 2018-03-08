<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\vehicle_maintenance;
use App\Vehicle_managemnt;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle;
use App\vehiclemake;
use App\vehiclemodel;
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

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
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

        $vehicledetail = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->get();


        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['licence'] = $licence;
        $data['vehicledetail'] = $vehicledetail;
        $data['hrDetails'] = $hrDetails;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['licence'] = $licence;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
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
        $vehicleID = $reportData['vehicle_id'];
        $driverID = $reportData['driver_id'];

//        if (!empty($vehicleID))
        if ($reportID == 1) { ///
            foreach ($vehicleID as $vehID) {

                $actionDate = $request['action_date'];
                if (!empty($actionDate)) {
                    $startExplode = explode('-', $actionDate);
                    $actionFrom = strtotime($startExplode[0]);
                    $actionTo = strtotime($startExplode[1]);
                }

                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->where('vehicle_details.id', $vehID)
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

                AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
                return view('Vehicles.Reports.vehiclebooking_results')->with($data);
            }

        } elseif ($reportID == 2) { // fuel log
            foreach ($vehicleID as $vehID) {


                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->where('vehicle_details.id', $vehID)
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

                AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
                return view('Vehicles.Reports.vehiclefuel_results')->with($data);
            }
        } elseif ($reportID == 3) { // Fines
            foreach ($vehicleID as $vehID) {

                $vehicledetail = DB::table('vehicle_details')
                    ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                        'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                    ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                    ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                    ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                    ->orderBy('vehicle_details.id', 'desc')
                    ->where('vehicle_details.id', $vehID)
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

                AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
                return view('Vehicles.Reports.vehiclefinelog_results')->with($data);
            }
        } elseif ($reportID == 4) { // Fines
            foreach ($vehicleID as $vehID) {

                //  $Fines

                //$data['vehiclefuellog'] = $vehiclefuellog;
                $data['page_title'] = " Fleet Management ";
                $data['page_description'] = "Fleet Cards Report ";
                $data['breadcrumb'] = [
                    ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                    ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
                ];

                $data['active_mod'] = 'Fleet Management';
                $data['active_rib'] = 'Reports';

                AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
                return view('Vehicles.Reports.fuellog_results')->with($data);
            }
        }

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

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.index')->with($data);
    }

    public function vehicleBookingDetails(vehicle_detail $vehicleID)
    {

        $vehicle_booking = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.vehicle_id', $vehicleID->id)
            ->get();



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

        $data['vehicle_booking'] = $vehicle_booking;
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

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.bookinglog_results')->with($data);
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

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuellog_results')->with($data);
    }

    public function vehicleFineDetails(vehicle_detail $vehicleID)
    {

        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->orderBy('vehicle_fines.id')
            ->where('vehicleID', $vehicleID->id)
            ->get();

        $vehicledetail = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->where('vehicle_details.id', $vehicleID->id)
            ->first();

        //return $vehiclefines;

        // $total =  DB::table('vehicle_details')->where('id', $vehicleID->id)->get('amount');
        $total = $vehiclefines->sum('amount');
        $totalamount_paid = $vehiclefines->sum('amount_paid');

        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $data['total'] = $total;
        $data['totalamount_paid'] = $totalamount_paid;
        $data['vehicledetail'] = $vehicledetail;
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

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.finelog_results')->with($data);
    }
}
