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
use App\ContactCompany;
use App\general_cost;
use App\fleet_fillingstation;
use App\module_access;
use App\DivisionLevelFive;
use App\vehicle_insurance;
use App\module_ribbons;
Use App\vehicle_serviceDetails;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VehicleFleetController extends Controller
{
    public function document(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');
        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
       ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;

            $vehicleDocumets = DB::table('vehicle_documets')
                ->select('vehicle_documets.*')
                ->orderBy('vehicle_documets.id')
                ->get();

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['safe'] = $safe;
            $data['employees'] = $employees;
            $data['vehicleDocumets'] = $vehicleDocumets;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.document')->with($data);
        } else
            return back();

    }

    public function contracts(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehicleDocumets = DB::table('vehicle_documets')
                ->select('vehicle_documets.*')
                ->orderBy('vehicle_documets.id')
                ->get();

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['safe'] = $safe;
            $data['employees'] = $employees;
            $data['vehicleDocumets'] = $vehicleDocumets;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.contracts')->with($data);
        } else
            return back();

    }

    public function addnotes(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
			$vehiclenotes = DB::table('notes')
			->select('notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
			->leftJoin('hr_people', 'notes.captured_by', '=', 'hr_people.id')
            		->orderBy('notes.id')
			->get();
		
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['safe'] = $safe;
            $data['employees'] = $employees;
            $data['vehiclenotes'] = $vehiclenotes;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.notes')->with($data);
        } else
            return back();
    }

    public function reminders(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;


        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $reminders = DB::table('vehicle_reminders')
                ->select('vehicle_reminders.*')
                ->orderBy('vehicle_reminders.id')
                ->get();


            //return $vehicleDocumets;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['reminders'] = $reminders;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.reminders')->with($data);
        } else
            return back();
    }

    public function addreminder(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $startdate = $SysData['start_date'] = str_replace('/', '-', $SysData['start_date']);
        $startdate = $SysData['start_date'] = strtotime($SysData['start_date']);

        $enddate = $SysData['end_date'] = str_replace('/', '-', $SysData['end_date']);
        $enddate = $SysData['end_date'] = strtotime($SysData['end_date']);

        $reminders = new reminders();
        $reminders->name = $SysData['name'];
        $reminders->description = $SysData['description'];
        $reminders->start_date = $startdate;
        $reminders->end_date = $enddate;
        $reminders->vehicleID = $SysData['valueID'];
        $reminders->status = 1;
        $reminders->save();

        return response()->json();

    }

    public function editreminder(Request $request, reminders $reminder)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $startdate = $SysData['start_date'] = str_replace('/', '-', $SysData['start_date']);
        $startdate = $SysData['start_date'] = strtotime($SysData['start_date']);

        $enddate = $SysData['end_date'] = str_replace('/', '-', $SysData['end_date']);
        $enddate = $SysData['end_date'] = strtotime($SysData['end_date']);

        $reminders->name = $SysData['name'];
        $reminders->description = $SysData['description'];
        $reminders->start_date = $startdate;
        $reminders->end_date = $enddate;
        $reminders->vehicleID = $SysData['valueID'];
        $reminders->status = 1;
        $reminders->update();

        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function reminderAct(Request $request, reminders $reminder)
    {
        if ($reminder->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $reminder->status = $stastus;
        $reminder->update();
        return back();
    }

    public function viewGeneralCost(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;


        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $generalcost = DB::table('vehicle_generalcosts')
                ->select('vehicle_generalcosts.*', 'hr_people.first_name as first_name', 'hr_people.surname as surname')
                ->leftJoin('hr_people', 'vehicle_generalcosts.person_esponsible', '=', 'hr_people.id')
                ->orderBy('vehicle_generalcosts.id')
                ->get();


            //return $vehicleDocumets;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['costtype'] = $costtype;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['generalcost'] = $generalcost;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewGeneralcost')->with($data);
        } else
            return back();
    }

    public function addcosts(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $date = $SysData['date'] = str_replace('/', '-', $SysData['date']);
        $date = $SysData['date'] = strtotime($SysData['date']);

        $generalcost = new general_cost();
        $generalcost->date = $date;
        $generalcost->document_number = $SysData['document_number'];
        $generalcost->supplier_name = $SysData['supplier_name'];
        $generalcost->cost_type = $SysData['cost_type'];
        $generalcost->cost = $SysData['cost'];
        $generalcost->litres = $SysData['litres'];
        $generalcost->description = $SysData['description'];
        $generalcost->person_esponsible = $SysData['person_esponsible'];
        $generalcost->vehicleID = $SysData['valueID'];
        $generalcost->save();

        return response()->json();

    }

    public function editcosts(Request $request, general_cost $costs)
    {

        $this->validate($request, [
            'date' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $date = $SysData['date'] = str_replace('/', '-', $SysData['date']);
        $date = $SysData['date'] = strtotime($SysData['date']);

        $costs->date = $date;
        $costs->document_number = $SysData['document_number'];
        $costs->supplier_name = $SysData['supplier_name'];
        $costs->cost_type = $SysData['cost_type'];
        $costs->cost = $SysData['cost'];
        $costs->litres = $SysData['litres'];
        $costs->description = $SysData['description'];
        $costs->person_esponsible = $SysData['person_esponsible'];
        $costs->vehicleID = $SysData['valueID'];
        $costs->update();
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function deletecosts(general_cost $costs)
    {

        $costs->delete();

        AuditReportsController::store('Vehicle Management', 'document  Deleted', "document has been deleted", 0);
        return back();
        //return redirect('/vehicle_management/general_cost/$maintenance->id');
    }

    public function viewWarranties(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id','asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehiclewarranties = DB::table('vehicle_warranties')
                ->select('vehicle_warranties.*')
                ->orderBy('vehicle_warranties.id')
                ->get();


            //return $vehicleDocumets;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['ContactCompany'] = $ContactCompany;
            $data['name'] = $name;
            $data['costtype'] = $costtype;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclewarranties'] = $vehiclewarranties;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewWarranties')->with($data);
        } else
            return back();
    }

     public function addwarranty(Request $request){
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        // $currentDate = time();

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $Vehiclewarranties = new vehicle_warranties($SysData);
        $Vehiclewarranties->exp_date = $Expdate;
        $Vehiclewarranties->inception_date = $inceptiondate ;
        $Vehiclewarranties->status = 1;
        $Vehiclewarranties->save();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $Vehiclewarranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $Vehiclewarranties->document = $fileName;
                $Vehiclewarranties->update();
            }
        }

        return response()->json();

    }

     public function warrantyAct(Request $request, vehicle_warranties $warranties)
    {
        if ($warranties->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $warranties->status = $stastus;
        $warranties->update();
        return back();
    }

     public function editwarranty(Request $request, vehicle_warranties $warranties)
    {

        $this->validate($request, [
            'date' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $warranties = new vehicle_warranties($SysData);
        $warranties->exp_date = $Expdate;
        $warranties->inception_date = $inceptiondate ;
        $warranties->status = 1;
        $warranties->update();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $warranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $warranties->document = $fileName;
                $warranties->update();
            }
        }
       AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function viewInsurance(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id','asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehicleinsurance = DB::table('vehicle_insurance')
                ->select('vehicle_insurance.*', 'contact_companies.name as companyName')
                ->leftJoin('contact_companies', 'vehicle_insurance.service_provider', '=', 'contact_companies.id')
                ->orderBy('vehicle_insurance.id')
                ->get();


            //return $vehicleinsurance;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['ContactCompany'] = $ContactCompany;
            $data['name'] = $name;
            $data['costtype'] = $costtype;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehicleinsurance'] = $vehicleinsurance;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewInsuarance')->with($data);
        } else
            return back();
    }

    public function addpolicy(Request $request){
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $Vehiclewarranties = new vehicle_insurance($SysData);
       // $Vehiclewarranties->inception_date = $inceptiondate ;
        $Vehiclewarranties->registration = 1;
        $Vehiclewarranties->status = 1;
        $Vehiclewarranties->save();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $Vehiclewarranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $Vehiclewarranties->document = $fileName;
                $Vehiclewarranties->update();
            }
        }

        return response()->json();

    }

     public function policyAct(Request $request, vehicle_insurance $policy)
    {
        if ($policy->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $policy->status = $stastus;
        $policy->update();
        return back();
    }

     public function edit_policy(Request $request, vehicle_insurance $warranties)
    {

        $this->validate($request, [
            'date' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $Vehiclewarranties = new vehicle_insurance($SysData);
        $Vehiclewarranties->inception_date = $inceptiondate ;
        $Vehiclewarranties->registration = 1;
        $Vehiclewarranties->status = 1;
        $Vehiclewarranties->update();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $Vehiclewarranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $Vehiclewarranties->document = $fileName;
                $Vehiclewarranties->update();
            }
        }


       AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function viewServiceDetails(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id','asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehicleserviceDetails = DB::table('vehicle_serviceDetails')
                ->select('vehicle_serviceDetails.*')
                ->orderBy('vehicle_serviceDetails.id')
                ->get();


            //return $vehicleinsurance;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['ContactCompany'] = $ContactCompany;
            $data['name'] = $name;
            $data['costtype'] = $costtype;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehicleserviceDetails'] = $vehicleserviceDetails;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewServiceDetails')->with($data);
        } else
            return back();
    }


    public function addServiceDetails(Request $request){
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $dateserviced = $SysData['date_serviced'] = str_replace('/', '-', $SysData['date_serviced']);
        $dateserviced = $SysData['date_serviced'] = strtotime($SysData['date_serviced']);

        $nxtservicedate = $SysData['nxt_service_date'] = str_replace('/', '-', $SysData['nxt_service_date']);
        $nxtservicedate = $SysData['nxt_service_date'] = strtotime($SysData['nxt_service_date']);

        $serviceDetails = new vehicle_serviceDetails($SysData);
        $serviceDetails->date_serviced = $dateserviced ;
        $serviceDetails->nxt_service_date = $nxtservicedate ;
        $serviceDetails->save();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $serviceDetails->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $serviceDetails->document = $fileName;
                $serviceDetails->update();
            }
        }

          //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $serviceDetails->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $serviceDetails->document1 = $fileName;
                $serviceDetails->update();
            }
        }

        return response()->json();

    }

    public function editservicedetails(Request $request, vehicle_serviceDetails $details)
    {

        $this->validate($request, [
            'date' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $dateserviced = $SysData['date_serviced'] = str_replace('/', '-', $SysData['date_serviced']);
        $dateserviced = $SysData['date_serviced'] = strtotime($SysData['date_serviced']);

        $nxtservicedate = $SysData['nxt_service_date'] = str_replace('/', '-', $SysData['nxt_service_date']);
        $nxtservicedate = $SysData['nxt_service_date'] = strtotime($SysData['nxt_service_date']);

        //$details = new vehicle_serviceDetails($SysData);
        $details->date_serviced = $dateserviced ;
        $details->nxt_service_date = $nxtservicedate ;
        $details->update();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $details->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $details->document = $fileName;
                $details->update();
            }
        }

          //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $details->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $details->document1 = $fileName;
                $details->update();
            }
        }


       AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function viewFines(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id','asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehiclefines = DB::table('vehicle_fines')
                ->select('vehicle_fines.*')
                ->orderBy('vehicle_fines.id')
                ->get();


            //return $vehicleinsurance;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['ContactCompany'] = $ContactCompany;
            $data['name'] = $name;
            $data['costtype'] = $costtype;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclefines'] = $vehiclefines;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewVehicleFines')->with($data);
        } else
            return back();
    }

    public function addvehiclefines(Request $request){
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
      
        $timeOfFine = $SysData['time_of_fine'] = strtotime($SysData['time_of_fine']);

        $dateOfFine = $SysData['date_of_fine'] = str_replace('/', '-', $SysData['date_of_fine']);
        $dateOfFine = $SysData['date_of_fine'] = strtotime($SysData['date_of_fine']);

        $courtDate = $SysData['court_date'] = str_replace('/', '-', $SysData['court_date']);
        $courtDate = $SysData['court_date'] = strtotime($SysData['court_date']);

        $paidDate = $SysData['paid_date'] = str_replace('/', '-', $SysData['paid_date']);
        $paidDate = $SysData['paid_date'] = strtotime($SysData['paid_date']);

        $vehicle_fines = new vehicle_fines($SysData);
        $vehicle_fines->date_captured = $currentDate ;
        $vehicle_fines->time_of_fine = $timeOfFine ;
        $vehicle_fines->date_of_fine = $dateOfFine ;
        $vehicle_fines->court_date = $courtDate ;
        $vehicle_fines->paid_date = $paidDate ;
        $vehicle_fines->vehicleID =  $SysData['valueID'];

        $vehicle_fines->save();

         //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $vehicle_fines->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $vehicle_fines->document = $fileName;
                $vehicle_fines->update();
            }
        }

          //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $vehicle_fines->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $vehicle_fines->document1 = $fileName;
                $vehicle_fines->update();
            }
        }

        return response()->json();

    }



}
