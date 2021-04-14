<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\DivisionLevel;
use App\fleet_licence_permit;
use App\vehicle_fuel_log;
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
use Carbon\Carbon;
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
        $data['page_description'] = "Reports";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
            ->get();

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Reports";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $destination = $request['destination'];
        $purpose = $request['purpose'];
		
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
            ->where(function ($query) use ($destination) {
                if (!empty($destination)) {
                    $query->where('destination', 'ILIKE', "%$destination%");
                }
            })
            ->where(function ($query) use ($purpose) {
                if (!empty($purpose)) {
                    $query->where('purpose', 'ILIKE', "%$purpose%");
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
		$bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
		
		$data['bookingStatus'] = $bookingStatus;
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['destination'] = $destination;
        $data['purpose'] = $purpose;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Bookings Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray =  array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];
        $destination = $request['destination'];
        $purpose = $request['purpose'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
            ->where(function ($query) use ($destination) {
                if (!empty($destination)) {
                    $query->where('destination', 'ILIKE', "%$destination%");
                }
            })
            ->where(function ($query) use ($purpose) {
                if (!empty($purpose)) {
                    $query->where('purpose', 'ILIKE', "%$purpose%");
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
		$bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");
		
		$data['bookingStatus'] = $bookingStatus;
        $data['vehiclebookings'] = $vehiclebookings;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Bookings Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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

    // Get previous Killometers reading
	function getLastMeterReading($month, $year, $vehicleID,$field,$carKm)
	{
		// get dates
		$monthSend = strtotime("$year-$month");
		$monthText = date("F Y", $monthSend);
		$fuelMonth = date("M Y", $monthSend);
		$prevMonth = ($month == 1) ? 12 : $month-1;
		$monthStart = strtotime(new Carbon("first day of $fuelMonth"));
        $monthEnd = new Carbon("last day of $fuelMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());

		$lastMonthMeter = vehicle_fuel_log::
		select($field)
			->where('vehicleID', $vehicleID)
			->whereBetween('date', [$monthStart, $monthEnd])
			->orderBy('date', 'desc')
			->first();

		if (!empty($lastMonthMeter->$field))
			return $lastMonthMeter->$field;
		else
		{
			$lastMeterReading = vehicle_fuel_log::
			select($field)
			->where('vehicleID', $vehicleID)
			->where('date', '<',$monthStart)
			->orderBy('date', 'desc')
			->first();
			if (!empty($lastMeterReading->$field)) return $lastMeterReading->$field;
			else return $carKm;
		}
	}
	
	public function fuelReports(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = $actionMonth = $actionYear = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
			$startExplode[0] =  str_replace("/","-",$startExplode[0]);
            $actionFrom = strtotime($startExplode[0]);
			$startExplode[1] =  str_replace("/","-",$startExplode[1]);
            $actionTo = strtotime($startExplode[1]);
			$fromExplode = explode('-', $startExplode[0]);
			$actionMonth = $fromExplode[0];
			$actionYear = $fromExplode[2];
        }
		
        $fuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Status'
			, 'vehicle_fuel_log.id as fuelLogID'
			, 'vehicle_details.fleet_number as fleet_number'
			, 'vehicle_details.metre_reading_type as metre_reading_type'
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'fleet_fillingstation.name as station')
			->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
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
                    $query->whereBetween('vehicle_fuel_log.date', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
			->orderBy('vehicle_fuel_log.date')
            ->get();

        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }
		// get total per vehicle
		
		if (!empty($fuelLog))
		{
			$totalHours = $totalKms = $totalLitres = $totalCost = 0;
			$vehicleID = $count = $grandTotalHours = $grandTotalkms = 0;
			$oldkm = $oldhr = $litreTopUp = $perLitre = $kmTravelled = $hrTravelled = 0;
			$numItems = count($fuelLog);			
			foreach ($fuelLog as $fuel) {
				$maintenance = vehicle_maintenance::where('id', $fuel->vehicleID)->first();
				if ($fuel->metre_reading_type == 1) 
				{
					$carKm = $maintenance->odometer_reading;
					$field = 'Odometer_reading';
				}
				else 
				{
					$field = 'Hoursreading';
					$carKm = $maintenance->hours_reading;
				}
				$prevMonth = ($actionFrom == 1) ? 12 : $actionFrom -1;
				if ($actionFrom > 0 && $actionTo > 0)
				{
					if ($actionMonth == 1)
					{
						$iprevYear = $actionYear - 1;
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $iprevYear,$maintenance->id,$field,$carKm);
					}
					else
					{
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $actionYear,$maintenance->id,$field,$carKm);
					}
				}
				else $prevMonthkm = $carKm;

				if ($count == 0)
					$kmTravelled = $fuel->$field - $prevMonthkm;
				else $kmTravelled = $fuel->$field - $oldkm;
					
				if ($fuel->metre_reading_type === 1)
					$fuel->km_travelled = $kmTravelled;
				else $fuel->hr_travelled = $kmTravelled;		
				$count ++;
				
				if ($vehicleID != $fuel->vehicleID && $vehicleID != 0)
				{
					// reset total to zero
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
					$grandTotalHours = $grandTotalHours + $totalHours;
					$grandTotalkms = $grandTotalkms + $totalKms;
					$totalHours = $totalKms = $oldkm = $oldhr =
					$totalLitres = $totalCost = $kmTravelled = $hrTravelled = 0;
				}
				$totalKms = $totalKms + $kmTravelled;
				$totalHours = $totalHours + $hrTravelled;
				$totalLitres = $totalLitres + $fuel->litres_new;
				$totalCost = $totalCost + $fuel->total_cost;
				if($count === $numItems) {
					
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
				}
				$oldkm = $fuel->Odometer_reading;
				$oldhr = $fuel->Hoursreading;
				$vehicleID = $fuel->vehicleID;
            }
		}

		$totalLitres = $fuelLog->sum('litres_new');
        $totalCost = $fuelLog->sum('total_cost');
		
		$data['totalKms'] = $grandTotalkms;
        $data['totalHours'] = $grandTotalHours ;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['fuelLog'] = $fuelLog;
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fuel Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
        $actionFrom = $actionTo = $actionMonth = $actionYear = 0;;
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
			$fromExplode = explode('/', $startExplode[0]);
			$actionMonth = $fromExplode[0];
			$actionYear = $fromExplode[2];
        }

        $fuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Status'
			, 'vehicle_fuel_log.id as fuelLogID'
			, 'vehicle_details.fleet_number as fleet_number'
			, 'vehicle_details.metre_reading_type as metre_reading_type'
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'fleet_fillingstation.name as station')
			->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
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
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
			->orderBy('vehicle_fuel_log.date')
            ->get();
		//
			if (!empty($fuelLog))
			{
				$totalHours = $totalKms = $totalLitres = $totalCost = 0;
				$vehicleID = $count = $grandTotalHours = $grandTotalkms = 0;
				$oldkm = $oldhr = $litreTopUp = $perLitre = $kmTravelled = $hrTravelled = 0;
				$numItems = count($fuelLog);			
				foreach ($fuelLog as $fuel) {
					$maintenance = vehicle_maintenance::where('id', $fuel->vehicleID)->first();
					if ($fuel->metre_reading_type == 1) 
					{
						$carKm = $maintenance->odometer_reading;
						$field = 'Odometer_reading';
					}
					else 
					{
						$field = 'Hoursreading';
						$carKm = $maintenance->hours_reading;
					}
					$prevMonth = ($actionMonth == 1) ? 12 : $actionMonth -1;
					if ($actionFrom > 0 && $actionTo > 0)
					{
						if ($actionMonth == 1)
						{
							$iprevYear = $actionYear - 1;
							$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $iprevYear,$maintenance->id,$field,$carKm);
						}
						else
						{
							$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $actionYear,$maintenance->id,$field,$carKm);
						}
					}
					else $prevMonthkm = $carKm;

					if ($count == 0)
						$kmTravelled = $fuel->$field - $prevMonthkm;
					else $kmTravelled = $fuel->$field - $oldkm;
						
					if ($fuel->metre_reading_type === 1)
						$fuel->km_travelled = $kmTravelled;
					else $fuel->hr_travelled = $kmTravelled;		
					$count ++;
					
					if ($vehicleID != $fuel->vehicleID && $vehicleID != 0)
					{
						// reset total to zero
						$fuel->total_hours = $totalHours;
						$fuel->total_kms = $totalKms;
						$fuel->total_litres = $totalLitres;
						$fuel->total_costs = $totalCost;
						$grandTotalHours = $grandTotalHours + $totalHours;
						$grandTotalkms = $grandTotalkms + $totalKms;
						$totalHours = $totalKms = $oldkm = $oldhr =
						$totalLitres = $totalCost = $kmTravelled = $hrTravelled = 0;
					}
					$totalKms = $totalKms + $kmTravelled;
					$totalHours = $totalHours + $hrTravelled;
					$totalLitres = $totalLitres + $fuel->litres_new;
					$totalCost = $totalCost + $fuel->total_cost;
					if($count === $numItems) {
						
						$fuel->total_hours = $totalHours;
						$fuel->total_kms = $totalKms;
						$fuel->total_litres = $totalLitres;
						$fuel->total_costs = $totalCost;
					}
					$oldkm = $fuel->Odometer_reading;
					$oldhr = $fuel->Hoursreading;
					$vehicleID = $fuel->vehicleID;
				}
			}
		$totalLitres = $fuelLog->sum('litres_new');
        $totalCost = $fuelLog->sum('total_cost');
		
		$data['totalKms'] = $grandTotalkms;
        $data['totalHours'] = $grandTotalHours ;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['fuelLog'] = $fuelLog;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fuel Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
                    $query->whereIn('vehicleID', $vehicleArray);
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
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fines Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
		if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
                    $query->whereIn('vehicleID', $vehicleArray);
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
        $data['page_description'] = "Fines Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }
        $serviceDetails = DB::table('vehicle_serviceDetails')
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
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->get();

		for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }

        $totalCost = $serviceDetails->sum('total_cost');
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['serviceDetails'] = $serviceDetails;
        $data['totalCost'] = $totalCost;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Service Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.Servicedetailslog_results')->with($data);
    }

    public function ServiceReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }
        $serviceDetails = DB::table('vehicle_serviceDetails')
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
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->get();

        for ($i = 0; $i < count($vehicleArray); $i++) {
            $vehicle .= $vehicleArray[$i] . ',';
        }
        $totalCost = $serviceDetails->sum('total_cost');
        $data['serviceDetails'] = $serviceDetails;
        $data['totalCost'] = $totalCost;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }
        $vehicleincidents = DB::table('vehicle_incidents')
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
                    $query->where('reported_by', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date_of_incident', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->get();

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['status'] = $status;
        $data['severity'] = $severity;
        $data['vehicleincidents'] = $vehicleincidents;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Incidents Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }

        $vehicleincidents = DB::table('vehicle_incidents')
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
                    $query->where('reported_by', $driverID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date_of_incident', [$actionFrom, $actionTo]);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->get();

        $severity = array(1 => ' Minor', 2 => ' Major ', 3 => 'Critical ');
        $status = array(1 => '  Reported', 2 => '  Scheduled for Repair  ', 3 => ' Resolved  ');

        $data['status'] = $status;
        $data['severity'] = $severity;
        $data['vehicleincidents'] = $vehicleincidents;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Incidents Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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

        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        $vehicleType = $reportData['vehicle_type'];
        $vehicleMake = $reportData['vehicle_make'];
		$divisionLevel_5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $divisionLevel_4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $divisionLevel_3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $divisionLevel_2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $divisionLevel_1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;

        $vehicledetails = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('division_level_threes', 'vehicle_details.division_level_4', '=', 'division_level_threes.id')
            ->leftJoin('division_level_twos', 'vehicle_details.division_level_4', '=', 'division_level_twos.id')
            ->leftJoin('division_level_ones', 'vehicle_details.division_level_4', '=', 'division_level_ones.id')
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
			->where(function ($query) use ($divisionLevel_5) {
                if (!empty($divisionLevel_5)) {
                    $query->where('vehicle_details.division_level_5', $divisionLevel_5);
                }
            })
			->where(function ($query) use ($divisionLevel_4) {
                if (!empty($divisionLevel_4)) {
                    $query->where('vehicle_details.division_level_4', $divisionLevel_4);
                }
            })
			->where(function ($query) use ($divisionLevel_3) {
                if (!empty($divisionLevel_3)) {
                    $query->where('vehicle_details.division_level_3', $divisionLevel_3);
                }
            })
			->where(function ($query) use ($divisionLevel_2) {
                if (!empty($divisionLevel_2)) {
                    $query->where('vehicle_details.division_level_2', $divisionLevel_2);
                }
            })
			->where(function ($query) use ($divisionLevel_1) {
                if (!empty($divisionLevel_1)) {
                    $query->where('vehicle_details.division_level_1', $divisionLevel_1);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('id', $vehicleArray);
				}
            })
            ->get();
        $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['vehicle_make'] = $vehicleMake;
        $data['division_level_5'] = $divisionLevel_5;
        $data['division_level_4'] = $divisionLevel_4;
        $data['division_level_3'] = $divisionLevel_3;
        $data['division_level_2'] = $divisionLevel_2;
        $data['division_level_1'] = $divisionLevel_1;
        $data['vehicledetails'] = $vehicledetails;
        $data['status'] = $status;

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Details Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $vehicleMake = $reportData['vehicle_make'];
		$divisionLevel_5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
        $divisionLevel_4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
        $divisionLevel_3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
        $divisionLevel_2 = !empty($request['division_level_2']) ? $request['division_level_2'] : 0;
        $divisionLevel_1 = !empty($request['division_level_1']) ? $request['division_level_1'] : 0;

		$vehicledetails = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as department')
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
            ->where(function ($query) use ($divisionLevel_5) {
                if (!empty($divisionLevel_5)) {
                    $query->where('vehicle_details.division_level_5', $divisionLevel_5);
                }
            })
			->where(function ($query) use ($divisionLevel_4) {
                if (!empty($divisionLevel_4)) {
                    $query->where('vehicle_details.division_level_4', $divisionLevel_4);
                }
            })
			->where(function ($query) use ($divisionLevel_3) {
                if (!empty($divisionLevel_3)) {
                    $query->where('vehicle_details.division_level_3', $divisionLevel_3);
                }
            })
			->where(function ($query) use ($divisionLevel_2) {
                if (!empty($divisionLevel_2)) {
                    $query->where('vehicle_details.division_level_2', $divisionLevel_2);
                }
            })
			->where(function ($query) use ($divisionLevel_1) {
                if (!empty($divisionLevel_1)) {
                    $query->where('vehicle_details.division_level_1', $divisionLevel_1);
                }
            })
            ->Where(function ($query) use ($vehicleArray) {
				if (!empty($vehicleArray)) {
                    $query->whereIn('id', $vehicleArray);
				}
            })
            ->get();

        $status = array(1 => '   Unleaded', 2 => 'Lead replacement', 3 => 'Diesel');

        $data['status'] = $status;
        $data['vehicledetails'] = $vehicledetails;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Details Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
                    $query->whereIn('permits_licence.vehicleID', $vehicleArray);
				}
            })
            ->where('permits_licence.exp_date', '<', $currentTime)
            ->orderby('permits_licence.id', 'desc')
            ->get();
			
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['action_date'] = $actionDate;
        $data['vehicleDocumets'] = $vehicleDocumets;
        $data['VehicleLicences'] = $VehicleLicences;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
        $data['page_description'] = "Expired Documents Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
		if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
                    $query->whereIn('permits_licence.vehicleID', $vehicleArray);
				}
            })
            ->where('permits_licence.exp_date', '<', $currentTime)
            ->orderby('permits_licence.id', 'desc')
            ->get();
        $data['VehicleLicences'] = $VehicleLicences;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Expired Licences Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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

        $actionFrom = $actionTo = $actionMonth =$actionYear = 0;
        $vehicle = '';
        $vehicleArray = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
		$vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
			$fromExplode = explode('/', $startExplode[0]);
			$actionMonth = $fromExplode[0];
			$actionYear = $fromExplode[2];
        }

        $externalFuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_details.fleet_number as fleet_number'
                , 'fleet_fillingstation.name as supplier'
                , 'vehicle_details.metre_reading_type'
				, 'vehicle_details.id as vehicle_id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
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
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->where('vehicle_fuel_log.tank_and_other', '=', 2)
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
			->orderBy('vehicle_fuel_log.date')
            ->orderby('supplier', 'asc')
            ->get();
		
		for ($i = 0; $i < count($vehicleArray); $i++) {
			$vehicle .= $vehicleArray[$i] . ',';
		}
		$totalHours = $totalKms = $totalLitres = $totalCost = 0;
		$vehicleID = $count = $grandTotalHours = $grandTotalkms = 0;
		if (!empty($externalFuelLog))
		{
			$oldkm = $oldhr = $litreTopUp = $perLitre = $kmTravelled = $hrTravelled = 0;
			$numItems = count($externalFuelLog);			
			foreach ($externalFuelLog as $fuel) {
				$maintenance = vehicle_maintenance::where('id', $fuel->vehicleID)->first();
				if ($fuel->metre_reading_type == 1) 
				{
					$carKm = $maintenance->odometer_reading;
					$field = 'Odometer_reading';
				}
				else 
				{
					$field = 'Hoursreading';
					$carKm = $maintenance->hours_reading;
				}
				$prevMonth = ($actionMonth == 1) ? 12 : $actionMonth -1;
				if ($actionFrom > 0 && $actionTo > 0)
				{
					if ($actionMonth == 1)
					{
						$iprevYear = $actionYear - 1;
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $iprevYear,$maintenance->id,$field,$carKm);
					}
					else
					{
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $actionYear,$maintenance->id,$field,$carKm);
					}
				}
				else $prevMonthkm = $carKm;

				if ($count == 0)
					$kmTravelled = $fuel->$field - $prevMonthkm;
				else $kmTravelled = $fuel->$field - $oldkm;
					
				if ($fuel->metre_reading_type === 1)
					$fuel->km_travelled = $kmTravelled;
				else $fuel->hr_travelled = $kmTravelled;		
				$count ++;
				
				if ($vehicleID != $fuel->vehicleID && $vehicleID != 0)
				{
					// reset total to zero
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
					$grandTotalHours = $grandTotalHours + $totalHours;
					$grandTotalkms = $grandTotalkms + $totalKms;
					$totalHours = $totalKms = $oldkm = $oldhr =
					$totalLitres = $totalCost = $kmTravelled = $hrTravelled = 0;
				}
				$totalKms = $totalKms + $kmTravelled;
				$totalHours = $totalHours + $hrTravelled;
				$totalLitres = $totalLitres + $fuel->litres_new;
				$totalCost = $totalCost + $fuel->total_cost;
				if($count === $numItems) {
					
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
				}
				$oldkm = $fuel->Odometer_reading;
				$oldhr = $fuel->Hoursreading;
				$vehicleID = $fuel->vehicleID;
			}
		}
        $totalLitres = $externalFuelLog->sum('litres_new');
        $totalCost = $externalFuelLog->sum('total_cost');
        if (!empty($grandTotalkms) && !empty($totalLitres)) $totalAvgKms = $grandTotalkms / $totalLitres;
        else $totalAvgKms = 0;
        if (!empty($grandTotalHours) && !empty($totalLitres)) $totalAvgHrs = $grandTotalHours / $totalLitres;
        else $totalAvgHrs = 0;
        if (!empty($totalCost) && !empty($totalLitres)) $totalAvgCost = $totalCost / $totalLitres;
        else $totalAvgCost = 0;

        $data['vehicle_id'] = rtrim($vehicle, ",");
        $data['vehicle_type'] = $vehicleType;
        $data['driver_id'] = $driverID;
        $data['action_date'] = $actionDate;
        $data['externalFuelLog'] = $externalFuelLog;
        $data['grandTotalHours'] = $grandTotalHours;
        $data['grandTotalkms'] = $grandTotalkms;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['totalAvgKms'] = $totalAvgKms;
        $data['totalAvgHrs'] = $totalAvgHrs;
        $data['totalAvgCost'] = $totalAvgCost;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "External Fuel Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.fuelexternallog_results')->with($data);
    }

    public function ExternalFuelReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = $actionMonth =$actionYear = 0;
		$vehicleArray = array();
        $vehicle = isset($reportData['vehicle_id']) ? $reportData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $reportData['vehicle_type'];
        $driverID = $reportData['driver_id'];
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
			$fromExplode = explode('/', $startExplode[0]);
			$actionMonth = $fromExplode[0];
			$actionYear = $fromExplode[2];
        }

        $externalFuelLog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_details.fleet_number as fleet_number'
                , 'fleet_fillingstation.name as supplier'
                , 'vehicle_details.metre_reading_type'
				, 'vehicle_details.id as vehicle_id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
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
                    $query->whereIn('vehicleID', $vehicleArray);
				}
            })
            ->where('vehicle_fuel_log.tank_and_other', '=', 2)
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
			->orderBy('vehicle_fuel_log.date')
            ->orderby('supplier', 'asc')
            ->get();
		$totalHours = $totalKms = $totalLitres = $totalCost = 0;
		$vehicleID = $count = $grandTotalHours = $grandTotalkms = 0;
		if (!empty($externalFuelLog))
		{
			$oldkm = $oldhr = $litreTopUp = $perLitre = $kmTravelled = $hrTravelled = 0;
			$numItems = count($externalFuelLog);			
			foreach ($externalFuelLog as $fuel) {
				$maintenance = vehicle_maintenance::where('id', $fuel->vehicleID)->first();
				if ($fuel->metre_reading_type == 1) 
				{
					$carKm = $maintenance->odometer_reading;
					$field = 'Odometer_reading';
				}
				else 
				{
					$field = 'Hoursreading';
					$carKm = $maintenance->hours_reading;
				}
				$prevMonth = ($actionMonth == 1) ? 12 : $actionMonth -1;
				if ($actionFrom > 0 && $actionTo > 0)
				{
					if ($actionMonth == 1)
					{
						$iprevYear = $actionYear - 1;
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $iprevYear,$maintenance->id,$field,$carKm);
					}
					else
					{
						$prevMonthkm = VehicleReportsController::getLastMeterReading($prevMonth, $actionYear,$maintenance->id,$field,$carKm);
					}
				}
				else $prevMonthkm = $carKm;

				if ($count == 0)
					$kmTravelled = $fuel->$field - $prevMonthkm;
				else $kmTravelled = $fuel->$field - $oldkm;
					
				if ($fuel->metre_reading_type === 1)
					$fuel->km_travelled = $kmTravelled;
				else $fuel->hr_travelled = $kmTravelled;		
				$count ++;
				
				if ($vehicleID != $fuel->vehicleID && $vehicleID != 0)
				{
					// reset total to zero
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
					$grandTotalHours = $grandTotalHours + $totalHours;
					$grandTotalkms = $grandTotalkms + $totalKms;
					$totalHours = $totalKms = $oldkm = $oldhr =
					$totalLitres = $totalCost = $kmTravelled = $hrTravelled = 0;
				}
				$totalKms = $totalKms + $kmTravelled;
				$totalHours = $totalHours + $hrTravelled;
				$totalLitres = $totalLitres + $fuel->litres_new;
				$totalCost = $totalCost + $fuel->total_cost;
				if($count === $numItems) {
					
					$fuel->total_hours = $totalHours;
					$fuel->total_kms = $totalKms;
					$fuel->total_litres = $totalLitres;
					$fuel->total_costs = $totalCost;
				}
				$oldkm = $fuel->Odometer_reading;
				$oldhr = $fuel->Hoursreading;
				$vehicleID = $fuel->vehicleID;
			}
		}
		
        $totalLitres = $externalFuelLog->sum('litres_new');
        $totalCost = $externalFuelLog->sum('total_cost');
        if (!empty($grandTotalkms) && !empty($totalLitres)) $totalAvgKms = $grandTotalkms / $totalLitres;
        else $totalAvgKms = 0;
        if (!empty($grandTotalHours) && !empty($totalLitres)) $totalAvgHrs = $grandTotalHours / $totalLitres;
        else $totalAvgHrs = 0;
        if (!empty($totalCost) && !empty($totalLitres)) $totalAvgCost = $totalCost / $totalLitres;
        else $totalAvgCost = 0;

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "External Fuel Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
        $data['externalFuelLog'] = $externalFuelLog;
        		
		$data['grandTotalHours'] = $grandTotalHours;
        $data['grandTotalkms'] = $grandTotalkms;
        $data['totalLitres'] = $totalLitres;
        $data['totalCost'] = $totalCost;
        $data['totalAvgKms'] = $totalAvgKms;
        $data['totalAvgHrs'] = $totalAvgHrs;
        $data['totalAvgCost'] = $totalAvgCost;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.vehicleextelFuel_report_print')->with($data);
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
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
                    $query->whereIn('fleet_number', $vehicleArray);
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
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		$vehicleArray = array();
        $cardtype = $request['card_type_id'];
        $company = $request['company_id'];
        $holder = $vehicleData['driver_id'];
        $actionDate = $request['action_date'];
        $vehicle = isset($vehicleData['vehicle_id']) ? $vehicleData['vehicle_id'] : array();
        if (!empty($vehicle))
			$vehicleArray = (explode(",", $vehicle));
        $vehicleType = $vehicleData['vehicle_type'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
                    $query->whereIn('fleet_number', $vehicleArray);
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
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
            ->orderBy('vehicle_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

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
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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
		else $vehicleArray =array();
        $actionDate = $request['action_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
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
            ->orderBy('vehicle_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
		$statusArray= array(1 => 'Active', 2 => ' Allocate', 3 => 'In Use', 4 => 'Empty', 5=> 'Evacate', 6=> 'In Storage', 7=> 'Discarded', 8=> 'Rental' , 9=> 'Sold');
        
        $data['fireExtinguishers'] = $fireExtinguishers;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fire Extinguisher Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
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