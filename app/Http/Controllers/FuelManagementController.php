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
use App\fleet_fillingstation;
use App\vehiclemake;
use App\FueltankPrivateUse;
use App\ContactCompany;
use App\vehicle_fuel_log;
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

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['employees'] = $employees;
        $data['Fueltanks'] = $Fueltanks;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.fueltanks')->with($data);

    }

    public function Addfueltank(Request $request)
    {
        $this->validate($request, [
            'tank_capacity' => 'required',
            'tank_name' => 'required',
            'tank_manager' => 'required',

        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $Fueltanks = new Fueltanks();
        //convert literes to number

        $tankcapacity = $FueltankData['tank_capacity'] = str_replace(',', '', $FueltankData['tank_capacity']);
        $tankcapacity = $FueltankData['tank_capacity'] = str_replace('. 00', '', $FueltankData['tank_capacity']);

        $currentlitres = $FueltankData['current_fuel_litres'] = str_replace('. 00', '', $FueltankData['current_fuel_litres']);

        $Fueltanks->division_level_1 = !empty($FueltankData['division_level_1']) ? $FueltankData['division_level_1'] : 0;
        $Fueltanks->division_level_2 = !empty($FueltankData['division_level_2']) ? $FueltankData['division_level_2'] : 0;
        $Fueltanks->division_level_3 = !empty($FueltankData['division_level_3']) ? $FueltankData['division_level_3'] : 0;
        $Fueltanks->division_level_4 = !empty($FueltankData['division_level_4']) ? $FueltankData['division_level_4'] : 0;
        $Fueltanks->division_level_5 = !empty($FueltankData['division_level_5']) ? $FueltankData['division_level_5'] : 0;
        $Fueltanks->tank_name = $FueltankData['tank_name'];
        $Fueltanks->tank_location = $FueltankData['tank_location'];
        $Fueltanks->tank_description = $FueltankData['tank_description'];
        $Fueltanks->tank_capacity = $tankcapacity;
        $Fueltanks->tank_manager = !empty($FueltankData['tank_manager']) ? $FueltankData['tank_manager'] : 0;
        $Fueltanks->current_fuel_litres = 0;
        $Fueltanks->available_litres =  0;
        $Fueltanks->status = 1;
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

        $tankcapacity = $FueltankData['tank_capacity'] = str_replace(',', '', $FueltankData['tank_capacity']);
        $tankcapacity = $FueltankData['tank_capacity'] = str_replace('. 00', '', $FueltankData['tank_capacity']);

        //convert literes to number


        $Fueltanks->division_level_1 = !empty($FueltankData['division_level_1']) ? $FueltankData['division_level_1'] : 0;
        $Fueltanks->division_level_2 = !empty($FueltankData['division_level_2']) ? $FueltankData['division_level_2'] : 0;
        $Fueltanks->division_level_3 = !empty($FueltankData['division_level_3']) ? $FueltankData['division_level_3'] : 0;
        $Fueltanks->division_level_4 = !empty($FueltankData['division_level_4']) ? $FueltankData['division_level_4'] : 0;
        $Fueltanks->division_level_5 = !empty($FueltankData['division_level_5']) ? $FueltankData['division_level_5'] : 0;
        $Fueltanks->tank_name = $FueltankData['tank_name'];
        $Fueltanks->tank_location = $FueltankData['tank_location'];
        $Fueltanks->tank_description = $FueltankData['tank_description'];
        $Fueltanks->tank_capacity = $tankcapacity;
        $Fueltanks->tank_manager = !empty($FueltankData['tank_manager']) ? $FueltankData['tank_manager'] : 0;
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
        $this->validate($request, [

        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

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
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['ID'] = $ID;
        $data['company'] = $company;
        $data['Department'] = $Department;
        $data['employees'] = $employees;
        $data['fuel'] = $fuel;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Viewtank')->with($data);

    }

    public function incoming(Request $request, Fueltanks $tank)
    {
        $this->validate($request, [

        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $ID = $tank->id;
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $ContactCompany = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        
        $Fueltank = DB::table('fuel_tanks')
            ->select('fuel_tanks.*','fuel_tank_topUp.*' ,'division_level_fives.name as Supplier')
            ->leftJoin('fuel_tank_topUp', 'fuel_tanks.id', '=', 'fuel_tank_topUp.tank_id')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->orderBy('fuel_tank_topUp.id')
            ->where('fuel_tank_topUp.tank_id', $ID)
            ->get();

        $topUpStatus = array(1 => 'Incoming', 2 => '= Outgoing', 3 => 'Private Usage');

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['topUpStatus'] = $topUpStatus;
        $data['name'] = $name;
        $data['ContactCompany'] = $ContactCompany;
        $data['employees'] = $employees;
        $data['tank'] = $tank;
        $data['ID'] = $ID;
        $data['Fueltank'] = $Fueltank;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.tank_results')->with($data);
    }

    public function outgoing(Request $request, Fueltanks $tank)
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

        $FueltankTopUpwhere = FueltankTopUp::where('tank_id', $ID)->orderBy('id', 'desc')->get();

        $FueltankPrivateUse = FueltankPrivateUse::where('status', 1)->orderBy('id', 'desc')->get();

        $ContactCompany = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $Fueltank = DB::table('fuel_tank_topUp')
            ->select('fuel_tank_topUp.*','fuel_tanks.*' ,'division_level_fives.name as Supplier')
            ->leftJoin('fuel_tanks', 'fuel_tank_topUp.tank_id', '=', 'fuel_tanks.id')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->orderBy('fuel_tank_topUp.id')
            ->where('fuel_tank_topUp.tank_id', $ID)
            ->get();

        $topUpStatus = array(1 => 'Incoming', 2 => '= Outgoing', 3 => 'Private Usage');

        $current = DB::table('fuel_tanks')->where('id', $ID)->pluck('current_fuel_litres')->first();

        $FueltankTopUp = FueltankTopUp::orderBy('id', 'desc')->get();
        $Fueltanks = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->first();

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['topUpStatus'] = $topUpStatus;
        $data['name'] = $name;
        $data['current'] = $current;
        $data['ContactCompany'] = $ContactCompany;
        $data['employees'] = $employees;
        $data['tank'] = $tank;
        $data['ID'] = $ID;
        $data['Fueltank'] = $Fueltank;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.outgoingtank_results')->with($data);
    }

    public function both(Request $request, Fueltanks $tank)
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

        $FueltankTopUpwhere = FueltankTopUp::where('tank_id', $ID)->orderBy('id', 'desc')->get();

        $FueltankPrivateUse = FueltankPrivateUse::where('status', 1)->orderBy('id', 'desc')->get();

        $ContactCompany = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $Fueltank = DB::table('fuel_tanks')
            ->select('fuel_tanks.*','fuel_tank_topUp.*' ,'division_level_fives.name as Supplier')
            ->leftJoin('fuel_tank_topUp', 'fuel_tanks.id', '=', 'fuel_tank_topUp.tank_id')
            ->leftJoin('division_level_fives', 'fuel_tanks.division_level_5', '=', 'division_level_fives.id')
            ->orderBy('fuel_tank_topUp.id')
            ->where('fuel_tank_topUp.tank_id', $ID)
            ->where('fuel_tank_topUp.status', 2)
            ->get();

        $topUpStatus = array(1 => 'Incoming', 2 => '= Outgoing', 3 => 'Private Usage');

        $current = DB::table('fuel_tanks')->where('id', $ID)->pluck('current_fuel_litres')->first();
        
        $FueltankTopUp = FueltankTopUp::orderBy('id', 'desc')->get();
        $Fueltanks = Fueltanks::where('id', $ID)->orderBy('id', 'desc')->first();

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " Fuel Management";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['topUpStatus'] = $topUpStatus;
        $data['name'] = $name;
        $data['current'] = $current;
        $data['ContactCompany'] = $ContactCompany;
        $data['employees'] = $employees;
        $data['tank'] = $tank;
        $data['ID'] = $ID;
        $data['Fueltank'] = $Fueltank;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.bothtank_results')->with($data);
    }


    public function TanktopUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // $this->validate($request, [
            'supplier_id' => 'required',
            'document_no' => 'required|unique:fuel_tank_topUp,document_no',
            'document_date' => 'required',
            'reading_before_filling' => 'required',
            'topup_date' => 'required',
            'cost_per_litre' => 'required',
            'received_by' => 'required',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $validator->after(function ($validator) use ($request) {

            $CurrentAmount = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('current_fuel_litres')->first();
            $TankCapacity = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('tank_capacity')->first();
            $tank_capacity = 'tank_capacity';
            $NewAmount = $CurrentAmount + $FueltankData['litres'];

            if ($NewAmount > $TankCapacity) {
                $validator->errors()->add($tank_capacity, 'Error: Cannot exceed tank capacity. Available capacity:' . ($TankCapacity - $CurrentAmount) . " litres");
            }
        });
        #Type 1 = incoming , Type 2 = Outgoing Type 3 = Private Usage

        $Fueltanks = Fueltanks::where('id', $FueltankData['tank_id'])->orderBy('id', 'desc')->first();
        $CurrentAmount = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('current_fuel_litres')->first();
        $TankCapacity = DB::table('fuel_tanks')->where('id', $FueltankData['tank_id'])->pluck('tank_capacity')->first();

        $NewAmount = $CurrentAmount + $FueltankData['litres'];

        $totalcost = $FueltankData['total_cost'] = str_replace(',', '', $FueltankData['total_cost']);
        $totalcost = $FueltankData['total_cost'] = str_replace('. 00', '', $FueltankData['total_cost']);

        $topupdate = $FueltankData['topup_date'] = str_replace('/', '-', $FueltankData['topup_date']);
        $topupdate = $FueltankData['topup_date'] = strtotime($FueltankData['topup_date']);

        $documentdate = $FueltankData['document_date'] = str_replace('/', '-', $FueltankData['document_date']);
        $documentdate = $FueltankData['document_date'] = strtotime($FueltankData['document_date']);

        $topUp = new FueltankTopUp();
        $topUp->supplier_id = !empty($FueltankData['supplier_id']) ? $FueltankData['supplier_id'] : 0;
        $topUp->document_no = $FueltankData['document_no'];
        $topUp->document_date = $documentdate;
        $topUp->topup_date = $topupdate;
        $topUp->type = 1; //Incoming
        $topUp->reading_before_filling = $CurrentAmount;
        $topUp->reading_after_filling = $NewAmount;
        $topUp->litres = $FueltankData['litres'];
        $topUp->cost_per_litre = $FueltankData['cost_per_litre'];
        $topUp->total_cost = $totalcost;
        $topUp->description = $FueltankData['description'];
        $topUp->received_by = !empty($FueltankData['received_by']) ? $FueltankData['received_by'] : 0;
        $topUp->captured_by = $loggedInEmplID = Auth::user()->person->id;
        $topUp->tank_id = !empty($FueltankData['tank_id']) ? $FueltankData['tank_id'] : 0;
        $topUp->status = 2;
        $topUp->save();

        AuditReportsController::store('Fleet Management', 'Fuel Tank Top Up', "Accessed By User", 0);
        return response()->json();

    }


    public function TankprivateUse(Request $request)
    {
         $this->validate($request, [
            'document_no' => 'required|unique:fuel_tank_topUp,document_no',
            'documents_date' => 'required',
            'usage_date' => 'required',
            'received_by' => 'required',
            'description' => 'required',
            'registration_number' => 'required',
            'make_or_model' => 'required',
        ]);

        $FueltankData = $request->all();
        unset($FueltankData['_token']);

		#Type 1 = incoming , Type 2 = Outgoing Type 3 = Private Usage
        $topupdate = $FueltankData['usage_date'] = str_replace('/', '-', $FueltankData['usage_date']);
        $topupdate = $FueltankData['usage_date'] = strtotime($FueltankData['usage_date']);

        $documentdate = $FueltankData['documents_date'] = str_replace('/', '-', $FueltankData['documents_date']);
        $documentdate = $FueltankData['documents_date'] = strtotime($FueltankData['documents_date']);

        $topUp = new FueltankTopUp();
        $topUp->document_no = $FueltankData['document_no'];
        $topUp->document_date = $documentdate;
        $topUp->topup_date = $topupdate;
        $topUp->type = 3; //outgoing
		$topUp->make_or_model = $FueltankData['make_or_model'];
        $topUp->registration_number = $FueltankData['registration_number'];
        $topUp->litres = $FueltankData['litres'];
        $topUp->description = $FueltankData['description'];
        $topUp->received_by = !empty($FueltankData['received_by']) ? $FueltankData['received_by'] : 0;
        $topUp->captured_by = $loggedInEmplID = Auth::user()->person->id;
        $topUp->tank_id = !empty($FueltankData['tank_id']) ? $FueltankData['tank_id'] : 0;
        $topUp->status = 1;
        $topUp->save();
        
        AuditReportsController::store('Fleet Management', 'Fuel Tank Private Use', "Accessed By User", 0);
        return response()->json();
    }

    public function tank_approval(Request $request)
    {
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
        $data['page_description'] = " Fuel Management";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehiclemodel'] = $vehiclemodel;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Approvals';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.tanksearch')->with($data);
    }

    public function ApproveTank(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();
        $tank = DB::table('fuel_tanks')->get();

        $Approvals = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_details.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'fleet_fillingstation.name as Staion', 'fuel_tanks.tank_name as tankName')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.tank_name', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->orderBy('vehicle_fuel_log.id')
            ->get();

        $data['page_title'] = "Fuel Tank Inventory";
        $data['page_description'] = "Fuel Tank Details";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fuel Tank Inventory ', 'active' => 1, 'is_module' => 0]
        ];
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Approvals'] = $Approvals;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Vehicle Approval';

        AuditReportsController::store('Fleet Management', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.tank')->with($data);
    }

    public function search(Request $request)
    {
        $this->validate($request, [
        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);

        $FleetNo = $fuelData['fleet_no'];
        $vehicleType = $fuelData['vehicle_type'];
        $actionFrom = $actionTo = 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $servicestation = fleet_fillingstation::orderBy('id', 'desc')->get();
        $fueltank = Fueltanks::orderBy('id', 'desc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();

        $tankResults = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Statas', 'fuel_tank_topUp.*', 'contact_companies.name as Supplier', 'vehicle_fuel_log.id as fuelLogID', 'vehicle_details.*'
                , 'fuel_tanks.*', 'fuel_tanks.tank_name as tankName',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('fuel_tank_topUp', 'fuel_tanks.id', '=', 'fuel_tank_topUp.tank_id')
            ->leftJoin('contact_companies', 'fuel_tank_topUp.supplier_id', '=', 'contact_companies.id')//CONTACT COMPANY
            ->where(function ($query) use ($FleetNo) {
                if (!empty($FleetNo)) {
                    $query->where('fleet_number', 'ILIKE', "%$FleetNo%");
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
			->where('vehicle_fuel_log.tank_and_other', 1)
            ->get();

        $stationResukts = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as iStatus', 'vehicle_fuel_log.id as fuelLogID',
                'vehicle_details.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname',
                'fleet_fillingstation.name as Staion')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.captured_by', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->where(function ($query) use ($FleetNo) {
                if (!empty($FleetNo)) {
                    $query->where('fleet_number', 'ILIKE', "%$FleetNo%");
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
            ->where('vehicle_fuel_log.tank_and_other', 2)
            ->get();
        $status = array(1 => 'Incoming', 2 => '= Outgoing',);

        $booking = array(10 => "Pending Capturer Ceo Approval",
            4 => "Pending Manager Approval",
            1 => "Approved",
            14 => "Rejected");
        $data['page_title'] = "Fuel Search Details";
        $data['page_description'] = "Fuel Search Details";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fuel Search Details ', 'active' => 1, 'is_module' => 0]
        ];

        $data['employees'] = $employees;
        $data['servicestation'] = $servicestation;
        $data['fueltank'] = $fueltank;
        $data['booking'] = $booking;
        $data['status'] = $status;
        $data['stationResukts'] = $stationResukts;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['tankResults'] = $tankResults;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Approvals';

        AuditReportsController::store('Fleet Management', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.search')->with($data);
    }

    public function tankApproval(Request $request)
    {
        $this->validate($request, [

        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);

        $FleetNo = $fuelData['fleet_no'];
        $vehicleType = $fuelData['vehicle_type'];
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();
        $tank = DB::table('vehicle_fuel_log')->get();

        $actionFrom = $actionTo = 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $Approvals = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.status as Statas', 'fuel_tank_topUp.*', 'contact_companies.name as Supplier', 'vehicle_fuel_log.id as fuelLogID', 'vehicle_details.*', 'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'fleet_fillingstation.name as Staion', 'fuel_tanks.tank_name as tankName',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('fuel_tank_topUp', 'fuel_tanks.id', '=', 'fuel_tank_topUp.tank_id')
            ->leftJoin('contact_companies', 'fuel_tank_topUp.supplier_id', '=', 'contact_companies.id')//CONTACT COMPANY
            ->where(function ($query) use ($FleetNo) {
                if (!empty($FleetNo)) {
                    $query->where('fleet_number', 'ILIKE', "%$FleetNo%");
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
            ->where('vehicle_fuel_log.tank_and_other', 1)
            ->whereNotIn('vehicle_fuel_log.status', [1, 14])
            ->get();

        $data['page_title'] = "Fuel Tank Approval";
        $data['page_description'] = "Fuel Tank Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fuel Tank Approvals ', 'active' => 1, 'is_module' => 0]
        ];

        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Approvals'] = $Approvals;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Approvals';

        AuditReportsController::store('Fleet Management', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.tanks_approvals')->with($data);
    }

    public function other(Request $request)
    {
        $this->validate($request, [
        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();

        $FleetNo = $fuelData['fleet_no'];
        $vehicleType = $fuelData['vehicle_type'];
        $actionFrom = $actionTo = 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $Approvals = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'vehicle_fuel_log.id as fuelLogID', 'vehicle_details.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'fleet_fillingstation.name as Staion', 'fuel_tanks.tank_name as tankName')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'vehicle_fuel_log.vehicleID', '=', 'vehicle_details.id')
            ->where(function ($query) use ($FleetNo) {
                if (!empty($FleetNo)) {
                    $query->where('fleet_number', 'ILIKE', "%$FleetNo%");
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_type', $vehicleType);
                }
            })
            ->orderBy('vehicle_details.id')
            ->where('vehicle_fuel_log.tank_and_other', 2)
            ->whereNotIn('vehicle_fuel_log.status', [1, 14])
            ->get();

        $data['page_title'] = "Other Fuel Approvals";
        $data['page_description'] = "Other Fuel Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Other Fuel Approvals ', 'active' => 1, 'is_module' => 0]
        ];

        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Approvals'] = $Approvals;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fuel Approvals';

        AuditReportsController::store('Fleet Management', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FuelTanks.Tank Approvals.other_approvals')->with($data);
    }

    public function otherApproval(Request $request, vehicle_fuel_log $fuelLog)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
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
                    $status = $sValue[10];
                } else $status = $sValue[0];
                $vehID = $vehicleID;
                $fuelLog->updateOrCreate(['id' => $vehID], ['status' => $status]);
            }
        }
		// Reject Reason
        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'declined_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                if ($sUnit == 'declined' && !empty($sValue)) {
                    if (empty($sValue)) $sValue = $sReasonToReject;

                    $fuelLog->updateOrCreate(['id' => $iID], ['status' => 14]);
                    $fuelLog->updateOrCreate(['id' => $iID], ['reject_reason' => $sValue]);
                    $fuelLog->updateOrCreate(['id' => $iID], ['reject_timestamp' => time()]);
                    $fuelLog->updateOrCreate(['id' => $iID], ['rejector_id' => Auth::user()->person->id]);
                }
            }
        }
        $sReasonToReject = '';
        AuditReportsController::store('Fleet Management', 'Fuel Station Approval', "Fuel Station has been updated", 0);
        // return back();
        return redirect('/vehicle_management/tank_approval');
    }

    public function fueltankApproval(Request $request, vehicle_fuel_log $fuelLog)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'vehicleappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                // $fuelLogID = $aValue[1];
                $tankID = $aValue[1];
                //    // Calculations
                $TopUp = DB::table('vehicle_fuel_log')->orderBy('id', 'asc')->where('tank_name', $tankID)->get();
                // $Type = $TopUp->first()->type;
                $iLitres = $TopUp->first()->litres;
                $atank = DB::table('fuel_tanks')->orderBy('id', 'asc')->where('id', $tankID)->get();
                $tankcapacity = $atank->first()->tank_capacity;
                $CurrentAmount = $atank->first()->current_fuel_litres;

                $NewAmount = $CurrentAmount - $iLitres;
                if ($NewAmount > 0) { //Incoming
                    DB::table('fuel_tanks')
                        ->where('id', $tankID)
                        ->update(['current_fuel_litres' => $NewAmount]);
                }
                if (count($sValue) > 1) {
                    $status = $sValue[1];
                } else $status = $sValue[0];
                $tankID = $tankID;
                // return $status;
                DB::table('vehicle_fuel_log')
                    ->where('tank_name', $tankID)
                    ->update(['status' => 1]);
            }
        }
		// Reject Reason
        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'declined_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                if ($sUnit == 'declined' && !empty($sValue)) {
                    if (empty($sValue)) $sValue = $sReasonToReject;

                    $fuelLog->updateOrCreate(['tank_name' => $iID], ['status' => 14]);
                    $fuelLog->updateOrCreate(['tank_name' => $iID], ['reject_reason' => $sValue]);
                    $fuelLog->updateOrCreate(['tank_name' => $iID], ['reject_timestamp' => time()]);
                    $fuelLog->updateOrCreate(['tank_name' => $iID], ['rejector_id' => Auth::user()->person->id]);
                    // $vehicle_maintenance->where('id',$iID)->update(['status' => 3],['reject_reason' => $sValue],['reject_timestamp' => time()]);
                }
            }
        }
        $sReasonToReject = '';
        AuditReportsController::store('Fleet Management', 'Fuel Tank Approval', "Fuel status has been Updated", 0);
        return redirect('/vehicle_management/tank_approval');
    }
}
