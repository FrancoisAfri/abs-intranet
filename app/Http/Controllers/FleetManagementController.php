<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\jobcardMaintance;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\job_maintanace;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\fleet_documentType;
use App\fleet_fillingstation;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class FleetManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fleetManagent()
    {
        //$incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.fleetIndex')->with($data);
    }

    public function addvehicle()
    {

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();

//        $vehiclemaintenance = vehicle_maintenance::orderBy('id', 'asc')->get();

        $vehiclemaintenance = DB::table('vehicle_maintenance')
            ->select('vehicle_maintenance.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_maintenance.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_maintenance.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_maintenance.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_maintenance.id')
            ->get();



        $data['page_title'] = " Fleet Management";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.add_vehicle')->with($data);

    }
    // public function addvehicleDetails(Request $request , job_maintanace $jobmaintanace) {
    //    $this->validate($request, [
    //         // 'name' => 'required',
    //         'description' => 'required',
    //     ]);
    //     $jobData = $request->all();
    //     unset($jobData['_token']);

    //     $jobmaintanace->status = 1;
    //     $jobmaintanace->responsible_for_maintenance = $jobData['responsible_for_maintenance'];
    //     $jobmaintanace->vehicle_make = $jobData['vehicle_make'];
    //     $jobmaintanace->vehicle_registration = $jobData['vehicle_registration'];
    //     $jobmaintanace->chassis_number = $jobData['chassis_number'];
    //     // $jobmaintanace->name = $jobData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     // $jobmaintanace->name = $SysData['name'];
    //     $jobmaintanace->save();
    //     AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
    //     ;
    //     return response()->json();


    // }

    public function addvehicleDetails(Request $request)
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $vehicle_maintenance = new vehicle_maintenance();
        $vehicle_maintenance->status = 1;
        $vehicle_maintenance->vehicle_make = $SysData['vehicle_make'];
        $vehicle_maintenance->vehicle_model = $SysData['vehicle_model'];
        $vehicle_maintenance->vehicle_type = $SysData['vehicle_type'];
        $vehicle_maintenance->year = $SysData['year'];
        $vehicle_maintenance->vehicle_registration = $SysData['vehicle_registration'];
        $vehicle_maintenance->chassis_number = $SysData['chassis_number'];
        $vehicle_maintenance->engine_number = $SysData['engine_number'];
        $vehicle_maintenance->vehicle_color = $SysData['vehicle_color'];
        $vehicle_maintenance->odometer_reading = $SysData['odometer_reading'];
        $vehicle_maintenance->hours_reading = $SysData['hours_reading'];
        // $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
       // $vehicle_maintenance->registration_papers = $SysData['registration_papers'];

        //$vehicle_maintenance->vehicle_owner = $SysData['vehicle_owner'];
        // $vehicle_maintenance->financial_institution = $SysData['financial_institution'];
        // $vehicle_maintenance->company = $SysData['company'];
        $vehicle_maintenance->extras = $SysData['extras'];
//           $vehicle_maintenance->image = $SysData['image'];
        //$vehicle_maintenance->property_type = $SysData['property_type'];
        $vehicle_maintenance->save();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('image', $fileName);
                //Update file name in the database
                $vehicle_maintenance->image = $fileName;
                $vehicle_maintenance->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('registration_papers')) {
            $fileExt = $request->file('registration_papers')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('registration_papers')->isValid()) {
                $fileName = $vehicle_maintenance->id . "_registration_papers." . $fileExt;
                $request->file('registration_papers')->storeAs('projects/registration_papers', $fileName);
                //Update file name in the table
                $vehicle_maintenance->registration_papers = $fileName;
                $vehicle_maintenance->save();
            }
        }


        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

//    public function viewDetails(){
//
//    }

    public function viewDetails(vehicle_maintenance $maintenance) {

       $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();


        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
            $vehiclemaintenance = DB::table('vehicle_maintenance')
                ->select('vehicle_maintenance.*', 'vehicle_make.name as vehicle_make',
                    'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                ->leftJoin('vehicle_make', 'vehicle_maintenance.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_maintenance.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_maintenance.vehicle_type', '=', 'vehicle_managemnet.id')
                ->where('vehicle_maintenance.id', $ID)
                ->orderBy('vehicle_maintenance.id')
                ->get();


            $vehiclemaintenances = $vehiclemaintenance ->first();

          // return $vehicle_maintenance;


            //$Category->load('productCategory');
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['vehiclemaintenance'] = $vehiclemaintenance;
            $data['vehiclemaintenances'] = $vehiclemaintenances;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewfleetDetails')->with($data);
        } else
            return back();
    }

    public  function  editvehicleDetails( Request $request ,vehicle_maintenance $maintenance) {
//        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required',
//        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $maintenance->vehicle_make = $SysData['vehicle_make'];
        $maintenance->vehicle_model = $SysData['vehicle_model'];
        $maintenance->vehicle_type = $SysData['vehicle_type'];
        // $fleet->status = 1;
        $maintenance->update();
        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        ;
        return response()->json();
    }

    public function viewImage(vehicle_maintenance $maintenance) {

        $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();


        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
            $vehiclemaintenance = DB::table('vehicle_maintenance')
                ->select('vehicle_maintenance.*', 'vehicle_make.name as vehicle_make',
                    'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
                ->leftJoin('vehicle_make', 'vehicle_maintenance.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_maintenance.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_maintenance.vehicle_type', '=', 'vehicle_managemnet.id')
                ->where('vehicle_maintenance.id', $ID)
                ->orderBy('vehicle_maintenance.id')
                ->get();


            $vehiclemaintenances = $vehiclemaintenance ->first();

            // return $vehicle_maintenance;


            //$Category->load('productCategory');
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['vehiclemaintenance'] = $vehiclemaintenance;
            $data['vehiclemaintenances'] = $vehiclemaintenances;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewfleetImage')->with($data);
        } else
            return back();

    }
}
