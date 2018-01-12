<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\Vehicle_managemnt;
use App\HRPerson;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\FueltankPrivateUse;
use App\ContactCompany;
use App\FueltankTopUp;
use App\module_ribbons;
use App\ribbons_access;
use App\Fueltanks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

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

        //return $Fueltanks;
        // Fueltanks::orderBy('id', 'asc')->get();

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
        $data['active_rib'] = 'Manage Fuel Tanks';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.fueltanks')->with($data);

    }

    public function Addfueltank(Request $request)
    {
        $this->validate($request, [
            'tank_capacity' => 'required',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $Fueltanks = new Fueltanks();
        // $sCapacity = number_format($FueltankData['tank_capacity'], 0, '.', ',');
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
        $Fueltanks->current_fuel_litres = !empty($FueltankData['current_fuel_litres']) ? number_format($FueltankData['current_fuel_litres'], 0, '.', ',') : 0;
        $Fueltanks->save();

        AuditReportsController::store('Fuel Management', 'Fuel Tank added', "Accessed By User", 0);
        return back();
    }

    public function editfueltank(Request $request, Fueltanks $Fueltanks)
    {
        $this->validate($request, [

        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $ID = $Fueltanks->id;
        $value = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->get();
        $DIV4 = $value->first()->division_level_4;
        $DIV5 = $value->first()->division_level_5;

        $Fueltanks->division_level_1 = !empty($FueltankData['division_level_1']) ? $FueltankData['division_level_1'] : 0;
        $Fueltanks->division_level_2 = !empty($FueltankData['division_level_2']) ? $FueltankData['division_level_2'] : 0;
        $Fueltanks->division_level_3 = !empty($FueltankData['division_level_3']) ? $FueltankData['division_level_3'] : 0;
        $Fueltanks->division_level_4 = !empty($FueltankData['division_level_4']) ? $FueltankData['division_level_4'] : $DIV4;
        $Fueltanks->division_level_5 = !empty($FueltankData['division_level_5']) ? $FueltankData['division_level_5'] : $DIV5;
        $Fueltanks->tank_name = $FueltankData['tank_name'];
        $Fueltanks->tank_location = $FueltankData['tank_location'];
        $Fueltanks->tank_description = $FueltankData['tank_description'];
        $Fueltanks->tank_capacity = !empty($FueltankData['tank_capacity']) ? $FueltankData['tank_capacity'] : 0;
        $Fueltanks->tank_manager = !empty($FueltankData['tank_manager']) ? $FueltankData['tank_manager'] : 0;
        $Fueltanks->current_fuel_litres = !empty($FueltankData['current_fuel_litres']) ? $FueltankData['current_fuel_litres'] : 0;
        $Fueltanks->update();

        AuditReportsController::store('Fuel Management', 'Fuel Tank added', "Accessed By User", 0);
        return response()->json();
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

    public function ViewTank(Request $request, Fueltanks $fuel)
    {

        $ID = $fuel->id;
        $value = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();


        $vehiclemaintenance = DB::table('fuel_tanks')
            ->select('fuel_tanks.*', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'fuel_tanks.division_level_4', '=', 'division_level_fours.id')
            ->orderBy('fuel_tanks.id')
            ->where('fuel_tanks.id', $ID)
            ->get();

        $company = $vehiclemaintenance->first()->company;
        $Department = $vehiclemaintenance->first()->Department;


        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['ID'] = $ID;
        $data['company'] = $company;
        $data['Department'] = $Department;
        $data['employees'] = $employees;
        $data['fuel'] = $fuel;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fuel Tanks';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Viewtank')->with($data);

    }

    public function tanksearch(Request $request, Fueltanks $tank)
    {
        $this->validate($request, [

        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $ID = $tank->id;
        $Fueltanks = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->get()->first();

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $FueltankTopUpwhere = FueltankTopUp::where('status', 1)->orderBy('id', 'desc')->get();
        $FueltankPrivateUse = FueltankPrivateUse::where('status', 1)->orderBy('id', 'desc')->get();

        $ContactCompany = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $Fueltank = DB::table('fuel_tanks')
            ->select('fuel_tanks.*', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'fuel_tanks.division_level_4', '=', 'division_level_fours.id')
            ->orderBy('fuel_tanks.id')
            ->where('fuel_tanks.id', $ID)
            ->get();

        $current = DB::table('fuel_tanks')->where('id', $ID)->pluck('current_fuel_litres')->first();

        $tank = $Fueltank->first()->tank_name;
        //    $current = $Fueltanks->first()->current_fuel_litres;


        $FueltankTopUp = FueltankTopUp::orderBy('id', 'desc')->get();
        $Fueltanks = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->first();
        // return $Fueltanks;

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['name'] = $name;
        $data['current'] = $current;
        $data['ContactCompany'] = $ContactCompany;
        $data['employees'] = $employees;
        $data['tank'] = $tank;
        $data['ID'] = $ID;
        $data['Fueltank'] = $Fueltank;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fuel Tanks';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.tank_results')->with($data);
    }

    public function TanktopUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // $this->validate($request, [
            'supplier_id' => 'required',
            'document_no' => 'required|unique:fuel_tank_topUp,document_no',
            'document_date' => 'required',
            'topup_date' => 'required',
            'cost_per_litre' => 'required',
            'received_by' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {

            $CurrentAmount = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('current_fuel_litres')->first();
            $TankCapacity = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('tank_capacity')->first();
            $tank_capacity = 'tank_capacity';
            $NewAmount = $CurrentAmount + $FueltankData['litres'];

            if (empty($sAlert) && ($NewAmount) > $TankCapacity) {
                $validator->errors()->add($tank_capacity, 'Error: Cannot exceed tank capacity. Available capacity:' . ($TankCapacity - $CurrentAmount) . " litres");
                // $sAlert = "Error: Cannot exceed tank capacity. Available capacity: ".($TankCapacity - $CurrentAmount)." litres";
            }

        });


        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        #Type 1 = incoming , Type 2 = Outgoing

        $Fueltanks = Fueltanks::where('id', $FueltankData['tank_id'])->orderBy('id', 'desc')->first();
        $CurrentAmount = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('current_fuel_litres')->first();
        $TankCapacity = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('tank_capacity')->first();

        $NewAmount = $CurrentAmount + $FueltankData['litres'];


        $topUp = new FueltankTopUp();
        $topUp->supplier_id = !empty($FueltankData['supplier_id']) ? $FueltankData['supplier_id'] : 0;
        $topUp->document_no = $FueltankData['document_no'];
        $topUp->document_date = strtotime($FueltankData['document_date']);
        $topUp->topup_date = strtotime($FueltankData['topup_date']);
        $topUp->type = 1; //Incoming
        $topUp->reading_before_filling = !empty($FueltankData['reading_before_filling']) ? $FueltankData['reading_before_filling'] : 0;
        $topUp->reading_after_filling = !empty($FueltankData['reading_after_filling']) ? $FueltankData['reading_after_filling'] : 0;
        $topUp->litres = $FueltankData['litres'];
        $topUp->cost_per_litre = $FueltankData['cost_per_litre'];
        $topUp->total_cost = $FueltankData['total_cost'];
        $topUp->description = $FueltankData['description'];
        $topUp->received_by = !empty($FueltankData['received_by']) ? $FueltankData['received_by'] : 0;
        $topUp->captured_by = $loggedInEmplID = Auth::user()->person->id;
        $topUp->tank_id = !empty($FueltankData['tank_id']) ? $FueltankData['tank_id'] : 0;
        $topUp->status = 1;
        $topUp->save();
        // update the tank with the new Capacity
        DB::table('fuel_tanks')
            ->where('id', $FueltankData['tank_id'])
            ->update(['current_fuel_litres' => $NewAmount]);

        AuditReportsController::store('Fuel Management', 'Fuel Tank Top Up', "Accessed By User", 0);
        return response()->json();

    }


    public function TankprivateUse(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);

        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $privateuse = new FueltankPrivateUse();
        $privateuse->document_no = $FueltankData['document_no'];
        $privateuse->document_date = strtotime($FueltankData['documents_date']);
        $privateuse->usage_date = strtotime($FueltankData['usage_date']);
        $privateuse->type = !empty($FueltankData['type']) ? $FueltankData['type'] : 0;
        $privateuse->make_or_model = $FueltankData['make_or_model'];
        $privateuse->registration_number = $FueltankData['registration_number'];
        $privateuse->description = $FueltankData['description'];
        $privateuse->received_by = !empty($FueltankData['received_by']) ? $FueltankData['received_by'] : 0;
        $privateuse->captured_by = $loggedInEmplID = Auth::user()->person->id;
        $privateuse->tank_id = !empty($FueltankData['tank_id']) ? $FueltankData['tank_id'] : 0;
        $privateuse->person_responsible = !empty($FueltankData['person_responsible']) ? $FueltankData['person_responsible'] : 0;
        $privateuse->status = 1;
        $privateuse->save();

        AuditReportsController::store('Fuel Management', 'Fuel Tank Top Up', "Accessed By User", 0);
        return response()->json();
    }

    //
    public function tank_approval(Request $request)
    {


        // $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();

        $vehiclemaintenance = DB::table('fuel_tanks')
            ->select('fuel_tanks.*', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'fuel_tanks.division_level_4', '=', 'division_level_fours.id')
            ->orderBy('fuel_tanks.id')
            //->where('fuel_tanks.id', $ID)
            ->get();


        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['ID'] = $ID;
        // $data['company'] = $company;
        // $data['Department'] = $Department;
        // $data['employees'] = $employees;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fuel Tanks';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.tanksearch')->with($data);

    }

    public function ApproveTank()
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();
        // return $vehicle_maintenance;

        $Fueltank = DB::table('fuel_tanks')
            ->select('fuel_tanks.*', 'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'fuel_tanks.division_level_4', '=', 'division_level_fours.id')
            ->orderBy('fuel_tanks.id')
            //->where('fuel_tanks.id', $ID)
            ->get();

        //return $Vehiclemanagemnt;

        $data['page_title'] = "Fuel Tank Approval";
        $data['page_description'] = "Fuel Tank Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fuel Tank Approvals ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Fueltank'] = $Fueltank;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Vehicle Approval';

        AuditReportsController::store('Vehicle Approvals', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.tanks_approvals')->with($data);

    }

    public function vehicleApprovals(Request $request, vehicle_maintenance $vehicle_maintenance)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        //return $results;

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'vehicleappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $vehicleID = $aValue[1];
                if (count($sValue) > 1) {
                    $status = $sValue[1];
                } else $status = $sValue[0];
                $vehID = $vehicleID;
                $vehicle_maintenance->updateOrCreate(['id' => $vehID], ['status' => $status]);
            }
        }
// Reject Reason
        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'declined_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                if ($sUnit == 'declined' && !empty($sValue)) {
                    if (empty($sValue)) $sValue = $sReasonToReject;

                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['status' => 3]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['reject_reason' => $sValue]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['reject_timestamp' => time()]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['rejector_id' => Auth::user()->person->id]);
                    // $vehicle_maintenance->where('id',$iID)->update(['status' => 3],['reject_reason' => $sValue],['reject_timestamp' => time()]);
                }
            }
        }
        $sReasonToReject = '';
        AuditReportsController::store('Vehicle Management', 'Approve Vehicle ', "Vehicle has been Approved", 0);
        return back();
    }
}
