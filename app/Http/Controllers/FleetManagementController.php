<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use App\ContactCompany;
use App\DivisionLevel;
Use App\permits_licence;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\keytracking;
use App\safe;
use App\vehicle_documets;
use App\images;
use App\notes;
use App\DivisionLevelFive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FleetManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fleetManagent()
    {
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('id', 'desc')->get();

        $images = images::orderBy('id', 'asc')->get();

        //check  vehicle_configuration table if new_vehicle_approval is active
        $vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();

        //return $vehicleConfig;

        // $DivisionLevelFive = DivisionLevelFive::where('active', 1)->get();
        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_image.image as vehicle_images',
                'vehicle_managemnet.name as vehicle_type', 'contact_companies.name as Vehicle_Owner ')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image', 'vehicle_details.id', '=', 'vehicle_image.vehicle_maintanace')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('contact_companies', 'vehicle_details.vehicle_owner', '=', 'contact_companies.id')
            ->orderBy('vehicle_details.id')
            ->get();


        $data['vehicleConfig'] = $vehicleConfig;
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['images'] = $images;
        $data['hrDetails'] = $hrDetails;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['ContactCompany'] = $ContactCompany;

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
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
                'vehicle_model.name as vehicle_model', 'vehicle_image.image as vehicle_images', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image', 'vehicle_details.id', '=', 'vehicle_image.vehicle_maintanace')
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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.add_vehicle')->with($data);

    }


    public function addvehicleDetails(Request $request)
    {
        $this->validate($request, [

            'vehicle_type' => 'required',
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $userLogged = Auth::user()->load('person');
        $Username = $userLogged->person->first_name . " " . $userLogged->person->surname;


        $vehicle_maintenance = new vehicle_maintenance();
        $vehicle_maintenance->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $vehicle_maintenance->responsible_for_maintenance = !empty($SysData['responsible_for_maintenance']) ? $SysData['responsible_for_maintenance'] : 0;
        $vehicle_maintenance->vehicle_make = !empty($SysData['vehiclemodel_id']) ? $SysData['vehiclemodel_id'] : 0;
        $vehicle_maintenance->vehicle_model = !empty($SysData['vehiclemake_id']) ? $SysData['vehiclemake_id'] : 0;
        $vehicle_maintenance->vehicle_type = !empty($SysData['vehicle_type']) ? $SysData['vehicle_type'] : 0;
        $vehicle_maintenance->year = $SysData['year'];
        $vehicle_maintenance->vehicle_registration = $SysData['vehicle_registration'];
        $vehicle_maintenance->chassis_number = $SysData['chassis_number'];
        $vehicle_maintenance->engine_number = $SysData['engine_number'];
        $vehicle_maintenance->vehicle_color = $SysData['vehicle_color'];
        $vehicle_maintenance->metre_reading_type = $SysData['promotion_type'];
        $vehicle_maintenance->odometer_reading = $SysData['odometer_reading'];
        $vehicle_maintenance->hours_reading = $SysData['hours_reading'];
        $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->fleet_number = $SysData['fleet_number'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
        $vehicle_maintenance->vehicle_owner = !empty($SysData['vehicle_owner']) ? $SysData['vehicle_owner'] : 0;
        $vehicle_maintenance->financial_institution = !empty($SysData['financial_institution']) ? $SysData['financial_institution'] : 0;
        $vehicle_maintenance->company = !empty($SysData['company']) ? $SysData['company'] : 0;
        $vehicle_maintenance->extras = $SysData['extras'];
        $vehicle_maintenance->property_type = !empty($SysData['property_type']) ? $SysData['property_type'] : 0;
        $vehicle_maintenance->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $vehicle_maintenance->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $vehicle_maintenance->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $vehicle_maintenance->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $vehicle_maintenance->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $vehicle_maintenance->currentDate = $currentDate;
        $vehicle_maintenance->title_type = !empty($SysData['title_type']) ? $SysData['title_type'] : 0;
        $vehicle_maintenance->name = $Username;
        $vehicle_maintenance->booking_status = !empty($SysData['booking_status']) ? $SysData['booking_status'] : 0;
        $vehicle_maintenance->save();

        $loggedInEmplID = Auth::user()->person->id;
        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = $vehicle_maintenance->id . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
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
                $request->file('registration_papers')->storeAs('Vehicle/registration_papers', $fileName);
                //Update file name in the table
                $vehicle_maintenance->registration_papers = $fileName;
                $vehicle_maintenance->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editvehicleDetails(Request $request, vehicle_maintenance $vehicle_maintenance)
    {
        $this->validate($request, [
//            'vehicle_make' => 'required',
//            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);


        $currentDate = time();
        $userLogged = Auth::user()->load('person');
        $Username = $userLogged->person->first_name . " " . $userLogged->person->surname;

        $vehicle_maintenance->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $vehicle_maintenance->responsible_for_maintenance = !empty($SysData['responsible_for_maintenance']) ? $SysData['responsible_for_maintenance'] : 0;
        $vehicle_maintenance->vehicle_make = !empty($SysData['vehiclemodel_id']) ? $SysData['vehiclemodel_id'] : 0;
        $vehicle_maintenance->vehicle_model = !empty($SysData['vehiclemake_id']) ? $SysData['vehiclemake_id'] : 0;
        $vehicle_maintenance->vehicle_type = !empty($SysData['vehicle_type']) ? $SysData['vehicle_type'] : 0;
        $vehicle_maintenance->year = $SysData['year'];
        $vehicle_maintenance->vehicle_registration = $SysData['vehicle_registration'];
        $vehicle_maintenance->chassis_number = $SysData['chassis_number'];
        $vehicle_maintenance->engine_number = $SysData['engine_number'];
        $vehicle_maintenance->vehicle_color = $SysData['vehicle_color'];
        $vehicle_maintenance->metre_reading_type = $SysData['promotion_type'];
        $vehicle_maintenance->odometer_reading = $SysData['odometer_reading'];
        $vehicle_maintenance->hours_reading = $SysData['hours_reading'];
        $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->fleet_number = $SysData['fleet_number'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
        $vehicle_maintenance->vehicle_owner = !empty($SysData['vehicle_owner']) ? $SysData['vehicle_owner'] : 0;
        $vehicle_maintenance->financial_institution = !empty($SysData['financial_institution']) ? $SysData['financial_institution'] : 0;
        $vehicle_maintenance->company = !empty($SysData['company']) ? $SysData['company'] : 0;
        $vehicle_maintenance->extras = $SysData['extras'];
        $vehicle_maintenance->property_type = !empty($SysData['property_type']) ? $SysData['property_type'] : 0;
        $vehicle_maintenance->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $vehicle_maintenance->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $vehicle_maintenance->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $vehicle_maintenance->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $vehicle_maintenance->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $vehicle_maintenance->currentDate = $currentDate;
        $vehicle_maintenance->title_type = !empty($SysData['title_type']) ? $SysData['title_type'] : 0;
        $vehicle_maintenance->name = $Username;
        $vehicle_maintenance->booking_status = !empty($SysData['booking_status']) ? $SysData['booking_status'] : 0;
        $vehicle_maintenance->update();

        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = $vehicle_maintenance->id . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
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
                $request->file('registration_papers')->storeAs('Vehicle/registration_papers', $fileName);
                //Update file name in the table
                $vehicle_maintenance->registration_papers = $fileName;
                $vehicle_maintenance->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function viewDetails(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;
        $hrDetails = HRPerson::where('status', 1)->get();
        $images = images::orderBy('id', 'asc')->get();
        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->get();
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $fueltype = array(1 => 'Unleaded', 2 => ' Lead replacement', 3 => ' Diesel');
        $status = array(1 => 'Active', 2 => 'Require Approval', 3 => 'Rejected', 4 => 'Inactive');

        // if ($maintenance->status == 1) {
        $ID = $maintenance->id;
        //return $ID;
        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehiclemake',
                'vehicle_model.name as vehiclemodel', 'vehicle_managemnet.name as vehicletype', 'division_level_fives.name as company', 'division_level_fours.name as Department', 'hr_people.first_name as first_name', 'hr_people.surname as surname'
                , 'contact_companies.name as Vehicle_Owner ')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('hr_people', 'vehicle_details.responsible_for_maintenance', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'vehicle_details.vehicle_owner', '=', 'contact_companies.id')
            ->where('vehicle_details.id', $ID)
            ->orderBy('vehicle_details.id')
            ->get();       

        $registrationPapers = $vehiclemaintenance->first()->registration_papers;

        $vehiclemaintenances = $vehiclemaintenance->first();

        $data['registration_papers'] = (!empty($registrationPapers)) ? Storage::disk('local')->url("Vehicle/registration_papers/$registrationPapers") : '';
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

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
        $data['status'] = $status;
        $data['fueltype'] = $fueltype;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicle_maintenance'] = $vehicle_maintenance;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['divisionLevels'] = $divisionLevels;
        $data['vehicledetail'] = $vehicledetail;
        $data['ContactCompany'] = $ContactCompany;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehiclemaintenances'] = $vehiclemaintenances;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);

        return view('Vehicles.FleetManagement.viewfleetDetails')->with($data);
    }


    public function viewImage(vehicle_maintenance $maintenance)
    {
        //return $maintenance;

        $ID = $maintenance->id;

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
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $ID = $maintenance->id;

        $vehiclemaintenance = vehicle_maintenance::where('id', $ID)->get();
        if (!empty($vehiclemaintenance))
            $vehiclemaintenance = $vehiclemaintenance->load('images');
        //return $vehiclemaintenance;

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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.viewfleetImage')->with($data);
    }

    public function addImages(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            // 'description' => 'required',
            'image' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $userLogged = Auth::user()->load('person');

        $vehicleImages = new images();

        //$vehicleImages->vehicle_maintanace = $ID;

        $vehicleImages->name = $SysData['name'];
        $vehicleImages->description = $SysData['description'];
        $vehicleImages->vehicle_maintanace = $SysData['valueID'];
        $vehicleImages->upload_date = $currentDate;
        $vehicleImages->user_name = $userLogged->id;
        $vehicleImages->default_image = 1;
        $vehicleImages->save();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
                //Update file name in the database
                $vehicleImages->image = $fileName;
                $vehicleImages->update();
            }
        }

        return response()->json();
    }


    public function editImage(Request $request, images $image)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $userLogged = Auth::user()->load('person');

        $image->name = $SysData['name'];
        $image->description = $SysData['description'];
        $image->vehicle_maintanace = $SysData['valueID'];
        $image->upload_date = $currentDate;
        $image->user_name = $userLogged->id;
        $image->default_image = 1;
        //$image->image = $SysData['images'];

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
                //Update file name in the database
                $image->image = $fileName;
                $image->update();
            }
        }

        $image->update();

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function keys(vehicle_maintenance $maintenance)
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
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 


        $ID = $maintenance->id;

        $keytracking = DB::table('keytracking')
            ->select('keytracking.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'hr_people.manager_id as manager', 'safe.name as safeName')
            ->leftJoin('hr_people', 'keytracking.employee', '=', 'hr_people.id')
            ->leftJoin('safe', 'keytracking.safe_name', '=', 'safe.id')
            ->orderBy('keytracking.id')
            ->where('vehicle_id', $ID)
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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.key_tracking')->with($data);
    }


    public function vehiclesAct(Request $request, vehicle_maintenance $vehicleDetails)
    {
        if ($vehicleDetails->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $vehicleDetails->status = $stastus;
        $vehicleDetails->update();
        return back();
    }


    public function addkeys(Request $request)
    {
        $this->validate($request, [

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $datelost = $SysData['date_lost'] = str_replace('/', '-', $SysData['date_lost']);
        $datelost = $SysData['date_lost'] = strtotime($SysData['date_lost']);

        // $issuedby = $SysData['issued_by'];
        // $Employee = HRPerson::where('id', $issuedby)->orderBy('id', 'desc')->get()->first();
        // $name = $Employee->first_name . ' ' . $Employee->surname;

        // $controller = $SysData['safe_controller'];
        // $Employee = HRPerson::where('id', $controller)->orderBy('id', 'desc')->get()->first();
        // $safecontroller = $Employee->first_name . ' ' . $Employee->surname;


        $keytracking = new keytracking();
        $keytracking->key_number = $SysData['key_number'];
        $keytracking->key_type = !empty($SysData['key_type']) ? $SysData['key_type'] : 0;
        $keytracking->key_status = $SysData['key_status'];
        $keytracking->description = $SysData['description'];
        $keytracking->employee = $SysData['key'];
        $keytracking->date_issued = $dates;
        $keytracking->issued_by = !empty($SysData['issued_by']) ? $SysData['issued_by'] : 0;
        $keytracking->safe_name = !empty($SysData['safe_name']) ? $SysData['safe_name'] : 0;
        $keytracking->safe_controller = !empty($SysData['safe_controller']) ? $SysData['safe_controller'] : 0;
        $keytracking->issued_to = !empty($SysData['issued_to']) ? $SysData['issued_to'] : 0;
        $keytracking->date_lost = $datelost;
        $keytracking->reason_loss = $SysData['reason_loss'];
        $keytracking->vehicle_type = 0;
        $keytracking->vehicle_id = $SysData['valueID'];
        $keytracking->captured_by = $SysData['employee'];
        $keytracking->safeController = !empty($SysData['safe_controller']) ? $SysData['safe_controller'] : '';
        $keytracking->save();

        return response()->json();

    }

    public function editKeys(Request $request, keytracking $keytracking)
    {
//        $this->validate($request, [

//        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $datelost = $SysData['date_lost'] = str_replace('/', '-', $SysData['date_lost']);
        $datelost = $SysData['date_lost'] = strtotime($SysData['date_lost']);

        $loggedInEmplID = Auth::user()->person->id;

        $keytracking->key_number = $SysData['key_number'];
        //$keytracking->key_type = $SysData['key_type'];
        $keytracking->key_status = $SysData['key_status'];
        $keytracking->description = $SysData['description'];
        $keytracking->employee = $loggedInEmplID;
        $keytracking->date_issued = $dates;
        $keytracking->issued_by = $SysData['issued_by'];
        // $keytracking->safe_name = $SysData['safe_name'];
        ////$keytracking->safe_controller = $SysData['safe_controller'];
        // $keytracking->issued_to = $SysData['issued_to'];
        $keytracking->date_lost = $datelost;
        $keytracking->reason_loss = $SysData['reason_loss'];
        //$keytracking->vehicle_type =0 ;
        $keytracking->vehicle_id = 1;
        $keytracking->update();
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

##permits

    public function permits_licences(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $companies = ContactCompany::orderBy('name', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();
        $permitlicence = fleet_licence_permit::orderBy('id', 'asc')->get();
       // return $permitlicence;

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

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

        $status = array(1 => 'Active', 2 => 'InActive');


        $ID = $maintenance->id;
        //return $ID;

        $permits = DB::table('permits_licence')
            ->select('permits_licence.*', 'contact_companies.name as comp_name', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'permits_licence.Supplier', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'permits_licence.Supplier', '=', 'contact_companies.id')
            ->orderBy('permits_licence.id')
            ->where('vehicleID', $ID)
            ->get();




        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['permitlicence'] = $permitlicence;
        $data['status'] = $status;
        $data['name'] = $name;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['companies'] = $companies;
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
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.permits')->with($data);
    }

    public function addPermit(Request $request)
    {
        $this->validate($request, [
            'Supplier' => 'required',
            'permits_licence_no' => 'required|unique:permits_licence,permits_licence_no',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);


        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $permits = new permits_licence();
        $permits->permit_licence = !empty($SysData['permit_licence']) ? $SysData['permit_licence'] : 0;
        $permits->Supplier = !empty($SysData['Supplier']) ? $SysData['Supplier'] : 0;
        $permits->exp_date = $Expdate;
        $permits->date_issued = $dates;
        $permits->status = !empty($SysData['status']) ? $SysData['status'] : 1;
        $permits->permits_licence_no = !empty($SysData['permits_licence_no']) ? $SysData['permits_licence_no'] : 0;
        $permits->captured_by = $name;
        $permits->date_captured = $currentDate;
        $permits->vehicleID = $SysData['valueID'];
        $permits->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $permits->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/permits_licence', $fileName);
                //Update file name in the table
                $permits->document = $fileName;
                $permits->update();
            }
        }

        return response()->json();

    }

    public function editPermit(Request $request, permits_licence $permit)
    {
        $this->validate($request, [
            'Supplier' => 'required',
            'permits_licence_no' => 'required|unique:permits_licence,permits_licence_no',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);


        $permit->permit_licence = !empty($SysData['permit_licence']) ? $SysData['permit_licence'] : 0;
        $permit->Supplier = !empty($SysData['Supplier']) ? $SysData['Supplier'] : 0;
        $permit->exp_date = $Expdate;
        $permit->date_issued = $dates;
        $permit->status = !empty($SysData['status']) ? $SysData['status'] : 1;
        $permit->permits_licence_no = !empty($SysData['permits_licence_no']) ? $SysData['permits_licence_no'] : 0;
        $permit->captured_by = $name;
        $permit->date_captured = $currentDate;
        $permit->update();

        
        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $permit->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/permits_licence', $fileName);
                //Update file name in the table
                $permit->document = $fileName;
                $permit->update();
            }
        }
        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function newdocument(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $datefrom = $SysData['date_from'] = str_replace('/', '-', $SysData['date_from']);
        $datefrom = $SysData['date_from'] = strtotime($SysData['date_from']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $currentDate = time();

        $vehicledocumets = new vehicle_documets();
        $vehicledocumets->type = !empty($SysData['type']) ? $SysData['type'] : 0;
        $vehicledocumets->description = $SysData['description'];
        $vehicledocumets->role = !empty($SysData['role']) ? $SysData['role'] : 0;
        $vehicledocumets->date_from = $datefrom;
        $vehicledocumets->exp_date = $Expdate;
        $vehicledocumets->upload_date = $currentDate;
        $vehicledocumets->vehicleID = $SysData['valueID'];
        $vehicledocumets->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $vehicledocumets->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/documents', $fileName);
                //Update file name in the table
                $vehicledocumets->document = $fileName;
                $vehicledocumets->update();
            }
        }

        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();

    }

    public function editVehicleDoc(Request $request, vehicle_documets $vehicledocumets)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $datefrom = $SysData['date_from'] = str_replace('/', '-', $SysData['date_from']);
        $datefrom = $SysData['date_from'] = strtotime($SysData['date_from']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $currentDate = time();

        $vehicledocumets->type = !empty($SysData['type']) ? $SysData['type'] : 0;
        $vehicledocumets->description = $SysData['description'];
        $vehicledocumets->role = !empty($SysData['role']) ? $SysData['role'] : 0;
        $vehicledocumets->date_from = $datefrom;
        $vehicledocumets->exp_date = $Expdate;
        $vehicledocumets->upload_date = $currentDate;
        $vehicledocumets->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $vehicledocumets->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/documents', $fileName);
                //Update file name in the table
                $vehicledocumets->document = $fileName;
                $vehicledocumets->update();
            }
        }

        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();

    }

    public function deleteDoc(vehicle_documets $documents)
    {

        $documents->delete();

        AuditReportsController::store('Fleet Management', 'document  Deleted', "document has been deleted", 0);
        return back();
        //return redirect('/vehicle_management/document/$maintenance->id');
    }

    public function newnotes(Request $request)
    {
        $this->validate($request, [
            // 'issued_to' => 'required_if:key,1',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $datecaptured = $SysData['date_captured'] = str_replace('/', '-', $SysData['date_captured']);
        $datecaptured = $SysData['date_captured'] = strtotime($SysData['date_captured']);
        $currentDate = time();

        $notes = new notes();
        $notes->date_captured = $datecaptured;
        $notes->captured_by = !empty($SysData['captured_by']) ? $SysData['captured_by'] : 0;
        $notes->notes = $SysData['notes'];
        $notes->vehicleID = $SysData['valueID'];
        $notes->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $notes->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/note_documents', $fileName);
                //Update file name in the table
                $notes->documents = $fileName;
                $notes->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();

    }

    public function editNote(Request $request, notes $note)
    {
        $this->validate($request, [

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
    

        $note->date_captured = $currentDate;
        $note->captured_by = !empty($SysData['captured_by']) ? $SysData['captured_by'] : 0;
        $note->notes = $SysData['notes'];
        $notes->vehicleID = $SysData['valueID'];
        $note->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = $note->id . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/note_documents', $fileName);
                //Update file name in the table
                $note->documents = $fileName;
                $note->update();
            }
        }
        AuditReportsController::store('Fleet Managemente', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function deleteNote(notes $note)
    {

        $note->delete();

        AuditReportsController::store('Fleet Management', 'note  Deleted', "document has been deleted", 0);
        return back();
        //return redirect('/vehicle_management/document/$maintenance->id');
    }

}
