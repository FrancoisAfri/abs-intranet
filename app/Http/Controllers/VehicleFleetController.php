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
use App\vehicle_detail;
use App\DivisionLevelFour;
use App\DivisionLevelFive;
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
        $generalcost->litres_new = $SysData['litres_new'];
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
        $costs->litres_new = $SysData['litres_new'];
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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
                $fileName = time() . "_documents." . $fileExt;
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

		$vehicleCong = vehicle_config::orderBy('id', 'asc')->first();  
        
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
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleIncidents')->with($data);
    }
    
    public function fixVehicle(vehicle_incidents $vehicle){
       // return $vehicle;
        
        // vehicle_fixed value is one wen the the vehicle has been fixed 2 still need to be fixed
        $vehicle->vehicle_fixed = 1;	
		$vehicle->update();
		AuditReportsController::store('Fleet Management', "Vehicle Fixed", "Edited by User", 0);
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
        $vehicleincidents->vehicle_fixed =  2; 
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
                $fileName = time() . "_documents." . $fileExt;
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
	public function viewFuelLog(Request $request, vehicle_maintenance $maintenance, $date = 0)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $FueltankData = $request->all();
        unset($FueltankData['_token']);
		
        $now = Carbon::now();
        $startExplode = explode('_', $date);
        $month = $startExplode[0];
        $command = (!empty($startExplode[1]) ? $startExplode[1] : 0);
        $year = (!empty($startExplode[2]) ? $startExplode[2] : 0);
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $metreType = $maintenance->metre_reading_type;

        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $servicestation = fleet_fillingstation::orderBy('name', 'asc')->get();
        $fueltank = Fueltanks::orderBy('id', 'desc')->get();
        $vehicle_config = vehicle_config::orderBy('id', 'desc')->get();
		
        if ($command === 0) 
		{
            $month = $now->month;
            $year = $now->year;
        } 
		elseif ($command === 'p') 
		{
            if ($month == 1) 
			{
                $year = $year - 1;
                $month = 12;
            } 
			else $month = $month - 1;
        } 
		elseif ($command === 'n') 
		{
            if ($month == 12) 
			{
                $year = $year + 1;
                $month = 1;
            } 
			else $month = $month + 1;
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
        $datetaken = date('n');

        if ($month < 10) {
            $month = 0. . $month;
        } else $month = $month;
		$monthSend = strtotime("$year-$month");
		$monthText = date("F Y", $monthSend);
		$fuelMonth = date("M Y", $monthSend);
		$prevMonth = ($month == 1) ? 12 : $month-1;
		$monthStart = strtotime(new Carbon("first day of $fuelMonth"));
        $monthEnd = new Carbon("last day of $fuelMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
		if ($metreType == 1) 
		{
			$carKm = $maintenance->odometer_reading;
			$field = 'Odometer_reading';
		}
		else 
		{
			$field = 'Hoursreading';
			$carKm = $maintenance->hours_reading;
		}
        if ($month == 1)
		{
			$iprevYear = $year - 1;
			$prevMonthkm = VehicleFleetController::getLastMeterReading($prevMonth, $iprevYear,$maintenance->id,$field,$carKm);
		}
		else
		{
			$prevMonthkm = VehicleFleetController::getLastMeterReading($prevMonth, $year,$maintenance->id,$field,$carKm);
		}

        $vehiclefuellog = DB::table('vehicle_fuel_log')
            ->select('vehicle_fuel_log.*'
			, 'hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'fleet_fillingstation.name as station'
			, 'fuel_tanks.tank_name as tankName')
            ->leftJoin('fuel_tanks', 'vehicle_fuel_log.tank_name', '=', 'fuel_tanks.id')
            ->leftJoin('fleet_fillingstation', 'vehicle_fuel_log.service_station', '=', 'fleet_fillingstation.id')
            ->leftJoin('hr_people', 'vehicle_fuel_log.driver', '=', 'hr_people.id')
            //->whereMonth('vehicle_fuel_log.date', '=', $month)// show record for this month
            //->whereYear('vehicle_fuel_log.date', '=', $year)// show record for this year
            ->whereBetween('date', [$monthStart, $monthEnd])
			->where('vehicle_fuel_log.vehicleID',  $maintenance->id)
            ->orderBy('vehicle_fuel_log.date')
            ->orderBy("vehicle_fuel_log.$field")
            ->get();
			//return $vehiclefuellog;
		if (!empty($vehiclefuellog))
		{
			$oldkm = $count = $litreTopUp = $perLitre = 0;
			foreach ($vehiclefuellog as $fuellog) {
				if ($count == 0)
					$kmTravelled = $fuellog->$field - $prevMonthkm;
				else $kmTravelled = $fuellog->$field - $oldkm;
				if ($fuellog->transaction_type == 1)
				{
					if (!empty($fuellog->litres_new + $litreTopUp))
						$fuellog->per_litre = $kmTravelled / ($fuellog->litres_new + $litreTopUp);
					else $fuellog->per_litre =0;
					$litreTopUp = 0;
				}
				else 
				{
					$fuellog->per_litre = 0;
					$litreTopUp = $litreTopUp + $fuellog->litres_new;
				}			
				$count ++;
				$oldkm = $fuellog->$field;
            }
		}

        $totalLitres = $vehiclefuellog->sum('litres_new');
        $totalCosts = $vehiclefuellog->sum('total_cost');
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $bookingStatus = array(10 => "Pending Ceo Approval",
            4 => "Pending Manager Approval",
            1 => "Approved",
            14 => "Rejected");
        $currentmonth = date('n');
        if ($currentmonth < 10) {
            $currentmonth = 0. . $currentmonth;
        } else $currentmonth = $currentmonth;
		// get user access level
		$user_id = Auth::user()->person->user_id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'vehicle')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $user_id)
            ->pluck('user_id')->first();
		
        $data['userAccess'] = $userAccess;
        $data['metreType'] = $metreType;
        $data['currentmonth'] = $currentmonth;
        $data['month'] = $month;
        $data['year'] = $year;
        $data['monthText'] = $monthText;
        $data['totalCosts'] = $totalCosts;
        $data['totalLitres'] = $totalLitres;
        $data['datetaken'] = $datetaken;
        $data['ID'] = $maintenance->id;
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
        AuditReportsController::store('FleetManagement', 'View Fuel Log Record', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewVehicleIFuelLog')->with($data);
    }

    public static function BookingDetails($status = 0)
    {
        $approvals = DB::table('vehicle_configuration')->select('fuel_auto_approval', 'fuel_require_tank_manager_approval', 'fuel_require_ceo_approval')->first();
		$userID = Auth::user()->person->id;
		$managerID = DivisionLevelFive::where('active', 1)->where('manager_id', $userID)->first();
		if (!empty($managerID->id)) return 1;
        if ($approvals->fuel_auto_approval == 1)
            return 1;
		elseif ($approvals->fuel_require_tank_manager_approval == 1 && $status < 4)
           return 4;
		elseif ($approvals->fuel_require_ceo_approval == 1 && $status < 10)
			return 10;
		else 
		{
			if ($status == 4 || $status == 10) $newStatus = 1;
			else  $newStatus = 4;
            return $newStatus;
        }
    }

    public function addvehiclefuellog(Request $request)
    {
        $this->validate($request, [
            'tank_name' => 'required_if:transaction,1',
            'date' => 'bail|required',
            'transaction' => 'required',
            'document_number' => 'required|unique:vehicle_fuel_log,document_number',
        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);

        $currentDate = time();
        $bookingStatus = array(10 => "Pending Ceo Approval",
            4 => "Pending Manager Approval",
            1 => "Approved",
            14 => "Rejected");
		$loggedInEmplID = Auth::user()->person->id;
        $tankID = $fuelData['tank_name'];
        $status = VehicleFleetController::BookingDetails(0);
        $fuelDate = $fuelData['date'];
        $fuelDate = str_replace('/', '-', $fuelDate);
        $fuelDate = strtotime($fuelDate);

        $totalcost = $fuelData['total_cost'] = str_replace(',', '', $fuelData['total_cost']);
        $totalcost = $fuelData['total_cost'] = str_replace('. 00', '', $fuelData['total_cost']);
		// get the last meter reading
		$fuelMonth = date("M Y", $fuelDate);
		$monthStart = strtotime(new Carbon("first day of $fuelMonth"));
        $monthEnd = new Carbon("last day of $fuelMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
		$meterType = DB::table('vehicle_details')
			->select('metre_reading_type')
			->where('id', $fuelData['valueID'])
			->first();
		
        $vehiclefuellog = new vehicle_fuel_log($fuelData);
        $vehiclefuellog->date = $fuelDate;
        $vehiclefuellog->vehicleID = !empty($fuelData['valueID']) ? $fuelData['valueID'] : 0;
        $vehiclefuellog->driver = !empty($fuelData['driver']) ? $fuelData['driver'] : 0;
        $vehiclefuellog->tank_name = !empty($fuelData['tank_name']) ? $fuelData['tank_name'] : 0;
        $vehiclefuellog->service_station = !empty($fuelData['service_station']) ? $fuelData['service_station'] : 0;
        $vehiclefuellog->captured_by = $loggedInEmplID;
        $vehiclefuellog->total_cost = !empty ($totalcost) ? $totalcost : 0;
        $vehiclefuellog->tank_and_other = !empty($fuelData['transaction']) ? $fuelData['transaction'] : 0;
        $vehiclefuellog->litres_new = !empty($fuelData['litres_new']) ? $fuelData['litres_new'] : 0;
        $vehiclefuellog->cost_per_litre = !empty($fuelData['cost_per_litre']) ? $fuelData['cost_per_litre'] : 0;
        $vehiclefuellog->Odometer_reading = !empty($fuelData['Odometer_reading']) ? $fuelData['Odometer_reading'] : 0;
        $vehiclefuellog->Hoursreading = !empty($fuelData['hours_reading']) ? $fuelData['hours_reading'] : '';
        /*if ($meterType->metre_reading_type == 1)
		{
			if (!empty($lastMeter->Odometer_reading))
				$actualkm = $fuelData['Odometer_reading'] - $lastMeter->Odometer_reading;
			else $actualkm = $fuelData['Odometer_reading'];
			$vehiclefuellog->actual_km_reading = $actualkm;
		}
		else
		{
			if (!empty($lastMeter->Hoursreading))
				$actualhr = $fuelData['hours_reading'] - $lastMeter->Hoursreading;
			else $actualhr = $fuelData['hours_reading'];
			$vehiclefuellog->actual_hr_reading = $actualhr;
        }*/
		$vehiclefuellog->status = $status;
        $vehiclefuellog->published_at = date("Y-m-d H:i:s");
        $vehiclefuellog->vehiclebookingID = !empty($fuelData['vehiclebookingID']) ? $fuelData['vehiclebookingID'] : 0;
        $vehiclefuellog->save();
		if (!empty($fuelData['transaction']) &&  $fuelData['transaction'] == 1)
		{
			$topUp = new FueltankTopUp();
			$topUp->document_no = $vehiclefuellog->document_number;
			$topUp->document_date = $fuelDate;
			$topUp->topup_date = $fuelDate;
			$topUp->type = 2; //outgoing
			$topUp->litres_new = $vehiclefuellog->litres_new;
			$topUp->description = $vehiclefuellog->description;
			$topUp->received_by = $vehiclefuellog->driver;
			$topUp->captured_by = $loggedInEmplID; 
			$topUp->tank_id = $vehiclefuellog->tank_name;
			$topUp->vehicle_fuel_id = $vehiclefuellog->id;
			$topUp->status = $BookingDetail['status'];
			$topUp->save();
		}
        AuditReportsController::store('Fleet Management', 'add vehicle fuel log', "Accessed by User", 0);
        return response()->json();
    }

	public function editFuel(vehicle_fuel_log $fuel)
    {
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $servicestation = fleet_fillingstation::orderBy('name', 'asc')->get();
        $fueltank = Fueltanks::orderBy('id', 'desc')->get();
        $vehicle_config = vehicle_config::orderBy('id', 'desc')->get();
		$fleet = vehicle_maintenance::where('id', $fuel->vehicleID)->first();
		$metreType = $fleet->metre_reading_type;
        
		$data['metreType'] = $metreType;
		$data['employees'] = $employees;
        $data['fuel'] = $fuel;
		$data['servicestation'] = $servicestation;
        $data['fueltank'] = $fueltank;
		$data['page_title'] = " Edit Fuel Record";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Edit Fuel Log', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.edit_fuel_log')->with($data);
    }
	
	public function updateFuelLog(Request $request, vehicle_fuel_log $fuel)
    {
		$this->validate($request, [
            'date' => 'required',
            'tank_and_other' => 'required',
            'document_number' => 'required',
			'tank_name' => 'required_if:transaction,1',
        ]);
        $fuelData = $request->all();
        unset($fuelData['_token']);
		$loggedInEmplID = Auth::user()->person->id;
        if ($fuelData['tank_and_other'] == 1)
		{
			$fuelData['service_station'] = 0;
			$fuelData['cost_per_litre'] = 0;
			$fuelData['total_cost'] = 0;
		}
		else 
			$fuelData['tank_name'] = 0;
        $fuelDate = $fuelData['date'];
        $fuelDate = str_replace('/', '-', $fuelDate);
        $fuelDate = strtotime($fuelDate);

        $totalcost = $fuelData['total_cost'] = str_replace(',', '', $fuelData['total_cost']);
        $totalcost = $fuelData['total_cost'] = str_replace('. 00', '', $fuelData['total_cost']);

        $fuel->date = $fuelDate;
        $fuel->driver = !empty($fuelData['driver']) ? $fuelData['driver'] : 0;
        $fuel->document_number = !empty($fuelData['document_number']) ? $fuelData['document_number'] : 0;
        $fuel->tank_name = !empty($fuelData['tank_name']) ? $fuelData['tank_name'] : 0;
        $fuel->service_station = !empty($fuelData['service_station']) ? $fuelData['service_station'] : 0;
        $fuel->captured_by = $loggedInEmplID;
        $fuel->total_cost = !empty ($totalcost) ? $totalcost : 0;
        $fuel->tank_and_other = !empty($fuelData['tank_and_other']) ? $fuelData['tank_and_other'] : 0;
        $fuel->description = !empty($fuelData['description']) ? $fuelData['description'] : 0;
        $fuel->litres_new = !empty($fuelData['litres_new']) ? $fuelData['litres_new'] : 0;
        $fuel->cost_per_litre = !empty($fuelData['cost_per_litre']) ? $fuelData['cost_per_litre'] : 0;
        $fuel->Odometer_reading = !empty($fuelData['Odometer_reading']) ? $fuelData['Odometer_reading'] : 0;
        $fuel->Hoursreading = !empty($fuelData['hours_reading']) ? $fuelData['hours_reading'] : '';
        $fuel->update();
		if (!empty($fuelData['tank_and_other']) &&  $fuelData['tank_and_other'] == 1)
		{
			// update fuel tank
			DB::table('fuel_tank_topUp')
				->where('vehicle_fuel_id', $fuel->id)
				->update([
					'document_no' => $fuel->document_number,
					'document_date' => $fuelDate,
					'topup_date' => $fuelDate,
					'litres_new' => $fuel->litres_new,
					'description' => $fuel->description,
					'received_by' => $fuel->driver,
					'captured_by' => $loggedInEmplID,
					'tank_id' => $fuel->tank_name
				]);
		}
        AuditReportsController::store('Fleet Management', 'Vehicle Fuel Transaction Updated', "Accessed by User", 0);
        return redirect("/vehicle_management/fuel_log/$fuel->vehicleID")->with('success_sent', "Record Updated.");
    }
	
    public function viewBookingLog(vehicle_maintenance $maintenance)
    {
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
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

        $bookingStatus = array(2 => "Pending Manager Approval",
            1 => "Pending Driver Manager Approval",
            3 => "Pending HOD Approval",
            4 => "Pending Admin Approval",
            10 => "Approved",
            11 => "Collected",
            12 => "Returned",
            13 => "Cancelled",
            14 => "Rejected");

        $usageType = array(1 => ' Usage', 2 => ' Service', 3 => 'Maintenance', 4 => 'Repair');

        $vehiclebooking = DB::table('vehicle_booking')
            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
                'hr_people.first_name as firstname', 'hr_people.surname as surname'
            )
            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_booking.id', 'desc')
            ->where('vehicle_booking.vehicle_id', $vehicleID)
            ->get();
       
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
        AuditReportsController::store('Fleet Management', 'Booking Page Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewBookingLog')->with($data);
    }

    public function deletefuelLog(Request $request, vehicle_fuel_log $fuel)
    {
        AuditReportsController::store('Fleet Management', "Vehicle Fuel Log  Deleted ($fuel->vehicleID)", "deleted", 0);
        $fuel->delete();
        return response()->json();
    }
	
	public function viewCommunications(vehicle_maintenance $maintenance)
    {
        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############
		$contactPersons = DB::table('contacts_contacts')
            ->select('contacts_contacts.*', 'contact_companies.name as comp_name')
            ->leftJoin('contact_companies', 'contacts_contacts.company_id', '=', 'contact_companies.id')
            ->where('contacts_contacts.status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		
		$communicaions = VehicleCommunications::where('vehicle_id',$maintenance->id)->get();
		if (!empty($communicaions)) $communicaions = $communicaions->load('contact','company','sender','employees');
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "Fleet Management";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/manage_fleet', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];
		$communicationStatus = array(1 => 'Email', 2 => 'SMS');
		$sendToStatus = array(1 => 'Client(s)', 2 => 'Employee(s)');
        $data['communicationStatus'] = $communicationStatus;
        $data['sendToStatus'] = $sendToStatus;
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['communicaions'] = $communicaions;
        $data['maintenance'] = $maintenance;
		$data['contactPersons'] = $contactPersons;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'View Fleet Communications', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.view_communications')->with($data);
    }
	
	public function sendMessageIndex(vehicle_maintenance $maintenance)
    {
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')->get();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############
		$contactPersons = DB::table('contacts_contacts')
            ->select('contacts_contacts.*', 'contact_companies.name as comp_name')
            ->leftJoin('contact_companies', 'contacts_contacts.company_id', '=', 'contact_companies.id')
            ->where('contacts_contacts.status', 1)
			->orderBy('contact_companies.name', 'asc')
			->orderBy('contacts_contacts.first_name', 'asc')
			->orderBy('contacts_contacts.surname', 'asc')->get();
		

        $data['page_title'] = "Vehicle Communication";
        $data['page_description'] = "";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/vehicle_management/manage_fleet', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Send Message', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        $data['employees'] = $employees;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['maintenance'] = $maintenance;
		$data['contactPersons'] = $contactPersons;
        AuditReportsController::store('Fleet Management', 'Send Message Page Accessed', "Actioned By User", 0);
        return view('Vehicles.FleetManagement.send_vehicle_communications')->with($data);
    }

    public function sendCommunication(Request $request, vehicle_maintenance $maintenance)
    {
        $this->validate($request, [
          'message_type' => 'required',
          'email_content' => 'bail|required_if:message_type,1',
          'sms_content' => 'bail|required_if:message_type,2|max:180',
        ]);
		$mobileArray = array();
        $CommunicationData = $request->all();
        unset($CommunicationData['_token']);
		$vehicleInfo = '';
        # Save email
		$sendFleetDetails = !empty($CommunicationData['send_fleet_details']) ? $CommunicationData['send_fleet_details'] : 0;
		if (!empty($sendFleetDetails) && $CommunicationData['message_type'] == 2)
		{
			################## WELL DETAILS ###############
			$vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
			$vehicleInfo .= " \nFleet#: $maintenance->fleet_number \n";
			$vehicleInfo .= "Reg#: $maintenance->vehicle_registration \n";
			if (!empty($vehiclemaker))
			$vehicleInfo .= "Make: $vehiclemaker->name \n";
			$vehicleInfo .= "Year: $maintenance->year \n";
			$vehicleInfo .= "Engine#: $maintenance->engine_number \n";
			$vehicleInfo .= "VIN (Chassis Number)#: $maintenance->chassis_number";
		}
		else
		{
			################## WELL DETAILS ###############
			$vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
			if (!empty($vehiclemaker)) $make = "Make: $vehiclemaker->name "; else $make =  '';
			$vehicleInfo = <<<HTML
<p>
Fleet#: $maintenance->fleet_number
Reg#: $maintenance->vehicle_registration
$make
Year: $maintenance->year
Engine#: $maintenance->engine_number
VIN (Chassis Number)#: $maintenance->chassis_number
</p>
HTML;
		}
        $user = Auth::user()->load('person');
		if (!empty($CommunicationData['clients']))
		{
			foreach ($CommunicationData['clients'] as $clientID) {
				$client = ContactPerson::where('id', $clientID)->first();
				$companyID = !empty($client->company_id) ? $client->company_id :0 ;
				if (!empty($companyID))
				{
					$VehicleCommunications = new VehicleCommunications;
					$VehicleCommunications->message = !empty($CommunicationData['email_content']) ? $CommunicationData['email_content']."
					 ".$vehicleInfo : $CommunicationData['sms_content']." ".$vehicleInfo;
					$VehicleCommunications->communication_type = $CommunicationData['message_type'];
					$VehicleCommunications->contact_id = $clientID;
					$VehicleCommunications->company_id = $companyID;
					$VehicleCommunications->vehicle_id = $maintenance->id;
					$VehicleCommunications->send_type = !empty($CommunicationData['send_type']) ? $CommunicationData['send_type'] : '';
					$VehicleCommunications->status = 1;
					$VehicleCommunications->sent_by = $user->person->id;
					$VehicleCommunications->communication_date = strtotime(date("Y-m-d"));
					$VehicleCommunications->time_sent = date("h:i:sa");
					$VehicleCommunications->save();
					if ($CommunicationData['message_type'] == 1 && !empty($client->email))
						# Send Email to Client
						Mail::to($client->email)->send(new VehicleCommunication($client, $VehicleCommunications, $client->email));
					elseif ($CommunicationData['message_type'] == 2 && !empty($client->cell_number))
							$mobileArray[] = $this->formatCellNo($client->cell_number);
				}
			}
		}
		elseif(!empty($CommunicationData['employees']))
		{
			foreach ($CommunicationData['employees'] as $hrPerson) {
				$employee = HRPerson::where('id', $hrPerson)->first();
				if (!empty($employee))
				{
					$VehicleCommunications = new VehicleCommunications;
					$VehicleCommunications->message = !empty($CommunicationData['email_content']) ? $CommunicationData['email_content']."
					 ".$vehicleInfo : $CommunicationData['sms_content']." ".$vehicleInfo;
					$VehicleCommunications->communication_type = $CommunicationData['message_type'];
					$VehicleCommunications->employee_id = $hrPerson;
					$VehicleCommunications->send_type = !empty($CommunicationData['send_type']) ? $CommunicationData['send_type'] : '';
					$VehicleCommunications->vehicle_id = $maintenance->id;
					$VehicleCommunications->status = 1;
					$VehicleCommunications->sent_by = $user->person->id;
					$VehicleCommunications->communication_date = strtotime(date("Y-m-d"));
					$VehicleCommunications->time_sent = date("h:i:sa");
					$VehicleCommunications->save();
					if ($CommunicationData['message_type'] == 1 && !empty($employee->email))
						# Send Email to employee
						Mail::to($employee->email)->send(new VehicleCommunicationsEmployees($employee, $VehicleCommunications, $employee->email));
					elseif ($CommunicationData['message_type'] == 2 && !empty($employee->cell_number))
							$mobileArray[] = $this->formatCellNo($employee->cell_number);
				}
			}
		}
        if ($CommunicationData['message_type'] == 2 && !empty($mobileArray)) {
            #format cell numbers
            # send out the message
            $CommunicationData['sms_content'] = $CommunicationData['sms_content']." ".$vehicleInfo;
            $CommunicationData['sms_content'] = str_replace("<br>", "", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace(">", "-", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace("<", "-", $CommunicationData['sms_content']);
            BulkSMSController::send($mobileArray, $CommunicationData['sms_content']);
        }

        AuditReportsController::store('Fleet Management', 'Fleet Communication sent', "Accessed By User", 0);
        return redirect("/vehicle_management/fleet-communications/$maintenance->id")->with('success_sent', "Communication Successfully Sent.");
    }
	
	function formatCellNo($sCellNo)
    {
        # Remove the following characters from the phone number
        $cleanup_chr = array("+", " ", "(", ")", "\r", "\n", "\r\n");

        # clean phone number
        $sCellNo = str_replace($cleanup_chr, '', $sCellNo);

        #Internationalise  the number
        if ($sCellNo{0} == "0") $sCellNo = "27" . substr($sCellNo, 1);

        return $sCellNo;
    }
	// Print Communication
	public function communicationsPrint(vehicle_detail $maintenance)
    {
		################## Fleet DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
		
		$communicaions = VehicleCommunications::where('vehicle_id',$maintenance->id)->get();
		if (!empty($communicaions)) $communicaions = $communicaions->load('contact','company','sender','employees');
		
		$companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');
		$communicationStatus = array(1 => 'Email', 2 => 'SMS');
		$sendToStatus = array(1 => 'Client(s)', 2 => 'Employee(s)');
		$data['page_title'] = 'View Vehicle History';
		$data['page_description'] = 'Vehicle History';
		$data['breadcrumb'] = [
			['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle/Search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'Manage Fleet', 'active' => 1, 'is_module' => 0]
		];
		
		$data['communicationStatus'] = $communicationStatus;
		$data['sendToStatus'] = $sendToStatus;
		$data['vehiclemaker'] = $vehiclemaker;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['maintenance'] = $maintenance;
		$data['communicaions'] = $communicaions;
		$data['active_mod'] = 'Fleet Management';
		$data['active_rib'] = 'Manage Fleet';
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
		
		AuditReportsController::store('Fleet Management', 'Communication Printed', 'Accessed by User', 0);
		return view('Vehicles.FleetManagement.communications_print')->with($data);
    }
}