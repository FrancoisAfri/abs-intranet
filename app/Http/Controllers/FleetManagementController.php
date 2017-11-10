<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\jobcardMaintance;
Use App\permits_licence;
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
use App\fleet_documentType;
use App\keytracking;
use App\safe;
use App\vehicle_documets;
use App\images;
use App\fleet_fillingstation;
use App\module_access;
use App\DivisionLevelFive;
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

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        //return $vehicle;
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();

        $images = images::orderBy('id','asc')->get();
        

        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->get();
        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model','vehicle_image.image as vehicle_images','vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image','vehicle_details.id','=' , 'vehicle_image.vehicle_maintanace' )
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id')
            ->get();

            //return $vehiclemaintenance;

        $data['images'] = $images;    
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['hrDetails'] = $hrDetails;    
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;

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


        $vehiclemaintenance = DB::table('vehicle_details')
             ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model','vehicle_image.image as vehicle_images','vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image','vehicle_details.id','=' , 'vehicle_image.vehicle_maintanace' )
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id')
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


    public function addvehicleDetails(Request $request)
    {
        $this->validate($request, [
            'vehicle_make' =>'required',
            'vehicle_model' => 'required',
            'vehicle_type' => 'required',
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

       $currentDate = time();

        $vehicle_maintenance = new vehicle_maintenance();
        $vehicle_maintenance->status = 1;
        $vehicle_maintenance->responsible_for_maintenance = $SysData['responsible_for_maintenance'];
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
        $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->fleet_number = $SysData['fleet_number'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
        $vehicle_maintenance->vehicle_owner = $SysData['vehicle_owner'];
        $vehicle_maintenance->financial_institution = 0;
        $vehicle_maintenance->company = $SysData['company'];
        $vehicle_maintenance->extras = $SysData['extras'];
        $vehicle_maintenance->property_type = $SysData['property_type'];
        $vehicle_maintenance->division_level_5 = $SysData['division_level_5'];
        $vehicle_maintenance->division_level_4 = $SysData['division_level_4'];
        $vehicle_maintenance->division_level_3 = 0;
        $vehicle_maintenance->division_level_2 = 0;
        $vehicle_maintenance->division_level_1 =0;
        $vehicle_maintenance->currentDate = $currentDate;
        $vehicle_maintenance->title_type =0;
        $vehicle_maintenance->responsible =0;
        $vehicle_maintenance->image = 1;
        $vehicle_maintenance->save();

         $vehicleImages = new images();
         $vehicledocumets = new vehicle_documets();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('image', $fileName);
                //Update file name in the database
                $vehicleImages->image = $fileName;
                $vehicleImages->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('registration_papers')) {
            $fileExt = $request->file('registration_papers')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('registration_papers')->isValid()) {
                $fileName = $vehicledocumets->id . "_registration_papers." . $fileExt;
                $request->file('registration_papers')->storeAs('projects/registration_papers', $fileName);
                //Update file name in the table
                $vehicledocumets->document = $fileName;
                $vehicledocumets->save();
            }
        }

          

          $ID = $vehicle_maintenance->id;
          $name = $vehicle_maintenance->image;
          $loggedInEmplID = Auth::user()->person->id;

          $vehicleImages->vehicle_maintanace = $ID;
          $vehicleImages->name = $name;
          $vehicleImages->user_name = $loggedInEmplID;
          $vehicleImages->upload_date = $currentDate;
          $vehicleImages->default_image = 1;
          $vehicleImages->status = 1;
          $vehicleImages->save();

          ##document 
          $vehicledocumets->vehicleID = $ID;
          $vehicledocumets->name = $name;
          $vehicledocumets->user_name = $loggedInEmplID;
          $vehicledocumets->upload_date = $currentDate;
          $vehicledocumets->default_documrnt = 1;
          $vehicledocumets->status = 1;
          $vehicledocumets->save();

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }


    public function viewDetails(vehicle_maintenance $maintenance) {

       $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $Vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();

         ################## WELL DETAILS ###############
         $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
         $vehiclemaker =  $vehiclemake->name;

         $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
         $vehiclemodeler =  $vehicle_model->name;

         $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
         $vehicleTypes =  $vehicleType->name;
       ################## WELL DETAILS ###############

         $fueltype = array(1 => 'Unleaded', 2 => ' Lead replacement', 3 => ' Diesel'); 
         $status = array(1 => 'Active', 2 => 'Deactivated'); 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
             $vehiclemaintenance = DB::table('vehicle_details')
             ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model','vehicle_managemnet.name as vehicle_type','division_level_fives.name as company' ,'division_level_fours.name as Department','hr_people.first_name as first_name' , 'hr_people.surname as surname')
                ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                ->leftJoin('division_level_fives','vehicle_details.division_level_5', '=', 'division_level_fives.id' )
                ->leftJoin('division_level_fours','vehicle_details.division_level_4', '=', 'division_level_fours.id' )
                ->leftJoin('hr_people', 'vehicle_details.responsible_for_maintenance', '=', 'hr_people.id')
                ->where('vehicle_details.id', $ID)
                ->orderBy('vehicle_details.id')
                ->get();


            $vehiclemaintenances = $vehiclemaintenance ->first();

            //$Category->load('productCategory');
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['status'] = $status;
            $data['fueltype'] =  $fueltype;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['Vehiclemake'] = $Vehiclemake;
            $data['vehiclemaintenance'] = $vehiclemaintenance;
            $data['vehiclemaintenances'] = $vehiclemaintenances;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
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
        //return $maintenance;

        $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        
        $currentDate = time();

         ################## WELL DETAILS ###############
         $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
         $vehiclemaker =  $vehiclemake->name;

         $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
         $vehiclemodeler =  $vehicle_model->name;

         $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
         $vehicleTypes =  $vehicleType->name;
       ################## WELL DETAILS ###############



        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
            $vehiclemaintenance = DB::table('vehicle_details')
                ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model','vehicle_image.image as vehicle_images','vehicle_managemnet.name as vehicle_type')
                ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_image','vehicle_details.id','=' , 'vehicle_image.vehicle_maintanace' )
                ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                ->where('vehicle_details.id', $ID)
                ->orderBy('vehicle_details.id')
                ->get();

                //return $vehiclemaintenance;


            //$vehiclemaintenances = $vehiclemaintenance ->first();

            //$Category->load('productCategory');
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['ID'] = $ID;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['vehiclemaintenance'] = $vehiclemaintenance;
            //$data['vehiclemaintenances'] = $vehiclemaintenances;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.viewfleetImage')->with($data);
        } else
            return back();

    }

    public function keys(vehicle_maintenance $maintenance) {

        $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id' , 'asc')->get();
        $safe = safe::orderBy('id' , 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
          
        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',); 
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe'); 
        
        $currentDate = time();
        ################## WELL DETAILS ###############
         $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
         $vehiclemaker =  $vehiclemake->name;

         $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
         $vehiclemodeler =  $vehicle_model->name;

         $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
         $vehicleTypes =  $vehicleType->name;
       ################## WELL DETAILS ###############

         $loggedInEmplID = Auth::user()->person->id;
         $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
         $name =  $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


                 $keytracking = DB::table('keytracking')
                ->select('keytracking.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'hr_people.manager_id as manager', 'safe.name as safeName')
                ->leftJoin('hr_people', 'keytracking.employee', '=', 'hr_people.id')
                ->leftJoin('safe', 'keytracking.safe_name', '=', 'safe.id')
                ->orderBy('keytracking.id')
                ->get();


               // return $keytracking;


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
            $data['keytracking'] = $keytracking;
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
            return view('Vehicles.FleetManagement.key_tracking')->with($data);
        } else
            return back();

    }

    public function vehiclesAct(Request $request, vehicle_maintenance $vehicle){
        if ($vehicle->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $vehicle->status = $stastus;
        $vehicle->update();
       // return view('Vehicles.vehicle_search_results');
        return back();
    }

    public function addImages(Request $request ){
        $this->validate($request, [
            'name' => 'required',
            // 'description' => 'required',
            'image' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

         $currentDate = time();         

          $vehicleImages = new images(); 

          //$vehicleImages->vehicle_maintanace = $ID;

          $vehicleImages->name =   $SysData['name']; 
          $vehicleImages->description = $SysData['description']; 
          $vehicleImages->vehicle_maintanace = $SysData['valueID']; 
          $vehicleImages->upload_date = $currentDate;
          $vehicleImages->save();

            //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('image', $fileName);
                //Update file name in the database
                $vehicleImages->image = $fileName;
                $vehicleImages->update();
            }
        }

         return response()->json();
    }

    public function editImage( Request $request ,images $image) {

        //        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required',
//        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $image->name =   $SysData['name'];
        $imagedescription = $SysData['name'];
        $imageimages = $SysData['name'];  
       $image->update();
       AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        ;
        return response()->json();    
    }

      public function addkeys(Request $request ){
        $this->validate($request, [
             // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

         $currentDate = time();  

         $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
         $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

       $keytracking = new keytracking(); 

       $keytracking->key_number = $SysData['key_number']; 
       $keytracking->key_type = $SysData['key_type'];
       $keytracking->key_status = $SysData['key_status'];
       $keytracking->description = $SysData['description'];
       $keytracking->employee = $SysData['key'];
       $keytracking->date_issued = $dates;
       $keytracking->issued_by = $SysData['issued_by'];
       $keytracking->safe_name = $SysData['safe_name'];
       $keytracking->safe_controller = $SysData['safe_controller'];
       $keytracking->issued_to = $SysData['issued_to'];
       $keytracking->vehicle_type =0 ;
       $keytracking->vehicle_id = 0;    
       $keytracking->save();

        return response()->json();

    }

    public  function  editKeys( Request $request ,keytracking $keytracking) {
//        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required',
//        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();  

       $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
       $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

       $keytracking->key_number = $SysData['key_number']; 
       $keytracking->key_type = $SysData['key_type'];
       $keytracking->key_status = $SysData['key_status'];
       $keytracking->description = $SysData['description'];
       $keytracking->employee = $SysData['key'];
       $keytracking->date_issued = $dates;
       $keytracking->issued_by = $SysData['issued_by'];
       $keytracking->safe_name = $SysData['safe_name'];
       $keytracking->safe_controller = $SysData['safe_controller'];
       $keytracking->issued_to = $SysData['issued_to'];
       $keytracking->vehicle_type =0 ;
       $keytracking->vehicle_id = 0;    
       $keytracking->update();
       AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        ;
        return response()->json();
    }

##permits

    public function permits_licences(vehicle_maintenance $maintenance) {

        $ID =  $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id' , 'asc')->get();
        $safe = safe::orderBy('id' , 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
          
        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',); 
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe'); 
        
        $currentDate = time();
        ################## WELL DETAILS ###############
         $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
         $vehiclemaker =  $vehiclemake->name;

         $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
         $vehiclemodeler =  $vehicle_model->name;

         $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
         $vehicleTypes =  $vehicleType->name;
       ################## WELL DETAILS ###############

         $loggedInEmplID = Auth::user()->person->id;
         $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
         $name =  $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

         $status = array(1 => 'Active', 2 => 'InActive'); 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;

                $permits = DB::table('permits_licence')
                           ->select('permits_licence.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
                           ->leftJoin('hr_people', 'permits_licence.Supplier', '=', 'hr_people.id')
                           ->orderBy('permits_licence.id') 
                           ->get();


               // return $permits;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['status'] = $status;
            $data['name'] = $name;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['safe'] = $safe;
            $data['employees'] = $employees;
            $data['permits'] = $permits;
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
            return view('Vehicles.FleetManagement.permits')->with($data);
        } else
            return back();

    }

    public function addPermit(Request $request ){
        $this->validate($request, [
             // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);



         $currentDate = time();  
         $loggedInEmplID = Auth::user()->person->id;
         $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
         $name =  $Employee->first_name . ' ' . $Employee->surname;

         $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
         $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);
         
         $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
         $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

       $permits = new permits_licence(); 

       $permits->permit_licence = $SysData['permit_licence']; 
       $permits->Supplier = $SysData['Supplier'];
       $permits->exp_date = $Expdate;
       $permits->date_issued = $dates;
       $permits->status = $SysData['status'];
       $permits->permits_licence_no = $SysData['permits_licence_no'];
       $permits->captured_by = $name;
          
       $permits->save();

       //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $permits->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('projects/documents', $fileName);
                //Update file name in the table
                $permits->document = $fileName;
                $permits->save();
            }
        }

        return response()->json();

    }

     public function editPermit(Request $request, permits_licence $permit) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $permit->permit_licence = $SysData['permit_licence'];
        $permit->Supplier = $SysData['Supplier'];
        $document->update();
        AuditReportsController::store('Vehicle FleetDocumentType', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        ;
        return response()->json();
    }
    
}
