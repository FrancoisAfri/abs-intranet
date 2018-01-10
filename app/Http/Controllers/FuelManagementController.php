<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\vehicle_warranties;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\job_maintanace;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\keytracking;
use App\vehicle_fines;
use App\safe;
Use App\reminders;
use App\vehicle_documets;
use App\images;
use App\vehicle_fuel_log;
use App\vehicle_incidents;
use App\ContactCompany;
use App\general_cost;
use App\fleet_fillingstation;
use App\module_access;
use App\DivisionLevelFive;
use App\vehicle_insurance;
use App\module_ribbons;
Use App\vehicle_serviceDetails;
use App\ribbons_access;
use App\service_station;
use App\tank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Fueltanks;

class FuelManagementController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fueltankIndex(Request $request)
    {
     

       
        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $Fueltanks = DB::table('fuel_tanks')
        ->select('fuel_tanks.*', 'hr_people.first_name as first_name', 'hr_people.surname as surname',
        'division_level_fives.name as company', 'division_level_fours.name as Department')
        ->leftJoin('hr_people', 'fuel_tanks.tank_manager', '=', 'hr_people.id')
        ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
        ->leftJoin('division_level_fours', 'fuel_tanks.division_level_4', '=', 'division_level_fours.id')
        ->orderBy('fuel_tanks.id')
        ->get();
        
        Fueltanks::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['employees'] = $employees;
        $data['Fueltanks'] = $Fueltanks;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

          AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.fueltanks')->with($data);

    }

    public function Addfueltank(Request $request){
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);  

        $Fueltanks = new Fueltanks();
        $Fueltanks->division_level_1 = !empty($FueltankData['division_level_1']) ? $FueltankData['division_level_1'] : 0;
        $Fueltanks->division_level_2 = !empty($FueltankData['division_level_2']) ? $FueltankData['division_level_2'] : 0;
        $Fueltanks->division_level_3 = !empty($FueltankData['division_level_3']) ? $FueltankData['division_level_3'] : 0;
        $Fueltanks->division_level_4 = !empty($FueltankData['division_level_4']) ? $FueltankData['division_level_4'] : 0;
        $Fueltanks->division_level_5 = !empty($FueltankData['division_level_5']) ? $FueltankData['division_level_5'] : 0;
        $Fueltanks->tank_name = $FueltankData['tank_name'];
        $Fueltanks->tank_location = $FueltankData['tank_location'];
        $Fueltanks->tank_description = $FueltankData['tank_description'];
        $Fueltanks->tank_capacity = !empty($FueltankData['tank_capacity']) ? $FueltankData['tank_capacity'] : 0;
        $Fueltanks->tank_manager = !empty($FueltankData['tank_manager']) ? $FueltankData['tank_manager'] : 0;
        $Fueltanks->status = 1;    
        $Fueltanks->save(); 
        
        AuditReportsController::store('Fuel Management', 'Fuel Tank added', "Accessed By User", 0);
        return back();
    }


    public function FuelTankAct(Request $request, Fueltanks $fuel)
    {
        if ($fuel->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $fuel->status = $stastus;
        $fuel->update();
        return back();
    }

}
