<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\CompanyIdentity;
use App\DivisionLevel;
use App\vehicle_warranties;
use App\vehiclemodel;
use App\Vehicle_managemnt;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\keytracking;
use App\vehicle_fines;
use App\safe;
Use App\reminders;
use App\HRPerson;
use App\tank;
use App\vehicle_documets;
use App\images;
use App\incident_type;
use App\vehicle_fuel_log;
use App\vehicle_incidents;
use App\ContactCompany;
use App\general_cost;
use App\VehicleIncidentsDocuments;
use App\fleet_fillingstation;
use App\VehicleCommunications;
use App\vehicle_insurance;
use App\module_ribbons;
Use App\vehicle_serviceDetails;
use App\ribbons_access;
use App\service_station;
use App\Fueltanks;
use App\fleet_documentType;
use App\vehicle_config;
use App\ContactPerson;
use App\SmS_Configuration;
use App\vehicle;
use App\jobcard_maintanance;
use App\jobcards_config;
use App\FueltankTopUp;
use App\DivisionLevelFour;
use App\DivisionLevelFive;
use App\vehicle_detail;
use App\Http\Controllers\BulkSMSController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\VehicleCommunication;
use App\Mail\VehicleCommunicationsEmployees;
use Illuminate\Contracts\Filesystem\Factory;

class VehicleDashboard extends Controller
{
	// enforce login
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {
        $loggedInEmplID = Auth::user()->person->id;

        $data['breadcrumb'] = [
            ['title' => 'Vehicle Dashboard', 'path' => '/vehicle/overview', 'icon' => 'fa fa-dashboard', 'active' => 1, 'is_module' => 1]
        ];
        $data['active_mod'] = 'Vehicle Overview';
        $user = Auth::user()->load('person');

        $activeVehicles = vehicle_detail::Where('status',1)->get()->count();
        $inactivevehicles = vehicle_detail::Where('status',4)->get()->count();
        $requiereApprovalVehicles = vehicle_detail::Where('status',2)->get()->count();
        $rejectedVehicles = vehicle_detail::Where('status',3)->get()->count();

		$data['activeVehicles'] = $activeVehicles;
		$data['inactivevehicles'] = $inactivevehicles;
		$data['requiereApprovalVehicles'] = $requiereApprovalVehicles;
		$data['rejectedVehicles'] = $rejectedVehicles;
		$data['page_title'] = "Vehicle Dashboard";
		$data['page_description'] = "This Is Your Vehicle Dashboard";

		return view('Vehicles.FleetManagement.dashboard')->with($data); //Admin Dashboard
    }
	public function vehicleStatus(){      
		
		$activeVehicles = vehicle_detail::Where('status',1)->get()->count();
        $inactivevehicles = vehicle_detail::Where('status',4)->get()->count();
        $requiereApprovalVehicles = vehicle_detail::Where('status',2)->get()->count();
        $rejectedVehicles = vehicle_detail::Where('status',3)->get()->count();
		
		$graphData = [];
		$graphData['activeVehicles'] = $activeVehicles;
		$graphData['inactivevehicles'] = $inactivevehicles;
		$graphData['requiereApprovalVehicles'] = $requiereApprovalVehicles;
		$graphData['rejectedVehicles'] = $rejectedVehicles;
		return $graphData;
    }
}
