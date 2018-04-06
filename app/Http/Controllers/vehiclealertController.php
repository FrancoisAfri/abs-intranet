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

class vehiclealertController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
       // alertsIndex
        
       // $vehiclebooking = vehicle_detail::OrderBy('id', 'asc')->get();
         $status = array(1 => 'Vehicle Minor Incidents Outstanding', 2 => 'Vehicle Major Incidents Outstanding', 3 => 'Vehicle Critical Incidents Outstanding');
         
         $keys = array(1 => 'Vehicle Key In Use', 2 => 'Vehicle Key Reallocated', 3 => 'Vehicle Key Lost', 4 => 'Vehicle Key In Safe');
        
        $vehiclebooking = DB::table('vehicle_details')
                ->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date ', 'vehicle_booking.return_datetime as return_date ', 
                        'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 
                        'division_level_fives.name as company', 'division_level_fours.name as Department' ,'vehicle_incidents.severity as Severity',
                        'keytracking.key_status as lost','permits_licence.exp_date as licenceExpiredate', 'permits_licence.permit_licence as permitlicence_name',
                        'permits_licence.permits_licence_no as permitslicenceNumber' ,'fleet_licence_permit.name as permitlicenceName')
                ->leftJoin('permits_licence', 'vehicle_details.id', '=', 'permits_licence.vehicleID')
                ->leftJoin('fleet_licence_permit', 'permits_licence.permit_licence', '=', 'fleet_licence_permit.id')
                ->leftJoin('keytracking', 'vehicle_details.id', '=', 'keytracking.vehicle_id')
                ->leftJoin('vehicle_incidents', 'vehicle_details.id', '=', 'vehicle_incidents.vehicleID')
                ->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
                ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
                ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
                //->where('vehicle_details.booking_status', '!=', 1)
               // ->whereNotIn('vehicle_incidents.severity', [ 2, 3])//check if the booking is not approved
                //->orWhereNull('vehicle_incidents.severity') // allow nulls
                ->orderBy('vehicle_details.id', 'asc')
                //->unique('vehicle_details.id')
                ->get();
        
      //   $vehiclebooking = $vehiclebookings->unique('id');
        
      // return $vehiclebooking;
        
        
        
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Alerts ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Alerts ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehiclebooking'] = $vehiclebooking;
        $data['keys'] = $keys;
        $data['status'] = $status;
//        $data['licence'] = $licence;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Alerts';

        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Alerts.alertsIndex')->with($data);
    }
}
