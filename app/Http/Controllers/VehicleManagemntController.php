<?php

namespace App\Http\Controllers;

use App\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\group_admin;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\incident_type;
use App\HRPerson;
use App\vehicle_config;
use App\modules;
Use App\vehiclemodel;
use App\safe;
use App\DivisionLevel;
use App\vehiclemake;
use App\fleet_documentType;
use App\fleet_fillingstation;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VehicleManagemntController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function VehicleSetup(Request $request)
    {
        //$incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Vehicle Configuration Settings";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.setup')->with($data);
    }

    public function index()
    {

        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.fleet_types')->with($data);
    }

    public function Addfleet(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vehicle = new Vehicle_managemnt($SysData);
        $vehicle->status = 1;
        $vehicle->save();
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editfleet(Request $request, Vehicle_managemnt $fleet)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $fleet->name = $SysData['name'];
        $fleet->description = $SysData['description'];
        // $fleet->status = 1;
        $fleet->update();
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function VehicleAct(Vehicle_managemnt $fleet)
    {
        if ($fleet->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $fleet->status = $stastus;
        $fleet->update();
        return back();
    }

    public function deletefleet(Vehicle_managemnt $fleet)
    {

        $fleet->delete();

        AuditReportsController::store('Vehicle Management', 'fleet  Deleted', "fleet has been deleted", 0);
        return redirect('/vehicle_management/Manage_fleet_types');
    }

    public function Fleet_Card()
    {

        $FleetType = FleetType::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Cards Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['FleetType'] = $FleetType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.fleet_card')->with($data);
    }

    public function AddfleetCards(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vehicle = new FleetType($SysData);
        $vehicle->status = 1;
        $vehicle->save();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editfleetcard(Request $request, FleetType $card)
    {

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $card->name = $SysData['name'];
        $card->description = $SysData['description'];
        $card->update();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function fleetcardAct(Request $request, FleetType $card)
    {
        if ($card->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $card->status = $stastus;
        $card->update();
        return back();
    }

    public function deletefleetcard(Request $request, FleetType $card)
    {
        $card->delete();

        AuditReportsController::store('Vehicle Management', 'fleetcard  Deleted', "fleet has been deleted", 0);
        return redirect('/vehicle_management/fleet_card');
    }

    public function Fleet_fillingstaion(Request $request)
    {
        $fleetfillingstation = fleet_fillingstation::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Cards Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['fleetfillingstation'] = $fleetfillingstation;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.fleet_fillingstation')->with($data);
    }

    public function Addfillingstation(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $station = new fleet_fillingstation($SysData);
        $station->status = 1;
        $station->save();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editstation(Request $request, fleet_fillingstation $station)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $station->name = $SysData['name'];
        $station->description = $SysData['description'];
        $station->update();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function stationcardAct(Request $request, fleet_fillingstation $station)
    {
        if ($station->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $station->status = $stastus;
        $station->update();
        return back();
    }

    public function deletestation(Request $request, fleet_fillingstation $station)
    {
        $station->delete();

        AuditReportsController::store('Vehicle Management', 'fleetcard  Deleted', "fleet has been deleted", 0);
        return redirect('/vehicle_management/fillingstaion');
    }

    public function Fleet_licencePermit(Request $request)
    {
        $licence_permit = fleet_licence_permit::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Cards Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['licence_permit'] = $licence_permit;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.fleet_licence_permit')->with($data);
    }

    public function AddlicencePermit(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $station = new fleet_licence_permit($SysData);
        $station->status = 1;
        $station->save();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editlicense(Request $request, fleet_licence_permit $permit)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $permit->name = $SysData['name'];
        $permit->description = $SysData['description'];
        $permit->update();
        AuditReportsController::store('Vehicle FleetTypecard', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function licensePermitAct(Request $request, fleet_licence_permit $permit)
    {
        if ($permit->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $permit->status = $stastus;
        $permit->update();
        return back();
    }

    public function deleteLicensePermit(Request $request, fleet_licence_permit $permit)
    {
        $permit->delete();

        AuditReportsController::store('Vehicle Management', 'fleetcard  Deleted', "fleet has been deleted", 0);
        return redirect('/vehicle_management/fillingstaion');
    }

    public function Fleet_DocumentType(Request $request)
    {
        $fleetdocumentType = fleet_documentType::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Document Type";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['fleetdocumentType'] = $fleetdocumentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.fleet_document_type')->with($data);
    }

    public function AddDocumentType(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $fleetdocument = new fleet_documentType($SysData);
        $fleetdocument->status = 1;
        $fleetdocument->save();
        AuditReportsController::store('Vehicle FleetDocumentType', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function EditDocumentType(Request $request, fleet_documentType $document)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $document->name = $SysData['name'];
        $document->description = $SysData['description'];
        $document->update();
        AuditReportsController::store('Vehicle FleetDocumentType', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function DocumentTypeAct(Request $request, fleet_documentType $document)
    {
        if ($document->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $document->status = $stastus;
        $document->update();
        return back();
    }

    public function deleteDocument(Request $request, fleet_documentType $document)
    {
        $document->delete();

        AuditReportsController::store('Vehicle Management', 'Document Type Deleted', "Document Type has been deleted", 0);
        return redirect('/vehicle_management/Document_type');
    }

//
    public function IncidentType(Request $request)
    {
        $incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Manage Incidents Type ";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.incident_type')->with($data);
    }

    public function AddIncidentType(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $incident_type = new incident_type($SysData);
        $incident_type->status = 1;
        $incident_type->save();
        AuditReportsController::store(' Incident Type', 'Incident Type Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function EditIncidentType(Request $request, incident_type $incident)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $incident->name = $SysData['name'];
        $incident->description = $SysData['description'];
        $incident->update();
        AuditReportsController::store(' Incident Type', 'Incident Type Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function incidentTypeAct(Request $request, incident_type $incident)
    {
        if ($incident->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $incident->status = $stastus;
        $incident->update();
        return back();
    }

    public function deleteIncident(Request $request, incident_type $incident)
    {
        $incident->delete();

        AuditReportsController::store('Incident Type', 'Incident Type Deleted', "Incident Type has been deleted", 0);
        return redirect('/vehicle_management/Incidents_type');
    }

    public function groupAdmin(Request $request)
    {
        $groupAdmin = group_admin::orderBy('id', 'asc')->get();

        $data['page_title'] = " Group Admin ";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['groupAdmin'] = $groupAdmin;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);
        return view('Vehicles.group_admin')->with($data);
    }

    public function Addgroupadmin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $groupAdmin = new group_admin($SysData);
        $groupAdmin->status = 1;
        $groupAdmin->save();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function edit_group(Request $request, group_admin $group)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $group->name = $SysData['name'];
        $group->description = $SysData['description'];
        $group->update();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function groupAct(Request $request, group_admin $group)
    {
        if ($group->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $group->status = $stastus;
        $group->update();
        return back();
    }

    public function VehicleConfiguration(Request $request)
    {
        $row = vehicle_config::count();
        if ($row > 0) {
            $configuration = DB::table('vehicle_configuration')->where("id", 1)->get()->first();
        } else {
            $vehicleconfig = new vehicle_config();
            $vehicleconfig->permit_days = 0;
            $vehicleconfig->currency = 0;
            $vehicleconfig->service_days = 0;
            $vehicleconfig->service_km = 0;
            $vehicleconfig->service_overdue_days = 0;
            $vehicleconfig->service_overdue_km = 0;
            $vehicleconfig->no_bookings_days = 0;
            $vehicleconfig->no_bookings_km = 0;
            $vehicleconfig->save();
        }

        $configuration = DB::table('vehicle_configuration')->where("id", 1)->get()->first();


        $data['page_title'] = " Vehicle Configuration ";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.vehicle_setup')->with($data);
    }

    public function Configuration(Request $request, vehicle_config $configuration)
    {

        $config = $request->all();
        unset($config['_token']);
        //return $config;
        $configuration->update($config);
        return back();
    }

    public function vehicemake()
    {
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();

        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Vehicle Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);
        return view('Vehicles.vehicle_make')->with($data);
    }

    public function AddVehicleMake(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vehiclemake = new vehiclemake($SysData);
        $vehiclemake->status = 1;
        $vehiclemake->save();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editvehiclemake(Request $request, vehiclemake $vmake)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vmake->name = $SysData['name'];
        $vmake->description = $SysData['description'];
        $vmake->update();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function vehiclemakeAct(Request $request, vehiclemake $vmake)
    {
        if ($vmake->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $vmake->status = $stastus;
        $vmake->update();
        return back();
    }

    public function deleteVehiclemake(Request $request, vehiclemake $vmake)
    {
        $vmake->delete();

        AuditReportsController::store('Vehicle Make', 'Vehicle Make Deleted', "Vehicle Make has been deleted", 0);
        return redirect('/vehicle_management/vehice_make');
    }

    public function vehicemodel(vehiclemake $model )
    {
        $modelID = $model->id;
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->where('vehiclemake_id', $modelID)->get();
        $vehiclemodelz = vehiclemake::orderBy('id', 'asc')->where('id', $modelID)->first();
        $vehiclemodels = $vehiclemodelz->name;
        $vehicleID = $modelID ;
        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Vehicle Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];
        
        
        

        $data['vehicleID'] = $vehicleID;
        $data['vehiclemodels'] = $vehiclemodels;   
        $data['vehiclemodel'] = $vehiclemodel;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);
        return view('Vehicles.vehicle_model')->with($data);
    }

    public function AddVehicleModel(Request $request ,vehiclemake $vehiclemake )
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $vehiclemakeID =  $vehiclemake->id;
        $vehiclemodel = new vehiclemodel($SysData);
        $vehiclemodel->status = 1;
        $vehiclemodel->vehiclemake_id = $vehiclemakeID; 
        $vehiclemodel->save();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editvehiclemodel(Request $request, vehiclemodel $vmodel)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vmodel->name = $SysData['name'];
        $vmodel->description = $SysData['description'];
        // $vehiclemodel->vehiclemake_id = $SysData['valueID']; 
        $vmodel->update();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function vehiclemodelAct(Request $request, vehiclemodel $vmodel)
    {
        if ($vmodel->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $vmodel->status = $stastus;
        $vmodel->update();
        return back();
    }

    public function deleteVehiclemodel(Request $request, vehiclemodel $vmodel  )
    {
       
        $vmodel->delete();

        AuditReportsController::store('Vehicle Model', 'Vehicle Model Deleted', "Vehicle Model has been deleted", 0);
        return back();
        //return redirect('/vehicle_management/vehice_model/' . $ID);
    }

    // 
    public function VehicleSearch(Request $request)
    {
        $this->validate($request, [
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $hrDetails = HRPerson::where('status', 1)->get();

        $companyID = $request['company_id'];
        $departmentID = $request['department_id'];
        $propertyID = $request['property_type'];
        $vehicleID = $request['vehicle_type'];
        $fleetID = $request['fleet_number'];
        $registration_number = $request['registration_number'];
        $promotionID = $request['promotion_type'];

        //return $propertyID;

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
//            ->where(function ($query) use ($propertyID) {
//                if (!empty($propertyID)) {
//                    $query->where('vehicle_details.property_type', $propertyID);
//                }
//            })
            ->where(function ($query) use ($vehicleID) {
                if (!empty($vehicleID)) {
                    $query->where('vehicle_details.vehicle_type', $vehicleID);
                }
            })
            ->where(function ($query) use ($fleetID) {
                if (!empty($fleetID)) {
                    $query->where('vehicle_details.fleet_number', $fleetID);
                }
            })
            ->where(function ($query) use ($registration_number) {
                if (!empty($registration_number)) {
                    $query->where('vehicle_details.vehicle_registration', $registration_number);
                }
            })
            ->orderBy('vehicle_details.id')
            ->get();

        //return $vehiclemaintenance;


        $data['hrDetails'] = $hrDetails;
        $data['division_levels'] = $divisionLevels;
        $data['companyID'] = $companyID;
        $data['departmentID'] = $departmentID;
        $data['propertyID'] = $propertyID;
        $data['vehicleID'] = $vehicleID;
        $data['fleetID'] = $fleetID;
        $data['registration_number'] = $registration_number;
        $data['promotionID'] = $promotionID;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Internal Vehicle Management Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('Vehicles.vehicle_search_results')->with($data);
    }

    public function safe()
    {

        $safe = safe::orderBy('id', 'asc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Types Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Types ', 'active' => 1, 'is_module' => 0]
        ];

        $data['safe'] = $safe;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Add_safe')->with($data);
    }

    public function Addsafe(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $safe = new safe($SysData);
        $safe->status = 1;
        $safe->save();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editsafe(Request $request, safe $safe)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $safe->name = $SysData['name'];
        $safe->description = $SysData['description'];
        $safe->update();
        AuditReportsController::store('Vehicle Management', 'Group Admin Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function safeAct(Request $request, safe $safe)
    {
        if ($safe->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $safe->status = $stastus;
        $safe->update();
        return back();
    }

    public function deletesafe(Request $request, safe $safe)
    {
        $safe->delete();

        AuditReportsController::store('Vehicle Model', 'safe  Deleted', "safe has been deleted", 0);
        return redirect('/vehicle_management/safe');
    }


}
