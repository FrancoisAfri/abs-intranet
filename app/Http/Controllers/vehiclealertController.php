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
use Excel;

class vehiclealertController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        $status = array(1 => 'Vehicle Minor Incidents Outstanding', 2 => 'Vehicle Major Incidents Outstanding', 3 => 'Vehicle Critical Incidents Outstanding');

        $severityArray = array(1 => 'Minor', 2 => 'Major', 3 => 'Critical');
		$configuration = DB::table('vehicle_configuration')->get()->first();
		$alertDays = !empty($configuration->alert_days) ? $configuration->alert_days : 0;
		$todayDate = strtotime(date('Y-m-d'));
		$thirthyDate =  mktime(0, 0, 0, date('m'), date('d')-30, date('Y'));
        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
        
        $user = Auth::user()->load('person');
        $managerID = $user->person->manager_id;
       
        $manager = !empty($managerID) ? $managerID : $user->id;

		$collectionOverdueAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date'
			,'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model',
			'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 
			'division_level_fours.name as department', 'vehicle_booking.status as BookingStatus'
			, 'vehicle_details.booking_status as vehicle_status')
			->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_details.booking_status', 1)
			->whereNull('vehicle_booking.collector_id')
			->where('vehicle_booking.require_datetime', '<', $todayDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('vehicle_details.id', 'asc')
			->get();

		$returnOverdueAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*', 'vehicle_booking.return_datetime as return_date'
			,'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model',
			'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 
			'division_level_fours.name as department', 'vehicle_booking.status as BookingStatus'
			, 'vehicle_details.booking_status as vehicle_status')
			->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_details.booking_status', 1)
			->whereNull('vehicle_booking.returner_id')
			->whereNotNull('vehicle_booking.collector_id')
			->where('vehicle_booking.return_datetime', '<', $todayDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('vehicle_details.id', 'asc')
			->get();

		$incidentsAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					, 'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					, 'division_level_fours.name as Department', 'vehicle_incidents.severity as incidents_severity'
					, 'incident_type.name as incident_type_name')
			->leftJoin('vehicle_incidents', 'vehicle_details.id', '=', 'vehicle_incidents.vehicleID')
			->leftJoin('incident_type', 'vehicle_incidents.incident_type', '=', 'incident_type.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_incidents.vehicle_fixed', 2)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('id', 'asc')
			->get();

		$servicesAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_serviceDetails.nxt_service_date')
			->leftJoin('vehicle_serviceDetails', 'vehicle_details.id', '=', 'vehicle_serviceDetails.vehicleID')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_serviceDetails.nxt_service_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('id', 'asc')
			->get();

		$warantyAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_warranties.exp_date')
			->leftJoin('vehicle_warranties', 'vehicle_details.id', '=', 'vehicle_warranties.vehicleID')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_warranties.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();

		$expiredPermitAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'permits_licence.exp_date'
					, 'fleet_licence_permit.name as license_name')
			->leftJoin('permits_licence', 'vehicle_details.id', '=', 'permits_licence.vehicleID')
			->leftJoin('fleet_licence_permit', 'permits_licence.permit_licence', '=', 'fleet_licence_permit.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('permits_licence.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();

		$expiredDocAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_documets.exp_date'
					, 'fleet_documentType.name as type_name')
			->leftJoin('vehicle_documets', 'vehicle_details.id', '=', 'vehicle_documets.vehicleID')
			->leftJoin('fleet_documentType', 'vehicle_documets.type', '=', 'fleet_documentType.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_documets.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();

        $data['page_title'] = " Fleet Management "; 
        $data['page_description'] = "Alerts";
        $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_alerts', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Fleet Alerts ', 'active' => 1, 'is_module' => 0]
        ];

        $data['collectionOverdueAlerts'] = $collectionOverdueAlerts;
        $data['returnOverdueAlerts'] = $returnOverdueAlerts;
        $data['incidentsAlerts'] = $incidentsAlerts;
        $data['servicesAlerts'] = $servicesAlerts;
        $data['warantyAlerts'] = $servicesAlerts;
        $data['expiredPermitAlerts'] = $expiredPermitAlerts;
        $data['expiredDocAlerts'] = $expiredDocAlerts;
        $data['bookingStatus'] = $bookingStatus;
        $data['severityArray'] = $severityArray;
        $data['status'] = $status;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Alerts';

        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Alerts.alertsIndex')->with($data);
    }
	
	public function printPdfAlerts() {

        $status = array(1 => 'Vehicle Minor Incidents Outstanding', 2 => 'Vehicle Major Incidents Outstanding', 3 => 'Vehicle Critical Incidents Outstanding');

        $severityArray = array(1 => 'Minor', 2 => 'Major', 3 => 'Critical');
		$configuration = DB::table('vehicle_configuration')->get()->first();
		$alertDays = !empty($configuration->alert_days) ? $configuration->alert_days : 0;
		$todayDate = strtotime(date('Y-m-d'));
		$thirthyDate =  mktime(0, 0, 0, date('m'), date('d')-30, date('Y'));
        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
        
        $user = Auth::user()->load('person');
        $managerID = $user->person->manager_id;
       
        $manager = !empty($managerID) ? $managerID : $user->id;
		
		//Collection Overdue Alerts
		$collectionOverdueAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date'
			,'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model',
			'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 
			'division_level_fours.name as department', 'vehicle_booking.status as BookingStatus'
			, 'vehicle_details.booking_status as vehicle_status')
			->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_details.booking_status', 1)
			->whereNull('vehicle_booking.collector_id')
			->where('vehicle_booking.require_datetime', '<', $todayDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('vehicle_details.id', 'asc')
			->get();
		
		//Return Overdue Alerts
		$returnOverdueAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*', 'vehicle_booking.return_datetime as return_date'
			,'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model',
			'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company', 
			'division_level_fours.name as department', 'vehicle_booking.status as BookingStatus'
			, 'vehicle_details.booking_status as vehicle_status')
			->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_details.booking_status', 1)
			->whereNull('vehicle_booking.returner_id')
			->whereNotNull('vehicle_booking.collector_id')
			->where('vehicle_booking.return_datetime', '<', $todayDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('vehicle_details.id', 'asc')
			->get();
			
		//Incidents Overdue Alerts
		$incidentsAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					, 'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					, 'division_level_fours.name as Department', 'vehicle_incidents.severity as incidents_severity'
					, 'incident_type.name as incident_type_name')
			->leftJoin('vehicle_incidents', 'vehicle_details.id', '=', 'vehicle_incidents.vehicleID')
			->leftJoin('incident_type', 'vehicle_incidents.incident_type', '=', 'incident_type.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->whereNull('vehicle_incidents.vehicle_fixed')
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('id', 'asc')
			->get();
		
		//Service  Overdue Alerts
		$servicesAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_serviceDetails.nxt_service_date')
			->leftJoin('vehicle_serviceDetails', 'vehicle_details.id', '=', 'vehicle_serviceDetails.vehicleID')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_serviceDetails.nxt_service_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('id', 'asc')
			->get();
			//return $servicesAlerts;
			
		//waranty Overdue Alerts
		$warantyAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_warranties.exp_date')
			->leftJoin('vehicle_warranties', 'vehicle_details.id', '=', 'vehicle_warranties.vehicleID')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_warranties.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();
			
		//permit Overdue Alerts
		$expiredPermitAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'permits_licence.exp_date'
					, 'fleet_licence_permit.name as license_name')
			->leftJoin('permits_licence', 'vehicle_details.id', '=', 'permits_licence.vehicleID')
			->leftJoin('fleet_licence_permit', 'permits_licence.permit_licence', '=', 'fleet_licence_permit.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('permits_licence.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();
			//return $servicesAlerts;
		
		//Documents Overdue Alerts
		$expiredDocAlerts = DB::table('vehicle_details')
			->select('vehicle_details.*','vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model'
					,'vehicle_managemnet.name as vehicle_type', 'division_level_fives.name as company'
					,'division_level_fours.name as Department', 'vehicle_documets.exp_date'
					, 'fleet_documentType.name as type_name')
			->leftJoin('vehicle_documets', 'vehicle_details.id', '=', 'vehicle_documets.vehicleID')
			->leftJoin('fleet_documentType', 'vehicle_documets.type', '=', 'fleet_documentType.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_documets.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();
			
		
        $data['page_title'] = " Fleet Management "; 
        $data['page_description'] = "Alerts";
        $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_alerts', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Fleet Alerts ', 'active' => 1, 'is_module' => 0]
        ];

        $data['collectionOverdueAlerts'] = $collectionOverdueAlerts;
        $data['returnOverdueAlerts'] = $returnOverdueAlerts;
        $data['incidentsAlerts'] = $incidentsAlerts;
        $data['servicesAlerts'] = $servicesAlerts;
        $data['warantyAlerts'] = $servicesAlerts;
        $data['expiredPermitAlerts'] = $expiredPermitAlerts;
        $data['expiredDocAlerts'] = $expiredDocAlerts;
        $data['bookingStatus'] = $bookingStatus;
        $data['severityArray'] = $severityArray;
        $data['status'] = $status;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Alerts';
		
		$companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Reports Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Alerts.alerts_print')->with($data);
    }
	
	public function downloadAlertExcel()
	{
		//die;
		$thirthyDate =  mktime(0, 0, 0, date('m'), date('d')-30, date('Y'));
		$data = DB::table('vehicle_details')
			->select('vehicle_details.*')
			->leftJoin('vehicle_documets', 'vehicle_details.id', '=', 'vehicle_documets.vehicleID')
			->leftJoin('fleet_documentType', 'vehicle_documets.type', '=', 'fleet_documentType.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->where('vehicle_documets.exp_date', '>=', $thirthyDate)
			->orderBy('division_level_fives.name', 'asc')
			->orderBy('exp_date', 'desc')
			->get();
			
			$type='csv';
		//$data = $data->toArray();
		return $data;
		return Excel::create('alerts', function($excel) use ($data) {
			$excel->sheet('mySheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}
}