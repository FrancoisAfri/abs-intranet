<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
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
use App\vehicle_insurance;
use App\module_ribbons;
Use App\vehicle_serviceDetails;
use App\ribbons_access;
use App\service_station;
use App\Fueltanks;
use App\fleet_documentType;
use App\vehicle_config;
use App\ContactPerson;
use App\vehicle;
use App\FueltankTopUp;
use App\vehicle_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Illuminate\Support\Facades\Storage;

class VehicleFleetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function document(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $documentTypes = fleet_documentType::where('status', 1)->orderBy('name')->get();
        ################## WELL DETAILS ###############

        $ID = $maintenance->id;
     
        $currentTime = time();

        $vehicleDocumets = vehicle_documets::where(['vehicleID' => $ID])->orderBy('vehicle_documets',$ID)->get();
		if (!empty($vehicleDocumets)) $vehicleDocumets = $vehicleDocumets->load('documentType');
		
        $data['currentTime'] = $currentTime;
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['employees'] = $employees;
        $data['vehicleDocumets'] = $vehicleDocumets;
        $data['documentTypes'] = $documentTypes;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.document')->with($data);
    }

    public function contracts(vehicle_maintenance $maintenance)
    {

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############


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


        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;

        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Contract Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.contracts')->with($data);
    }

    public function viewnotes(vehicle_maintenance $maintenance)
    {


        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $ID = $maintenance->id;
        $vehiclenotes = DB::table('notes')
            ->select('notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'notes.captured_by', '=', 'hr_people.id')
            ->orderBy('notes.id')
            ->where('vehicleID', $ID)
            ->get();

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
		
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['employees'] = $employees;
        $data['vehiclenotes'] = $vehiclenotes;
		$data['name'] = $name;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management ', 'Add Notes Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.notes')->with($data);
    }

    public function reminders(vehicle_maintenance $maintenance)
    {

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $ID = $maintenance->id;

        $reminders = DB::table('vehicle_reminders')
            ->select('vehicle_reminders.*')
            ->orderBy('vehicle_reminders.id')
            ->where('vehicleID', $ID)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['IssuedTo'] = $IssuedTo;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['reminders'] = $reminders;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Add Vehicle Reminders Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.reminders')->with($data);
    }

    public function addreminder(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:vehicle_reminders,name',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

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
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $startdate = $SysData['startdate'] = str_replace('/', '-', $SysData['startdate']);
        $startdate = $SysData['startdate'] = strtotime($SysData['startdate']);

        $enddate = $SysData['enddate'] = str_replace('/', '-', $SysData['enddate']);
        $enddate = $SysData['enddate'] = strtotime($SysData['enddate']);

        $reminder->name = $SysData['name'];
        $reminder->description = $SysData['description'];
        $reminder->start_date = $startdate;
        $reminder->end_date = $enddate;
        $reminder->vehicleID = $SysData['valueID'];
        $reminder->status = 1;
        $reminder->update();

        AuditReportsController::store('Fleet Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
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

    public function deletereminder(Request $request, reminders $reminder)
    {
        $reminder->delete();

        AuditReportsController::store('Fleet Management', 'reminder Type Deleted', "Document Type has been deleted", 0);
        return back();
    }


    public function viewGeneralCost(vehicle_maintenance $maintenance)
    {

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();


        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');


        $ID = $maintenance->id;
        $generalcost = DB::table('vehicle_generalcosts')
            ->select('vehicle_generalcosts.*', 'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_generalcosts.person_esponsible', '=', 'hr_people.id')
            ->orderBy('vehicle_generalcosts.id')
            ->where('vehicleID', $ID)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['name'] = $name;
        $data['costtype'] = $costtype;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['generalcost'] = $generalcost;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewGeneralcost')->with($data);
    }

    public function addcosts(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            //'document_number' => 'required|unique:general_cost,document_number',
            'supplier_name' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $date = $SysData['date'] = str_replace('/', '-', $SysData['date']);
        $date = $SysData['date'] = strtotime($SysData['date']);

        $generalcost = new general_cost();
        $generalcost->date = $date;
        $generalcost->document_number = $SysData['document_number'];
        $generalcost->supplier_name = $SysData['supplier_name'];
        $generalcost->cost_type = !empty($SysData['cost_type']) ? $SysData['cost_type'] : 1;
        $generalcost->cost = $SysData['cost'];
        $generalcost->litres = $SysData['litres'];
        $generalcost->description = $SysData['description'];
        $generalcost->person_esponsible = !empty($SysData['person_esponsible']) ? $SysData['person_esponsible'] : 1;
        $generalcost->vehicleID = $SysData['valueID'];
        $generalcost->vehiclebookingID = !empty($SysData['vehiclebookingID']) ? $SysData['vehiclebookingID'] : 0;
        $generalcost->save();

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();

    }

    public function editcosts(Request $request, general_cost $costs)
    {

        $this->validate($request, [
            'date' => 'required',
            'document_number' => 'required|unique:general_cost,document_number',
            'supplier_name' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $date = $SysData['date'] = str_replace('/', '-', $SysData['date']);
        $date = $SysData['date'] = strtotime($SysData['date']);

        $costs->date = $date;
        $costs->document_number = $SysData['document_number'];
        $costs->supplier_name = $SysData['supplier_name'];
        $costs->cost_type = !empty($SysData['cost_type']) ? $SysData['cost_type'] : 1;
        $costs->cost = $SysData['cost'];
        $costs->litres = $SysData['litres'];
        $costs->description = $SysData['description'];
        $costs->person_esponsible = !empty($SysData['person_esponsible']) ? $SysData['person_esponsible'] : 1;
        $costs->update();
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function deletecosts(general_cost $costs)
    {

        $costs->delete();

        AuditReportsController::store('Fleet Management', 'document  Deleted', "document has been deleted", 0);
        return back();

    }

    public function viewWarranties(vehicle_maintenance $maintenance)
    {

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();


        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');


        $ID = $maintenance->id;
        $vehiclewarranties = DB::table('vehicle_warranties')
            ->select('vehicle_warranties.*','contacts_contacts.*', 'contact_companies.name as serviceprovider')
            ->leftJoin('contact_companies', 'vehicle_warranties.service_provider', '=', 'contact_companies.id')
            ->leftJoin('contacts_contacts', 'vehicle_warranties.contact_person', '=', 'contacts_contacts.id')
            ->orderBy('vehicle_warranties.id')
            ->where('vehicleID', $ID)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['ContactCompany'] = $ContactCompany;
        $data['name'] = $name;
        $data['costtype'] = $costtype;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclewarranties'] = $vehiclewarranties;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewWarranties')->with($data);
    }

    public function addwarranty(Request $request)
    {
        $this->validate($request, [
            'policy_no' => 'required|unique:vehicle_warranties,policy_no',
            
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $Vehiclewarranties = new vehicle_warranties($SysData);
        $Vehiclewarranties->exp_date = $Expdate;
        $Vehiclewarranties->inception_date = $inceptiondate;
        $Vehiclewarranties->status = 1;
        $Vehiclewarranties->vehicleID = $SysData['valueID'];
        $Vehiclewarranties->service_provider = !empty($SysData['company_id']) ? $SysData['company_id'] : 0;
        $Vehiclewarranties->contact_person = !empty($SysData['contact_person_id']) ? $SysData['contact_person_id'] : 0;
        $Vehiclewarranties->policy_no = $SysData['policy_no'];
        $Vehiclewarranties->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $Vehiclewarranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/warranty', $fileName);
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

            'policy_no' => 'required|unique:vehicle_warranties,policy_no',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inceptiondate'] = str_replace('/', '-', $SysData['inceptiondate']);
        $inceptiondate = $SysData['inceptiondate'] = strtotime($SysData['inceptiondate']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);


        $warranties->exp_date = $Expdate;
        $warranties->inception_date = $inceptiondate;
        $warranties->status = 1;
        $warranties->vehicleID = $SysData['valueID'];
        $warranties->service_provider = !empty($SysData['company_id']) ? $SysData['company_id'] : 0;
        $warranties->contact_person = !empty($SysData['contact_person_id']) ? $SysData['contact_person_id'] : 0;
        $warranties->policy_no = $SysData['policy_no'];
        $warranties->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $warranties->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/warranty', $fileName);
                //Update file name in the table
                $warranties->document = $fileName;
                $warranties->update();
            }
        }
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function viewInsurance(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');

        $ID = $maintenance->id;
        $vehicleinsurance = DB::table('vehicle_insurance')
            ->select('vehicle_insurance.*', 'contact_companies.name as companyName')
            ->leftJoin('contact_companies', 'vehicle_insurance.service_provider', '=', 'contact_companies.id')
            ->orderBy('vehicle_insurance.id')
            ->where('vehicleID', $ID)
            ->get();
        //return $vehicleinsurance;

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
        $data['ContactCompany'] = $ContactCompany;
        $data['name'] = $name;
        $data['costtype'] = $costtype;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehicleinsurance'] = $vehicleinsurance;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewInsuarance')->with($data);
    }

    public function addInsurance(Request $request)
    {
        $this->validate($request, [
            //'email'    => 'required|email|max:255',
            'policy_no' => 'required|unique:vehicle_insurance,policy_no',
            'address' => 'required',
            'inception_date' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);


        $insurance = new vehicle_insurance($SysData);
        $insurance->inception_date = $inceptiondate;
        $insurance->service_provider = !empty($SysData['company_id']) ? $SysData['company_id'] : 0;
        $insurance->contact_person = !empty($SysData['contact_person_id']) ? $SysData['contact_person_id'] : 0;
        $insurance->vehicleID = $SysData['valueID'];
        $insurance->policy_no = $SysData['policy_no'];
        $insurance->status = 1;
        $insurance->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $insurance->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/Insurance', $fileName);
                //Update file name in the table
                $insurance->document = $fileName;
                $insurance->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $insurance->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('Vehicle/Insurance', $fileName);
                //Update file name in the table
                $insurance->document1 = $fileName;
                $insurance->update();
            }
        }

        return response()->json();
    }


    public function editInsurance(Request $request, vehicle_insurance $policy)
    {
        $this->validate($request, [
            'policy_no' => 'required|unique:vehicle_insurance,policy_no',
            'address' => 'required',
            'inception_date' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $inceptiondate = $SysData['inception_date'] = str_replace('/', '-', $SysData['inception_date']);
        $inceptiondate = $SysData['inception_date'] = strtotime($SysData['inception_date']);

        $insurance->inception_date = $inceptiondate;
        $insurance->service_provider = !empty($SysData['company_id']) ? $SysData['company_id'] : 0;
        $insurance->contact_person = !empty($SysData['contact_person_id']) ? $SysData['contact_person_id'] : 0;
        $insurance->vehicleID = $SysData['valueID'];
        $insurance->policy_no = $SysData['policy_no'];
        $insurance->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $insurance->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/Insurance', $fileName);
                //Update file name in the table
                $insurance->document = $fileName;
                $insurance->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $insurance->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('Vehicle/Insurance', $fileName);
                //Update file name in the table
                $insurance->document1 = $fileName;
                $insurance->update();
            }
        }

        return response()->json();
    }

    public function InsuranceAct(Request $request, vehicle_insurance $policy)
    {
        if ($policy->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $policy->status = $stastus;
        $policy->update();
        return back();
    }

    public function viewServiceDetails(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $costtype = array(1 => 'Oil');


        $ID = $maintenance->id;
        $vehicleserviceDetails = DB::table('vehicle_serviceDetails')
            ->select('vehicle_serviceDetails.*')
            ->orderBy('vehicle_serviceDetails.id')
            ->where('vehicleID', $ID)
            ->get();

        //  return  $vehicleserviceDetails;

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['ContactCompany'] = $ContactCompany;
        $data['name'] = $name;
        $data['costtype'] = $costtype;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehicleserviceDetails'] = $vehicleserviceDetails;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewServiceDetails')->with($data);
    }


    public function addServiceDetails(Request $request)
    {
        $this->validate($request, [
            // 'invoice_number' => 'required_if:key,1',
            'invoice_number' => 'required|unique:vehicle_serviceDetails,invoice_number',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $dateserviced = $SysData['date_serviced'] = str_replace('/', '-', $SysData['date_serviced']);
        $dateserviced = $SysData['date_serviced'] = strtotime($SysData['date_serviced']);

        $nxtservicedate = $SysData['nxt_service_date'] = str_replace('/', '-', $SysData['nxt_service_date']);
        $nxtservicedate = $SysData['nxt_service_date'] = strtotime($SysData['nxt_service_date']);

        $serviceDetails = new vehicle_serviceDetails($SysData);
        $serviceDetails->date_serviced = $dateserviced;
        $serviceDetails->nxt_service_date = $nxtservicedate;
        $serviceDetails->vehicleID = $SysData['valueID'];
        $serviceDetails->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $serviceDetails->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('servicedetails/documents', $fileName);
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
                $request->file('documents1')->storeAs('Vehicle/servicedetails', $fileName);
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
            // 'date' => 'required',
            'invoice_number' => 'required|unique:vehicle_serviceDetails,invoice_number',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $dateserviced = $SysData['date_serviced'] = str_replace('/', '-', $SysData['date_serviced']);
        $dateserviced = $SysData['date_serviced'] = strtotime($SysData['date_serviced']);

        $nxtservicedate = $SysData['nxt_service_date'] = str_replace('/', '-', $SysData['nxt_service_date']);
        $nxtservicedate = $SysData['nxt_service_date'] = strtotime($SysData['nxt_service_date']);

        $details->date_serviced = $dateserviced;
        $details->nxt_service_date = $nxtservicedate;
        $details->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $details->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/servicedetails', $fileName);
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
                $request->file('documents1')->storeAs('Vehicle/servicedetails', $fileName);
                //Update file name in the table
                $details->document1 = $fileName;
                $details->update();
            }
        }


        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function viewFines(vehicle_maintenance $maintenance)
    {

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $fineType = array(1 => 'Speeding', 2 => 'Parking', 3 => 'Moving Violation', 4 => 'Expired Registration', 5 => 'No Drivers Licence', 6 => 'Other');

        $status = array(1 => 'Captured', 2 => 'Fine Queried', 3 => 'Fine Revoked', 4 => 'Fine Paid');

        $ID = $maintenance->id;
        $vehiclefines = DB::table('vehicle_fines')
            ->select('vehicle_fines.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_fines.driver', '=', 'hr_people.id')
            ->orderBy('vehicle_fines.id')
            ->where('vehicleID', $ID)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['ContactCompany'] = $ContactCompany;
        $data['name'] = $name;
        $data['status'] = $status;
        $data['fineType'] = $fineType;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclefines'] = $vehiclefines;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleFines')->with($data);
    }

    public function addvehiclefines(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
            'fine_ref' => 'required|unique:vehicle_fines,fine_ref',
        ]);
        $fineData = $request->all();
        unset($fineData['_token']);

        $vehicle_fines = new vehicle_fines($fineData);
        $currentDate = time();

        $dateOfFine = $fineData['date_of_fine'] = str_replace('/', '-', $fineData['date_of_fine']);
        $dateOfFine = $fineData['date_of_fine'] = strtotime($fineData['date_of_fine']);

        $courtDate = $fineData['court_date'] = str_replace('/', '-', $fineData['court_date']);
        $courtDate = $fineData['court_date'] = strtotime($fineData['court_date']);

        $paidDate = $fineData['paid_date'] = str_replace('/', '-', $fineData['paid_date']);
        $paidDate = $fineData['paid_date'] = strtotime($fineData['paid_date']);


        $timeOfFine = $fineData['time_of_fine'] = str_replace('/', '-', $fineData['time_of_fine']);
        $timeOfFine = $fineData['time_of_fine'] = strtotime($fineData['time_of_fine']);


        $vehicle_fines->date_captured = $currentDate;
        $vehicle_fines->time_of_fine = $timeOfFine;
        $vehicle_fines->date_of_fine = $dateOfFine;
        $vehicle_fines->court_date = $courtDate;
        $vehicle_fines->paid_date = $paidDate;
        $vehicle_fines->vehicleID = $fineData['valueID'];
        $vehicle_fines->fine_ref = $fineData['fine_ref'];
        $vehicle_fines->fine_type = !empty($fineData['fine_type']) ? $fineData['fine_type'] : 0;
        $vehicle_fines->driver = !empty($fineData['driver']) ? $fineData['driver'] : 0;
        $vehicle_fines->fine_status = !empty($fineData['fine_status']) ? $fineData['fine_status'] : 0;
        $vehicle_fines->vehiclebookingID = !empty($fineData['vehiclebookingID']) ? $fineData['vehiclebookingID'] : 0;
        $vehicle_fines->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $vehicle_fines->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/vehiclefines', $fileName);
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
                $request->file('documents1')->storeAs('Vehicle/vehiclefines', $fileName);
                //Update file name in the table
                $vehicle_fines->document1 = $fileName;
                $vehicle_fines->update();
            }
        }

        return response()->json();

    }


    public function edit_finesdetails(Request $request, vehicle_fines $fines)
    {

        $this->validate($request, [
            //'date' => 'required',
            'fine_ref' => 'required|unique:vehicle_fines,fine_ref',
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


        $fines->date_captured = $currentDate;
        $fines->time_of_fine = $timeOfFine;
        $fines->date_of_fine = $dateOfFine;
        $fines->court_date = $courtDate;
        $fines->paid_date = $paidDate;
        $fines->vehicleID = $SysData['valueID'];
        $fines->fine_ref = $SysData['fine_ref'];
        $fines->fine_type = !empty($SysData['fine_type']) ? $SysData['fine_type'] : 0;
        $fines->driver = !empty($SysData['driver']) ? $SysData['driver'] : 0;
        $fines->fine_status = !empty($SysData['fine_status']) ? $SysData['fine_status'] : 0;
        $fines->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $fines->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/vehiclefines', $fileName);
                //Update file name in the table
                $fines->document = $fileName;
                $fines->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('documents1')) {
            $fileExt = $request->file('documents1')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents1')->isValid()) {
                $fileName = $fines->id . "_documents." . $fileExt;
                $request->file('documents1')->storeAs('Vehicle/vehiclefines', $fileName);
                //Update file name in the table
                $fines->document1 = $fileName;
                $fines->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return back();
    }
    public function viewIncidents(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        //return $ContactCompany;

        $incidentType = incident_type::orderBy('id', 'asc')->get();
       
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $fineType = array(1 => 'Accident', 2 => 'Mechanical Fault', 3 => 'Electronic Fault', 4 => 'Damaged', 5 => 'Attempted Hi-jacking', 6 => 'Hi-jacking', 7 => 'Other');

        $status = array(1 => 'Minor', 2 => 'Major', 3 => 'Critical');

        $ID = $maintenance->id;
        $vehicleincidents = vehicle_incidents::
            select('vehicle_incidents.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname','incident_type.name as IncidintType')
            ->leftJoin('incident_type', 'vehicle_incidents.incident_type', '=', 'incident_type.id')
            ->leftJoin('hr_people', 'vehicle_incidents.reported_by', '=', 'hr_people.id')
            ->where('vehicleID', $ID)
            ->orderBy('vehicle_incidents.id')
            ->get();
       if (!empty($vehicleincidents))  $vehicleincidents = $vehicleincidents->load('incidentDoc');
	  // return $vehicleincidents;
	$vehicleCong = vehicle_config::orderBy('id', 'asc')->first();  
        
       // return $vehicleincidents;
        
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['incidentType'] = $incidentType;
        $data['ContactCompany'] = $ContactCompany;
        $data['vehicleCong'] = $vehicleCong;
        $data['name'] = $name;
        $data['status'] = $status;
        $data['fineType'] = $fineType;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehicleincidents'] = $vehicleincidents;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleIncidents')->with($data);
    }
    
    public function fixVehicle(vehicle_incidents $vehicle){
       // return $vehicle;
        
        // vehicle_fixed value is one wen the the vehicle has been fixed
        $vehicle->vehicle_fixed = 1;	
	$vehicle->update();
	AuditReportsController::store('MVehicle Incidents', "Vehicle Incidents Page Accessed", "Edited by User", 0);
	return back();
    }

    public function addvehicleincidents(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
            'claim_number' => 'required|unique:vehicle_incidents,claim_number',

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
		$vehicleCong = vehicle_config::orderBy('id', 'asc')->first();
        $dateofincident = $SysData['date_of_incident'] = str_replace('/', '-', $SysData['date_of_incident']);
        $dateofincident = $SysData['date_of_incident'] = strtotime($SysData['date_of_incident']);

        $vehicleincidents = new vehicle_incidents($SysData);
        $vehicleincidents->date_of_incident = $dateofincident;
        $vehicleincidents->vehicleID = $SysData['valueID'];
        $vehicleincidents->incident_type = !empty($SysData['incident_type']) ? $SysData['incident_type'] : 0;
        $vehicleincidents->severity = !empty($SysData['severity']) ? $SysData['severity'] : 0;
        $vehicleincidents->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $vehicleincidents->reported_by = !empty($SysData['reported_by']) ? $SysData['reported_by'] : 0;
        $vehicleincidents->vehiclebookingID = !empty($SysData['vehiclebookingID']) ? $SysData['vehiclebookingID'] : 0;
        $vehicleincidents->odometer_reading = !empty($SysData['odometer_reading']) ? $SysData['odometer_reading'] : 0;
        $vehicleincidents->hours_reading = !empty($SysData['hours_reading']) ? $SysData['hours_reading'] : 0;
        $vehicleincidents->vehicle_fixed =  0; 
        $vehicleincidents->save();

        # document
        $numFiles = $index = 0;
        $totalFiles = !empty($SysData['total_files']) ? $SysData['total_files'] : 0;
        $Extensions = array('pdf', 'docx', 'doc');

        $Files = isset($_FILES['document']) ? $_FILES['document'] : array();
        while ($numFiles != $totalFiles) {
            $index++;
            $Name = $request->name[$index];
            if (isset($Files['name'][$index]) && $Files['name'][$index] != '') {
                $fileName = $vehicleincidents->id . '_' . $Files['name'][$index];
                $Explode = array();
                $Explode = explode('.', $fileName);
                $ext = end($Explode);
                $ext = strtolower($ext);
                if (in_array($ext, $Extensions)) {
                    if (!is_dir("$vehicleCong->incidents_upload_directory")) mkdir("$vehicleCong->incidents_upload_directory", 0775);
                    move_uploaded_file($Files['tmp_name'][$index], "$vehicleCong->incidents_upload_directory".'/' . $fileName) or die('Could not move file!');

                    $document = new VehicleIncidentsDocuments($SysData);
                    $document->display_name = $Name;
                    $document->filename = $fileName;
                    $document->status = 1;
                    $vehicleincidents->addIncidentDocs($document);
                }
            }
            $numFiles++;
        }
        return response()->json();
    }

    public function editvehicleincidents(Request $request, vehicle_incidents $incident)
    {

        $this->validate($request, [
            //'date' => 'required',
            //'claim_number' => 'required|unique:vehicle_incidents,claim_number',
        ]);
        $IncuData = $request->all();
        unset($IncuData['_token']);

        $dateofincident = $IncuData['date_of_incident'] = str_replace('/', '-', $IncuData['date_of_incident']);
        $dateofincident = $IncuData['date_of_incident'] = strtotime($IncuData['date_of_incident']);

        $incident = new vehicle_incidents($IncuData);
        $incident->date_of_incident = $dateofincident;;
        $incident->vehicleID = $IncuData['valueID'];
        $incident->incident_type = !empty($SysData['incident_type']) ? $SysData['incident_type'] : 0;
        $incident->severity = !empty($SysData['severity']) ? $SysData['severity'] : 0;
        $incident->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $incident->reported_by = !empty($SysData['reported_by']) ? $SysData['reported_by'] : 0;
        $incident->odometer_reading = !empty($SysData['odometer_reading']) ? $SysData['odometer_reading'] : 0;
        $incident->hours_reading = !empty($SysData['hours_reading']) ? $SysData['hours_reading'] : 0;
        $incident->Update();


       // Upload supporting document
        if ($request->hasFile('documents')) {
           $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $incident->id . "_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/vehicleIncidents', $fileName);
                //Update file name in the table
                $incident->document = $fileName;
                $incident->update();
            }
        }
        return response()->json();

        AuditReportsController::store('Fleet Management', 'Fleet Incident Edited', "Edited By User", 0);
        return back();
    }

    public function viewOilLog(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>#################
        $fineType = array(1 => 'Accident', 2 => 'Mechanical Fault', 3 => 'Electronic Fault', 4 => 'Damaged', 5 => 'Attempted Hi-jacking', 6 => 'Hi-jacking', 7 => 'Other');

        $status = array(1 => 'Minor', 2 => 'Major', 3 => 'Critical');


        $ID = $maintenance->id;
        //return $ID;


        $vehicleoil_log = DB::table('vehicle_oil_log')
            ->select('vehicle_oil_log.*')
            ->orderBy('vehicle_oil_log.id')
            ->get();


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['ContactCompany'] = $ContactCompany;
        $data['name'] = $name;
        $data['status'] = $status;
        $data['fineType'] = $fineType;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehicleoil_log'] = $vehicleoil_log;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleIOilLog')->with($data);
    }


    public function viewFuelLog(Request $request, vehicle_maintenance $maintenance, $date = 0)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);

        // return  date('Y', $date);
        //return $date;

        $now = Carbon::now();

        $startExplode = explode('_', $date);
        $imonth = $startExplode[0];
        $command = (!empty($startExplode[1]) ? $startExplode[1] : 0);
        $iYear = (!empty($startExplode[2]) ? $startExplode[2] : 0);


        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();

        $Details = vehicle_detail::where('id', $maintenance->id)->first();
        $MetreType = $Details->metre_reading_type;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $servicestation = fleet_fillingstation::orderBy('id', 'desc')->get();
        $fueltank = Fueltanks::orderBy('id', 'desc')->get();

        $vehicle_config = vehicle_config::orderBy('id', 'desc')->get();
        $commands = $command;

        if ($commands === 0) {
            $imonth = $now->month;
            $iYear = $now->year;
        } elseif ($commands === 'p') {
            if ($imonth == 1) {
                $iYear = $iYear - 1;
                $imonth = 12;
            } else $imonth = $imonth - 1;


        } elseif ($commands === 'n') {
            if ($imonth == 12) {
                $iYear = $iYear + 1;
                $imonth = 1;
            } else $imonth = $imonth + 1;
        }

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        ###################>>>>>#################
        $fineType = array(1 => 'Accident', 2 => 'Mechanical Fault', 3 => 'Electronic Fault', 4 => 'Damaged', 5 => 'Attempted Hi-jacking', 6 => 'Hi-jacking', 7 => 'Other');

        $status = array(1 => 'Tank', 2 => 'Other');
        $transType = array(1 => 'Full Tank', 2 => 'Top Up');

        $vehiclefuel = DB::table('vehicle_fuel_log')->get();

        if (!empty($vehicle_fuel_log))
            $vehicle_fuel_log = 0;
        else
            $vehicle_fuel_log = vehicle_fuel_log::latest()->first();

        $datetaken = date('n');

        if ($imonth < 10) {
            $imonth = 0. . $imonth;
        } else $imonth = $imonth;

        $ID = $maintenance->id;
        $iTotalLitres = DB::table('vehicle_fuel_log')->where('vehicleID', $ID)->sum('litres');
        $sCurrency = DB::table('vehicle_fuel_log')->where('vehicleID', $ID)->sum('total_cost');


        $vehiclefuellog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'fleet_fillingstation.name as Staion', 'fuel_tanks.tank_name as tankName')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            ->orderBy('vehicle_fuel_log.id')
            ->where('vehicle_fuel_log.vehicleID', $ID)
            ->whereMonth('vehicle_fuel_log.created_at', '=', $imonth)// show record for this month
            ->whereYear('vehicle_fuel_log.created_at', '=', $iYear)// show record for this year
            // ->where('vehicle_fuel_log.status','!=', 1)
            ->get();

        //return $vehiclefuellog;


        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $bookingStatus = array(10 => "Pending Capturer Ceo Approval",
            4 => "Pending Manager Approval",
            1 => "Approved",
            14 => "Rejected");


        $icurrentmonth = date('n');
        if ($icurrentmonth < 10) {
            $icurrentmonth = 0. . $icurrentmonth;
        } else $icurrentmonth = $icurrentmonth;


        $data['MetreType'] = $MetreType;
        $data['icurrentmonth'] = $icurrentmonth;
        $data['imonth'] = $imonth;
        $data['iYear'] = $iYear;
        $data['sCurrency'] = $sCurrency;
        $data['iTotalLitres'] = $iTotalLitres;
        $data['datetaken'] = $datetaken;
        $data['ID'] = $ID;
        $data['ContactCompany'] = $ContactCompany;
        $data['loggedInEmplID'] = $loggedInEmplID;
        $data['name'] = $name;
        $data['bookingStatus'] = $bookingStatus;
        $data['servicestation'] = $servicestation;
        $data['fueltank'] = $fueltank;
        $data['status'] = $status;
        $data['transType'] = $transType;
        $data['fineType'] = $fineType;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclefuellog'] = $vehiclefuellog;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('FleetManagement', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleIFuelLog')->with($data);
    }

    public static function BookingDetails($status = 0, $hrID = 0, $driverID = 0, $tankID = 0)
    {

        $approvals = DB::table('vehicle_configuration')->select('fuel_auto_approval', 'fuel_require_tank_manager_approval', 'fuel_require_ceo_approval')->first();

        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverDetails = HRPerson::where('id', $driverID)->where('status', 1)->first();
        $fueltanks = Fueltanks::where('id', $tankID)->orderBy('id', 'desc')->get();
        if (!empty($fueltanks))
            $fueltanks = HRPerson::where('id', $hrID)->where('status', 1)->first(); // to be changed to ceo

        //     if (!empty($leave_customs))
        // $leave_customs = $leave_customs->load('userCustom');

        $managerID = HRPerson::where('id', $hrID)->where('status', 1)->first();
        $driverHead = $managerID->manager_id;

        if ($approvals->fuel_auto_approval == 1) {
            # code...
            // query the hrperon  model and bring back the values of the manager
            $loggedInEmplID = Auth::user()->person->id;
            $User = HRPerson::where('id', $loggedInEmplID)->where('status', 1)->select('first_name', 'surname', 'email')->first();

            $details = array('status' => 1, 'first_name' => $User->first_name, 'surname' => $User->surname, 'email' => $User->email);
            return $details;

        } elseif ($approvals->fuel_require_tank_manager_approval == 1 && $status < 4) {

            //
            if (!empty($fueltanks)) {
                $fueltanks = HRPerson::where('id', $hrID)->where('status', 1)->first(); // to be changed to ceo
                $userID = $fueltanks->id;
            } else
                $userID = $fueltanks->first()->tank_manager;

            $UserDetails = HRPerson::where('id', $userID)->where('status', 1)->select('first_name', 'surname', 'email')->first();
            
            if ($UserDetails == null) {
                $details = array('status' => 4, 'first_name' => $UserDetails->first_name, 'surname' => $UserDetails->surname, 'email' => $UserDetails->email);
                return $details;
            } else {

                $details = array('status' => 4, 'first_name' => $UserDetails->first_name, 'surname' => $UserDetails->surname, 'email' => $UserDetails->email);
                return $details;
            }
        } elseif ($approvals->fuel_require_ceo_approval == 1 && $status < 10) {

            $Dept = DivisionLevelFour::where('manager_id', $hrDetails->division_level_4)->get()->first();

            $hodmamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)->select('first_name', 'surname', 'email')->first();

            if ($hodmamgerDetails == null) {
                $details = array('status' => 10, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            } else {

                $details = array('status' => 10, 'first_name' => $hodmamgerDetails->firstname, 'surname' => $hodmamgerDetails->surname, 'email' => $hodmamgerDetails->email);
                return $details;
            }
        } 
		else 
		{
			if ($status == 4 || $status == 10) $newStatus = 1;
			else  $newStatus = 4;
            $details = array('status' => $newStatus, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
            return $details;
        }
    }


    public function addvehiclefuellog(Request $request)
    {
        $this->validate($request, [
            'tank_name' => 'bail|required',
            //'Odometer_reading' => 'bail|required',
            'document_number' => 'required|unique:vehicle_fuel_log,document_number',

        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);

        $currentDate = time();

        $bookingStatus = array(10 => "Pending Capturer Ceo Approval",
            4 => "Pending Tank Manager",
            1 => "Approved",
            14 => "Rejected");

        $hrID = $fuelData['rensonsible_person'];
        $tankID = $fuelData['tank_name'];

        $BookingDetails = array();
        $BookingDetail = VehicleFleetController::BookingDetails(0, $hrID, 0, $tankID);

        $dateofincident = $fuelData['date'] = str_replace('/', '-', $fuelData['date']);
        $dateofincident = $fuelData['date'] = strtotime($fuelData['date']);

        $totalcost = $fuelData['total_cost'] = str_replace(',', '', $fuelData['total_cost']);
        $totalcost = $fuelData['total_cost'] = str_replace('. 00', '', $fuelData['total_cost']);

        $loggedInEmplID = Auth::user()->person->id;

        $vehiclefuellog = new vehicle_fuel_log($fuelData);
        $vehiclefuellog->date = $dateofincident;
        $vehiclefuellog->vehicleID = !empty($fuelData['valueID']) ? $fuelData['valueID'] : 0;
        $vehiclefuellog->driver = !empty($fuelData['driver']) ? $fuelData['driver'] : 0;
        $vehiclefuellog->tank_name = !empty($fuelData['tank_name']) ? $fuelData['tank_name'] : 0;
        $vehiclefuellog->service_station = !empty($fuelData['service_station']) ? $fuelData['service_station'] : 0;
        $vehiclefuellog->rensonsible_person = !empty($fuelData['rensonsible_person']) ? $fuelData['rensonsible_person'] : 0;
        $vehiclefuellog->captured_by = $loggedInEmplID;
        $vehiclefuellog->total_cost = !empty ($totalcost) ? $totalcost : 0;
        $vehiclefuellog->tank_and_other = !empty($fuelData['transaction']) ? $fuelData['transaction'] : 0;
        $vehiclefuellog->cost_per_litre = !empty($fuelData['cost_per_litre']) ? $fuelData['cost_per_litre'] : 0;
        $vehiclefuellog->Odometer_reading = !empty($fuelData['Odometer_reading']) ? $fuelData['Odometer_reading'] : 0;
        $vehiclefuellog->status = $BookingDetail['status'];
        $vehiclefuellog->Hoursreading = !empty($fuelData['hours_reading']) ? $fuelData['hours_reading'] : '';
        $vehiclefuellog->published_at = date("Y-m-d H:i:s");
        $vehiclefuellog->vehiclebookingID = !empty($fuelData['vehiclebookingID']) ? $fuelData['vehiclebookingID'] : 0;
        $vehiclefuellog->save();
		if (!empty($fuelData['transaction']) &&  $fuelData['transaction'] == 1)
		{
			//FueltankTopUp FueltankTopUp
			$topUp = new FueltankTopUp();
			$topUp->document_no = $vehiclefuellog->document_number;
			$topUp->document_date = $dateofincident;
			$topUp->topup_date = $dateofincident;
			$topUp->type = 2; //outgoing
			$topUp->litres = $vehiclefuellog->litres;
			$topUp->description = $vehiclefuellog->description;
			$topUp->received_by = $vehiclefuellog->driver;
			$topUp->captured_by = $loggedInEmplID; 
			$topUp->tank_id = $vehiclefuellog->tank_name;
			$topUp->vehicle_fuel_id = $vehiclefuellog->id;
			$topUp->status = $BookingDetail['status'];
			$topUp->save();
		}
        AuditReportsController::store('Fleet Management', 'add vehiclefuel log', "Accessed by User", 0);
        return response()->json();
    }

    public function viewBookingLog(vehicle_maintenance $maintenance)
    {

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        //return $ContactCompany;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $servicestation = service_station::orderBy('id', 'desc')->get();
        $fueltank = tank::orderBy('id', 'desc')->get();


        $currentDate = time();
        //return $currentDate;
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        //return $name;
        ###################>>>>>#################
        $fineType = array(1 => 'Accident', 2 => 'Mechanical Fault', 3 => 'Electronic Fault', 4 => 'Damaged', 5 => 'Attempted Hi-jacking', 6 => 'Hi-jacking', 7 => 'Other');

        $status = array(1 => 'Tank', 2 => 'Other');
        $transType = array(1 => 'Full Tank', 2 => 'Top Up');


        $vehicleID = $maintenance->id;

        $bookingStatus = array(2 => "Pending Capturer Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $vehiclebookinglog = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as firstname', 'hr_people.surname as surname'
            )
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
//            ->leftJoin('vehicle_collect_documents', 'vehicle_booking.id', '=', 'vehicle_collect_documents.bookingID')
//            ->leftJoin('vehicle_return_documents', 'vehicle_booking.id', '=', 'vehicle_return_documents.bookingID')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.vehicle_id', $vehicleID)
            ->get();


        $vehiclebooking = $vehiclebookinglog->unique('id');

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['bookingStatus'] = $bookingStatus;
        $data['ContactCompany'] = $ContactCompany;
        $data['loggedInEmplID'] = $loggedInEmplID;
        $data['name'] = $name;
        $data['servicestation'] = $servicestation;
        $data['usageType'] = $usageType;
        $data['status'] = $status;
        $data['transType'] = $transType;
        $data['fineType'] = $fineType;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclebooking'] = $vehiclebooking;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewBookingLog')->with($data);
    }

    public function deletefuelLog(Request $request, vehicle_fuel_log $fuel)
    {
        $fuel->delete();
        AuditReportsController::store('Fleet Management', 'Vehicle Fuel Log  Deleted', "Document Type has been deleted", 0);
        return back();
    }
}


       
       
